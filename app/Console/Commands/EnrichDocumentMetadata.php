<?php

namespace App\Console\Commands;

use App\Services\AI\DocumentEnrichmentService;
use Illuminate\Console\Command;

class EnrichDocumentMetadata extends Command
{
    protected $signature = 'opub:enrich
                            {--limit=50 : Number of documents to enrich per run}
                            {--source= : Filter by source type (open_overheid, raadsinformatie)}
                            {--id= : Enrich a specific document by ID or external_id}
                            {--stats : Show enrichment statistics}';

    protected $description = 'Enrich documents with AI-generated metadata via Gemini (descriptions, keywords, subjects)';

    public function handle(DocumentEnrichmentService $service): int
    {
        // Stats mode
        if ($this->option('stats')) {
            return $this->showStats();
        }

        // Single document mode
        $id = $this->option('id');
        if ($id) {
            return $this->enrichSingle($service, $id);
        }

        // Batch mode
        $this->info('Starting AI metadata enrichment (Gemini)...');
        $this->newLine();

        $limit = (int) $this->option('limit');
        $sourceType = $this->option('source');

        if ($sourceType) {
            $this->line("   Source filter: {$sourceType}");
        }
        $this->line("   Batch size: {$limit}");
        $this->newLine();

        $result = $service->enrichBatch($limit, $sourceType, $this);

        $this->info("   Enriched: {$result['enriched']} documents");
        $this->info("   Fields filled: {$result['fields_enriched']}");

        if ($result['errors'] > 0) {
            $this->warn("   Errors: {$result['errors']}");
        }

        $this->newLine();
        $this->info('Enrichment completed!');

        return self::SUCCESS;
    }

    protected function enrichSingle(DocumentEnrichmentService $service, string $id): int
    {
        $document = \App\Models\OpenOverheidDocument::find($id)
            ?? \App\Models\OpenOverheidDocument::where('external_id', $id)->first();

        if (! $document) {
            $this->error("Document not found: {$id}");

            return self::FAILURE;
        }

        $this->info("Enriching: {$document->title}");

        try {
            $fields = $service->enrichDocument($document);

            if (empty($fields)) {
                $this->info('Document already fully enriched.');
            } else {
                $this->info('Enriched fields: ' . implode(', ', $fields));
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    protected function showStats(): int
    {
        $total = \App\Models\OpenOverheidDocument::count();
        $enriched = \App\Models\OpenOverheidDocument::whereNotNull('ai_enhanced_at')->count();
        $pending = $total - $enriched;

        $bySource = \App\Models\OpenOverheidDocument::selectRaw('source_type, COUNT(*) as total')
            ->selectRaw('COUNT(CASE WHEN ai_enhanced_at IS NOT NULL THEN 1 END) as enriched')
            ->groupBy('source_type')
            ->get();

        $this->info('Document Enrichment Statistics');
        $this->newLine();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total documents', number_format($total)],
                ['AI-enriched', number_format($enriched)],
                ['Pending enrichment', number_format($pending)],
                ['Enrichment %', $total > 0 ? round(($enriched / $total) * 100, 1) . '%' : '0%'],
            ]
        );

        if ($bySource->isNotEmpty()) {
            $this->newLine();
            $this->info('By Source:');
            $rows = $bySource->map(fn ($row) => [
                $row->source_type ?? 'unknown',
                number_format($row->total),
                number_format($row->enriched),
                $row->total > 0 ? round(($row->enriched / $row->total) * 100, 1) . '%' : '0%',
            ])->toArray();

            $this->table(['Source', 'Total', 'Enriched', '%'], $rows);
        }

        // Fields coverage
        $this->newLine();
        $this->info('Field Coverage:');

        $fields = [
            'ai_enhanced_title' => 'AI Titel',
            'ai_description_short' => 'AI Korte omschrijving',
            'ai_description_long' => 'AI Lange omschrijving',
            'ai_summary' => 'AI Samenvatting',
            'ai_keywords' => 'AI Zoekwoorden',
            'ai_subjects' => 'AI Onderwerpen',
        ];

        $rows = [];
        foreach ($fields as $column => $label) {
            $filled = \App\Models\OpenOverheidDocument::whereNotNull($column)->count();
            $rows[] = [
                $label,
                number_format($filled),
                $total > 0 ? round(($filled / $total) * 100, 1) . '%' : '0%',
            ];
        }

        $this->table(['Field', 'Filled', '%'], $rows);

        return self::SUCCESS;
    }
}
