<?php

namespace App\Console\Commands;

use App\Services\Typesense\NewDataTypesenseSyncService;
use Illuminate\Console\Command;

class SyncOooriToTypesense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ooori:sync-typesense
                            {--model=* : Specific model(s) to sync (documents, ori_documents, themes, categories, organisations)}
                            {--limit= : Limit number of documents to sync (only for documents/ori_documents)}
                            {--all : Sync all models}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Ooori models (OverheidDocument, OriDocument, themes, categories, organisations) to Typesense';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Starting Ooori Typesense sync...');
        $this->newLine();

        $service = app(NewDataTypesenseSyncService::class);

        $models = $this->option('model');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $syncAll = $this->option('all') || empty($models);

        if ($syncAll) {
            $this->info('Syncing all models...');
            $result = $service->syncAll(null, $this);
        } else {
            $this->info('Syncing models: '.implode(', ', $models));
            
            // Handle limit for document models
            if ($limit && in_array('documents', $models)) {
                $docResult = $service->syncDocuments($this, $limit);
                $otherModels = array_diff($models, ['documents']);
                
                if (!empty($otherModels)) {
                    $otherResult = $service->syncAll($otherModels, $this);
                    $result = [
                        'total' => $docResult['total'] + $otherResult['total'],
                        'synced' => $docResult['synced'] + $otherResult['synced'],
                        'errors' => $docResult['errors'] + $otherResult['errors'],
                    ];
                } else {
                    $result = $docResult;
                }
            } elseif ($limit && in_array('ori_documents', $models)) {
                $oriResult = $service->syncOriDocuments($this, $limit);
                $otherModels = array_diff($models, ['ori_documents']);
                
                if (!empty($otherModels)) {
                    $otherResult = $service->syncAll($otherModels, $this);
                    $result = [
                        'total' => $oriResult['total'] + $otherResult['total'],
                        'synced' => $oriResult['synced'] + $otherResult['synced'],
                        'errors' => $oriResult['errors'] + $otherResult['errors'],
                    ];
                } else {
                    $result = $oriResult;
                }
            } else {
                $result = $service->syncAll($models, $this);
            }
        }

        $this->newLine();
        $this->info('✅ Sync completed!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $result['total']],
                ['Successfully Synced', $result['synced']],
                ['Errors', $result['errors']],
            ]
        );

        if ($result['errors'] > 0) {
            $this->warn("⚠️  {$result['errors']} errors occurred. Check logs for details.");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
