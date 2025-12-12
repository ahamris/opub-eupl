<?php

namespace App\Jobs;

use App\Models\OpenOverheidDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrecomputeDossierMetadataJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $dossierExternalId
    ) {}

    /**
     * Execute the job.
     * Pre-computes metadata AND generates AI-content for the dossier.
     */
    public function handle(): void
    {
        try {
            $document = OpenOverheidDocument::where('external_id', $this->dossierExternalId)->first();

            if (! $document) {
                Log::warning('Dossier document not found for metadata pre-compute', [
                    'external_id' => $this->dossierExternalId,
                ]);

                return;
            }

            // Get all documents in this dossier
            $members = $document->getDossierMembers();
            $allDocuments = $members->push($document)->unique('id');

            // Calculate metadata
            $memberCount = $allDocuments->count();
            $publicationDates = $allDocuments->pluck('publication_date')->filter();
            $latestDate = $publicationDates->max();
            $earliestDate = $publicationDates->min();

            // Calculate status (actief if latest document < 2 years ago)
            $twoYearsAgo = now()->subYears(2);
            $status = ($latestDate && $latestDate->gte($twoYearsAgo)) ? 'actief' : 'gesloten';

            // Store or update metadata
            DB::table('dossier_metadata')->updateOrInsert(
                ['dossier_external_id' => $this->dossierExternalId],
                [
                    'status' => $status,
                    'member_count' => $memberCount,
                    'latest_publication_date' => $latestDate?->format('Y-m-d'),
                    'earliest_publication_date' => $earliestDate?->format('Y-m-d'),
                    'organisation' => $document->organisation,
                    'category' => $document->category,
                    'theme' => $document->theme,
                    'computed_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Generate AI-content (title, summary, audio) - ALTIJD voor toegankelijkheid
            $enhancementService = app(\App\Services\AI\DossierEnhancementService::class);
            $enhancementService->enhanceDossier($this->dossierExternalId);

            Log::info('Dossier fully processed (metadata + AI-content)', [
                'dossier_external_id' => $this->dossierExternalId,
                'member_count' => $memberCount,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to pre-compute dossier metadata and AI-content', [
                'dossier_external_id' => $this->dossierExternalId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
