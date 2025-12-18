<?php

namespace App\Services\AI;

use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DossierEnhancementService
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Enhance a dossier (all documents in a dossier group)
     */
    public function enhanceDossier(string $dossierExternalId): bool
    {
        try {
            $document = OpenOverheidDocument::where('external_id', $dossierExternalId)->first();

            if (! $document) {
                Log::channel('ai_enhancement')->warning('Dossier document not found', ['external_id' => $dossierExternalId]);

                return false;
            }

            // Get all documents in this dossier
            $dossierMembers = $document->getDossierMembers();
            $allDocuments = $dossierMembers->push($document)->unique('id');

            if ($allDocuments->isEmpty()) {
                Log::channel('ai_enhancement')->warning('No documents found in dossier', ['external_id' => $dossierExternalId]);

                return false;
            }

            // Generate dossier summary
            $summary = $this->geminiService->summarizeDossier($allDocuments->toArray());

            // Generate enhanced title (use main document title as base)
            $enhancedTitle = $this->geminiService->enhanceTitle(
                $document->title ?? 'Dossier',
                $summary
            );

            // Extract keywords from all documents
            $combinedText = $allDocuments->map(function ($doc) {
                return ($doc->title ?? '').' '.($doc->description ?? '').' '.mb_substr($doc->content ?? '', 0, 500);
            })->implode(' ');

            $keywords = $this->geminiService->extractKeywords($combinedText, 15);

            // Always generate audio for accessibility (digitoegankelijkheid)
            $audioUrl = null;
            $audioDuration = null;
            if ($summary) {
                $audioUrl = $this->geminiService->generateAudio($summary);
                // Estimate: ~150 words per minute, average reading speed
                $wordCount = str_word_count($summary);
                $audioDuration = (int) ceil(($wordCount / 150) * 60); // in seconds
            } elseif ($allDocuments->isNotEmpty()) {
                // If no summary yet, generate a basic audio from document titles
                $titles = $allDocuments->pluck('title')->filter()->take(5)->implode('. ');
                if (! empty($titles)) {
                    $audioUrl = $this->geminiService->generateAudio($titles);
                    $wordCount = str_word_count($titles);
                    $audioDuration = (int) ceil(($wordCount / 150) * 60);
                }
            }

            // Store or update dossier AI content
            DB::table('dossier_ai_content')->updateOrInsert(
                ['dossier_external_id' => $dossierExternalId],
                [
                    'summary' => $summary,
                    'enhanced_title' => $enhancedTitle,
                    'keywords' => json_encode($keywords),
                    'audio_url' => $audioUrl,
                    'audio_duration_seconds' => $audioDuration,
                    'generated_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Also enhance individual documents in the dossier (optional, can be done separately)
            // foreach ($allDocuments as $doc) {
            //     $this->enhanceDocument($doc);
            // }

            Log::channel('ai_enhancement')->info('Dossier enhanced successfully', [
                'dossier_external_id' => $dossierExternalId,
                'documents_count' => $allDocuments->count(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::channel('ai_enhancement')->error('Dossier enhancement failed', [
                'dossier_external_id' => $dossierExternalId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Enhance a single document (title and description)
     */
    public function enhanceDocument(OpenOverheidDocument $document): bool
    {
        try {
            // Enhance title
            $enhancedTitle = $this->geminiService->enhanceTitle(
                $document->title ?? '',
                $document->description
            );

            // Enhance description
            $enhancedDescription = $this->geminiService->enhanceDescription(
                $document->description ?? '',
                mb_substr($document->content ?? '', 0, 2000)
            );

            // Extract keywords
            $textForKeywords = ($document->title ?? '').' '.($document->description ?? '').' '.mb_substr($document->content ?? '', 0, 1000);
            $keywords = $this->geminiService->extractKeywords($textForKeywords, 10);

            // Update document
            $document->update([
                'ai_enhanced_title' => $enhancedTitle,
                'ai_enhanced_description' => $enhancedDescription,
                'ai_keywords' => $keywords,
                'ai_enhanced_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::channel('ai_enhancement')->error('Document enhancement failed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get or generate dossier summary
     */
    public function getOrGenerateSummary(string $dossierExternalId): ?string
    {
        $aiContent = DB::table('dossier_ai_content')
            ->where('dossier_external_id', $dossierExternalId)
            ->first();

        if ($aiContent && ! empty($aiContent->summary)) {
            return $aiContent->summary;
        }

        // Generate if not exists
        $this->enhanceDossier($dossierExternalId);

        // Try again
        $aiContent = DB::table('dossier_ai_content')
            ->where('dossier_external_id', $dossierExternalId)
            ->first();

        return $aiContent->summary ?? null;
    }

    /**
     * Get or generate dossier audio
     */
    public function getOrGenerateAudio(string $dossierExternalId): ?string
    {
        $aiContent = DB::table('dossier_ai_content')
            ->where('dossier_external_id', $dossierExternalId)
            ->first();

        if ($aiContent && ! empty($aiContent->audio_url)) {
            return $aiContent->audio_url;
        }

        // Generate if not exists (requires summary first)
        $summary = $this->getOrGenerateSummary($dossierExternalId);

        if (empty($summary)) {
            return null;
        }

        $this->enhanceDossier($dossierExternalId);

        // Try again
        $aiContent = DB::table('dossier_ai_content')
            ->where('dossier_external_id', $dossierExternalId)
            ->first();

        return $aiContent->audio_url ?? null;
    }

    /**
     * Get dossier AI content (summary, title, keywords, audio)
     */
    public function getDossierAiContent(string $dossierExternalId): ?object
    {
        return DB::table('dossier_ai_content')
            ->where('dossier_external_id', $dossierExternalId)
            ->first();
    }
}
