<?php

use App\Jobs\EnhanceDossierJob;
use App\Models\OpenOverheidDocument;
use App\Services\AI\DossierEnhancementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

it('dispatches enhance dossier job', function () {
    Queue::fake();

    EnhanceDossierJob::dispatch('test-dossier-id');

    Queue::assertPushed(EnhanceDossierJob::class, function ($job) {
        return $job->dossierExternalId === 'test-dossier-id';
    });
});

it('processes enhance dossier job', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'test-dossier-123',
    ]);

    $enhancementService = Mockery::mock(DossierEnhancementService::class);
    $enhancementService->shouldReceive('enhanceDossier')
        ->once()
        ->with('test-dossier-123')
        ->andReturn(true);

    app()->instance(DossierEnhancementService::class, $enhancementService);

    $job = new EnhanceDossierJob('test-dossier-123');
    $job->handle($enhancementService);

    // Job should complete without exceptions
    expect(true)->toBeTrue();
});
