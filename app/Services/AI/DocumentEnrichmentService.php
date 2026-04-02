<?php

namespace App\Services\AI;

use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;

class DocumentEnrichmentService
{
    public function __construct(
        protected GeminiService $gemini
    ) {}

    /**
     * Enrich a single document with AI-generated metadata.
     * Only fills fields that are currently empty.
     *
     * @return array Fields that were enriched
     */
    public function enrichDocument(OpenOverheidDocument $document): array
    {
        $enriched = [];
        $updates = [];

        // 1. AI title (B1 level) — when title exists but ai_enhanced_title is missing
        if ($document->title && ! $document->ai_enhanced_title) {
            $aiTitle = $this->gemini->enhanceTitle($document->title, $document->description);
            if ($aiTitle) {
                $updates['ai_enhanced_title'] = $aiTitle;
                $enriched[] = 'ai_enhanced_title';
            }
        }

        // 2. Short description — when source lacks a description
        if (! $document->description && ! $document->ai_description_short) {
            $short = $this->gemini->generateDescriptionShort(
                $document->title ?? '',
                $document->content
            );
            if ($short) {
                $updates['ai_description_short'] = $short;
                $enriched[] = 'ai_description_short';
            }
        }

        // 3. Long description — always generate if missing, based on content
        if (! $document->ai_description_long && ($document->content || $document->description)) {
            $long = $this->gemini->generateDescriptionLong(
                $document->title ?? '',
                $document->content,
                $document->description
            );
            if ($long) {
                $updates['ai_description_long'] = $long;
                $enriched[] = 'ai_description_long';
            }
        }

        // 4. Keywords — when source + AI keywords are both missing
        if (empty($document->ai_keywords)) {
            $text = $document->content ?? $document->description ?? $document->title ?? '';
            if ($text) {
                $keywords = $this->gemini->extractKeywords($text);
                if (! empty($keywords)) {
                    $updates['ai_keywords'] = $keywords;
                    $enriched[] = 'ai_keywords';
                }
            }
        }

        // 5. Subjects — when source subjects + AI subjects are both missing
        if (empty($document->subjects) && empty($document->ai_subjects)) {
            $text = $document->content ?? $document->description ?? '';
            if ($text) {
                $subjects = $this->gemini->extractSubjects($text, $document->title);
                if (! empty($subjects)) {
                    $updates['ai_subjects'] = $subjects;
                    $enriched[] = 'ai_subjects';
                }
            }
        }

        // 6. AI summary — when missing
        if (! $document->ai_summary && ($document->content || $document->description)) {
            $summary = $this->gemini->enhanceDescription(
                $document->description ?? $document->title ?? '',
                $document->content
            );
            if ($summary) {
                $updates['ai_summary'] = $summary;
                $enriched[] = 'ai_summary';
            }
        }

        // Apply updates
        if (! empty($updates)) {
            $updates['ai_enhanced_at'] = now();
            $document->update($updates);
        }

        return $enriched;
    }

    /**
     * Batch enrich documents that are missing AI metadata.
     */
    public function enrichBatch(int $limit = 50, ?string $sourceType = null, $command = null): array
    {
        $query = OpenOverheidDocument::query()
            ->whereNull('ai_enhanced_at')
            ->where(function ($q) {
                // At least has a title or content to work with
                $q->whereNotNull('title')
                  ->orWhereNotNull('content');
            })
            ->orderBy('publication_date', 'desc');

        if ($sourceType) {
            $query->where('source_type', $sourceType);
        }

        $documents = $query->limit($limit)->get();
        $total = $documents->count();

        if ($total === 0) {
            $command?->info('No documents need enrichment.');

            return ['total' => 0, 'enriched' => 0, 'errors' => 0];
        }

        $command?->info("Enriching {$total} documents with AI metadata...");

        if ($command) {
            $bar = $command->getOutput()->createProgressBar($total);
            $bar->start();
        }

        $stats = ['enriched' => 0, 'errors' => 0, 'fields_enriched' => 0];

        foreach ($documents as $document) {
            try {
                $fields = $this->enrichDocument($document);

                if (! empty($fields)) {
                    $stats['enriched']++;
                    $stats['fields_enriched'] += count($fields);
                }
            } catch (\Exception $e) {
                Log::channel('ai_enhancement')->error('Document enrichment failed', [
                    'document_id' => $document->id,
                    'external_id' => $document->external_id,
                    'error' => $e->getMessage(),
                ]);
                $stats['errors']++;
            }

            if (isset($bar)) {
                $bar->advance();
            }
        }

        if (isset($bar)) {
            $bar->finish();
            $command?->newLine(2);
        }

        return [
            'total' => $total,
            ...$stats,
        ];
    }
}
