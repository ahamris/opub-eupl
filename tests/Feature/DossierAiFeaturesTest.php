<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

it('displays enhance button when no AI content exists', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-enhance',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    $response = $this->get("/dossiers/{$document->external_id}");

    $response->assertStatus(200);
    $response->assertSee('Maak AI-samenvatting', false);
});

it('displays AI summary when exists', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-summary',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    DB::table('dossier_ai_content')->insert([
        'dossier_external_id' => $document->external_id,
        'summary' => 'Dit is een AI samenvatting',
        'enhanced_title' => 'Verbeterde titel',
        'generated_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get("/dossiers/{$document->external_id}");

    $response->assertStatus(200);
    $response->assertSee('Dit is een AI samenvatting', false);
    $response->assertSee('Verbeterde titel', false);
    $response->assertDontSee('Maak AI-samenvatting', false);
});

it('displays audio player when audio exists', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-audio',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    DB::table('dossier_ai_content')->insert([
        'dossier_external_id' => $document->external_id,
        'summary' => 'Samenvatting',
        'audio_url' => '/storage/audio/test.mp3',
        'audio_duration_seconds' => 120,
        'generated_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get("/dossiers/{$document->external_id}");

    $response->assertStatus(200);
    $response->assertSee('audio controls', false);
    $response->assertSee('/storage/audio/test.mp3', false);
});

it('dispatches enhance job when button clicked', function () {
    Queue::fake();

    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-job',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    $response = $this->post("/dossiers/{$document->external_id}/enhance");

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'processing',
    ]);

    Queue::assertPushed(\App\Jobs\EnhanceDossierJob::class, function ($job) use ($document) {
        return $job->dossierExternalId === $document->external_id;
    });
});

it('returns summary via API endpoint', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-api',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    DB::table('dossier_ai_content')->insert([
        'dossier_external_id' => $document->external_id,
        'summary' => 'API samenvatting',
        'generated_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get("/dossiers/{$document->external_id}/summary");

    $response->assertStatus(200);
    $response->assertJson([
        'summary' => 'API samenvatting',
        'status' => 'ready',
    ]);
});

it('returns audio URL via API endpoint', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-audio-api',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    DB::table('dossier_ai_content')->insert([
        'dossier_external_id' => $document->external_id,
        'summary' => 'Samenvatting',
        'audio_url' => '/storage/audio/api.mp3',
        'audio_duration_seconds' => 180,
        'generated_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get("/dossiers/{$document->external_id}/audio");

    $response->assertStatus(200);
    $response->assertJson([
        'audio_url' => '/storage/audio/api.mp3',
        'duration_seconds' => 180,
        'status' => 'ready',
    ]);
});

it('shows enhanced title in dossier header', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-enhanced-title',
        'title' => 'Original Title',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    DB::table('dossier_ai_content')->insert([
        'dossier_external_id' => $document->external_id,
        'enhanced_title' => 'AI Enhanced Title',
        'generated_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get("/dossiers/{$document->external_id}");

    $response->assertStatus(200);
    $response->assertSee('AI Enhanced Title', false);
    // Enhanced title should be shown, but original might also be in page
});

it('displays keywords when available', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-keywords',
        'metadata' => [
            'documentrelaties' => [],
        ],
    ]);

    DB::table('dossier_ai_content')->insert([
        'dossier_external_id' => $document->external_id,
        'summary' => 'Summary',
        'keywords' => json_encode(['overheid', 'transparantie', 'document']),
        'generated_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get("/dossiers/{$document->external_id}");

    $response->assertStatus(200);
    $response->assertSee('overheid', false);
    $response->assertSee('transparantie', false);
    $response->assertSee('document', false);
});
