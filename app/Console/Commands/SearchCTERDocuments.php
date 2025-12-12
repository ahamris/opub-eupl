<?php

namespace App\Console\Commands;

use App\Models\OpenOverheidDocument;
use Illuminate\Console\Command;

class SearchCTERDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:cter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for documents related to CTER (Contraterrorisme, Extremisme en Radicalisering)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Zoeken naar documenten gerelateerd aan CTER, Contraterrorisme, Extremisme en Radicalisering...');
        $this->newLine();

        // Search terms
        $searchTerms = [
            'CTER',
            'contraterrorisme',
            'contraterrorisme',
            'terrorisme',
            'extremisme',
            'radicalisering',
            'NCTV',
            'AIVD',
            'CT Infobox',
            'CTER-registraties',
        ];

        $allDocuments = collect();

        foreach ($searchTerms as $term) {
            $this->info("Zoeken naar: {$term}...");

            $documents = OpenOverheidDocument::query()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'ilike', "%{$term}%")
                        ->orWhere('description', 'ilike', "%{$term}%")
                        ->orWhere('content', 'ilike', "%{$term}%")
                        ->orWhere('theme', 'ilike', "%{$term}%")
                        ->orWhere('category', 'ilike', "%{$term}%");
                })
                ->get();

            if ($documents->isNotEmpty()) {
                $this->info("  Gevonden: {$documents->count()} document(en)");
                $allDocuments = $allDocuments->merge($documents);
            } else {
                $this->line('  Geen documenten gevonden');
            }
        }

        // Remove duplicates based on external_id
        $uniqueDocuments = $allDocuments->unique('external_id');

        $this->newLine();
        $this->info("Totaal aantal unieke documenten gevonden: {$uniqueDocuments->count()}");
        $this->newLine();

        if ($uniqueDocuments->isEmpty()) {
            $this->warn('Geen documenten gevonden die gerelateerd zijn aan CTER.');

            return Command::SUCCESS;
        }

        // Display results
        $this->table(
            ['ID', 'Titel', 'Publicatiedatum', 'Organisatie', 'Thema'],
            $uniqueDocuments->map(function ($doc) {
                return [
                    $doc->external_id,
                    substr($doc->title ?? 'Geen titel', 0, 60).'...',
                    $doc->publication_date?->format('Y-m-d') ?? 'Onbekend',
                    $doc->organisation ?? 'Onbekend',
                    $doc->theme ?? 'Onbekend',
                ];
            })->toArray()
        );

        // Save to file
        $outputFile = storage_path('app/cter_documents.json');
        file_put_contents(
            $outputFile,
            json_encode($uniqueDocuments->map(function ($doc) {
                return [
                    'external_id' => $doc->external_id,
                    'title' => $doc->title,
                    'description' => $doc->description,
                    'publication_date' => $doc->publication_date?->format('Y-m-d'),
                    'document_type' => $doc->document_type,
                    'category' => $doc->category,
                    'theme' => $doc->theme,
                    'organisation' => $doc->organisation,
                    'metadata' => $doc->metadata,
                    'url' => $doc->metadata['weblocatie'] ?? $doc->metadata['pid'] ?? null,
                ];
            })->values()->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $this->newLine();
        $this->info("Documenten opgeslagen in: {$outputFile}");

        return Command::SUCCESS;
    }
}
