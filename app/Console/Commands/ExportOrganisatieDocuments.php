<?php

namespace App\Console\Commands;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Services\OpenOverheid\OpenOverheidSearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExportOrganisatieDocuments extends Command
{
    protected $signature = 'open-overheid:export-organisatie 
                            {organisatie : De naam van de organisatie (bijv. "ministerie van Justitie en Veiligheid")}
                            {--output= : Output bestandspad (default: storage/app/exports/organisatie_documents.json)}
                            {--count-only : Alleen het aantal documenten tonen zonder te exporteren}
                            {--limit= : Beperk het aantal documenten om te exporteren (voor testen)}';

    protected $description = 'Export alle detailed document data voor een organisatie naar een flat JSON file';

    public function handle(OpenOverheidSearchService $service): int
    {
        $organisatie = $this->argument('organisatie');
        $outputPath = $this->option('output')
            ? storage_path('app/'.ltrim($this->option('output'), '/'))
            : storage_path('app/exports/organisatie_documents.json');
        $countOnly = $this->option('count-only');

        // Ensure output directory exists
        $outputDir = dirname($outputPath);
        if (! is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $this->info("Zoeken naar documenten voor organisatie: {$organisatie}");

        // First, get the total count
        $query = new OpenOverheidSearchQuery(
            zoektekst: '',
            page: 1,
            perPage: 50,
            organisatie: $organisatie
        );

        $result = $service->search($query);
        $total = $result['totaal'] ?? 0;

        $this->info("Totaal aantal documenten gevonden: {$total}");

        if ($countOnly) {
            return self::SUCCESS;
        }

        if ($total === 0) {
            $this->warn('Geen documenten gevonden om te exporteren.');

            return self::SUCCESS;
        }

        // Apply limit if specified (for testing)
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        if ($limit && $limit < $total) {
            $total = $limit;
            $this->info("Beperkt tot {$limit} documenten voor test doeleinden.");
        }

        // Calculate number of pages (50 per page is max)
        $perPage = 50;
        $totalPages = (int) ceil($total / $perPage);

        $this->info("Bezig met ophalen van {$total} documenten in {$totalPages} pagina's...");
        $this->newLine();

        $allDocuments = [];
        $currentPage = 1;
        $successCount = 0;
        $errorCount = 0;
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        while ($currentPage <= $totalPages) {
            try {
                $query = new OpenOverheidSearchQuery(
                    zoektekst: '',
                    page: $currentPage,
                    perPage: $perPage,
                    organisatie: $organisatie
                );

                $result = $service->search($query);
                $items = $result['resultaten'] ?? [];

                if (empty($items)) {
                    break;
                }

                // For each item, fetch detailed document
                foreach ($items as $item) {
                    try {
                        // Extract document ID from search result
                        $documentId = $this->extractDocumentId($item);

                        if (! $documentId) {
                            $this->newLine();
                            $this->warn('Kon document ID niet vinden voor item:');
                            $this->line(json_encode($item, JSON_PRETTY_PRINT));
                            $errorCount++;
                            $progressBar->advance();

                            continue;
                        }

                        // Fetch detailed document
                        $documentDetail = $service->getDocument($documentId);

                        // Flatten the document - combine all data into a single flat object
                        $flatDocument = $this->flattenDocument($documentDetail, $documentId);

                        $allDocuments[] = $flatDocument;
                        $successCount++;

                    } catch (\Exception $e) {
                        $errorCount++;
                        Log::error('Error fetching document detail', [
                            'item' => $item,
                            'exception' => $e->getMessage(),
                        ]);
                    }

                    $progressBar->advance();
                }

                $currentPage++;

                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error op pagina {$currentPage}: ".$e->getMessage());
                Log::error('Error fetching page', [
                    'page' => $currentPage,
                    'exception' => $e->getMessage(),
                ]);
                $currentPage++;
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Save to JSON file
        $this->info("Opslaan naar: {$outputPath}");
        file_put_contents($outputPath, json_encode($allDocuments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $fileSize = number_format(filesize($outputPath) / 1024 / 1024, 2);

        $this->info('✅ Export voltooid!');
        $this->table(
            ['Metric', 'Waarde'],
            [
                ['Totaal documenten', number_format($total)],
                ['Succesvol opgehaald', number_format($successCount)],
                ['Fouten', number_format($errorCount)],
                ['Bestandsgrootte', $fileSize.' MB'],
                ['Output bestand', $outputPath],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Extract document ID from search result item
     */
    private function extractDocumentId(array $item): ?string
    {
        // Try different possible locations for the document ID
        $id = $item['document']['id'] ?? null;

        if ($id) {
            // Remove suffix like "_1" if present
            return preg_replace('/_\d+$/', '', $id);
        }

        // Try pid - extract last segment
        $pid = $item['document']['pid'] ?? null;
        if ($pid) {
            $parts = explode('/', rtrim($pid, '/'));

            return end($parts);
        }

        // Try other possible ID fields
        return $item['id'] ?? $item['dcnId'] ?? $item['document']['dcnId'] ?? null;
    }

    /**
     * Flatten a document detail response into a single flat object
     */
    private function flattenDocument(array $documentDetail, string $documentId): array
    {
        $flat = [
            '_document_id' => $documentId,
            '_exported_at' => now()->toIso8601String(),
        ];

        // Recursively flatten all nested arrays and objects
        $this->flattenArray($documentDetail, $flat, '');

        return $flat;
    }

    /**
     * Recursively flatten nested arrays/objects with dot notation
     */
    private function flattenArray(array $array, array &$flat, string $prefix): void
    {
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                // Check if it's a sequential array (list) or associative array (object)
                if ($this->isSequentialArray($value)) {
                    // For arrays, store as JSON string or indexed keys
                    $flat[$newKey] = json_encode($value, JSON_UNESCAPED_UNICODE);
                } else {
                    // Recursively flatten associative arrays
                    $this->flattenArray($value, $flat, $newKey);
                }
            } else {
                $flat[$newKey] = $value;
            }
        }
    }

    /**
     * Check if array is sequential (list) vs associative (object)
     */
    private function isSequentialArray(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
