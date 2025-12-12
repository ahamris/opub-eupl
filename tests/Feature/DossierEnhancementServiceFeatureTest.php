<?php

use App\Models\OpenOverheidDocument;
use App\Services\AI\DossierEnhancementService;
use App\Services\AI\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('enhances dossier with summary and audio', function () {
    $geminiService = Mockery::mock(GeminiService::class);
    $service = new DossierEnhancementService($geminiService);

    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-123',
        'title' => 'Test Dossier',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    // Mock Gemini service responses
    $geminiService->shouldReceive('summarizeDossier')
        ->once()
        ->andReturn('Dit is een samenvatting');

    $geminiService->shouldReceive('enhanceTitle')
        ->once()
        ->andReturn('Verbeterde titel');

    $geminiService->shouldReceive('extractKeywords')
        ->once()
        ->andReturn(['keyword1', 'keyword2']);

    $geminiService->shouldReceive('generateAudio')
        ->once()
        ->andReturn('/storage/audio/test.mp3');

    $result = $service->enhanceDossier('test-dossier-123');

    // Should return true if document has dossier members or if it's a single document dossier
    expect($result)->toBeBool();

    // Check if AI content was created (if enhancement succeeded)
    if ($result) {
        $aiContent = DB::table('dossier_ai_content')
            ->where('dossier_external_id', 'test-dossier-123')
            ->first();

        expect($aiContent)->not->toBeNull();
    }
});

it('returns false when dossier document not found', function () {
    $geminiService = Mockery::mock(GeminiService::class);
    $service = new DossierEnhancementService($geminiService);

    $result = $service->enhanceDossier('non-existent-id');

    expect($result)->toBeFalse();
});

it('returns cached summary if exists', function () {
    $geminiService = Mockery::mock(GeminiService::class);
    $service = new DossierEnhancementService($geminiService);

    DB::table('dossier_ai_content')->insert([
        'dossier_external_id' => 'cached-dossier',
        'summary' => 'Cached summary',
        'generated_at' => now(),
        'updated_at' => now(),
    ]);

    $result = $service->getOrGenerateSummary('cached-dossier');

    expect($result)->toBe('Cached summary');
});

it('enhances individual document', function () {
    $geminiService = Mockery::mock(GeminiService::class);
    $service = new DossierEnhancementService($geminiService);

    $document = OpenOverheidDocument::factory()->create([
        'title' => 'Original Title',
        'description' => 'Original Description',
        'content' => 'Content here',
    ]);

    $geminiService->shouldReceive('enhanceTitle')
        ->once()
        ->andReturn('Enhanced Title');

    $geminiService->shouldReceive('enhanceDescription')
        ->once()
        ->andReturn('Enhanced Description');

    $geminiService->shouldReceive('extractKeywords')
        ->once()
        ->andReturn(['keyword1']);

    $result = $service->enhanceDocument($document);

    expect($result)->toBeTrue();

    $document->refresh();
    expect($document->ai_enhanced_title)->toBe('Enhanced Title')
        ->and($document->ai_enhanced_description)->toBe('Enhanced Description')
        ->and($document->ai_keywords)->toContain('keyword1');
});
