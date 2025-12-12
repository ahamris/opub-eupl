<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EnhanceDossierJob implements ShouldQueue
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
     */
    public function handle(\App\Services\AI\DossierEnhancementService $enhancementService): void
    {
        $enhancementService->enhanceDossier($this->dossierExternalId);
    }
}
