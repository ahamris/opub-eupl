# Archived: High-Load Sync Commands

## Status: DISABLED

These commands have been **archived and disabled** due to high load on the main application. They were moved to this folder on **January 12, 2026**.

## Archived Commands & Jobs

### Commands

1. **`open-overheid:sync`** - `SyncOpenOverheidDocuments.php`
   - Synchronizes Open Overheid documents from API to PostgreSQL and Typesense
   - Was scheduled to run daily at 2:00 AM

2. **`typesense:sync`** - `SyncTypesense.php`
   - Synchronizes PostgreSQL documents to Typesense search index
   - Was scheduled to run every minute

### Jobs

1. **`SyncOpenOverheidDocumentsJob`** - `app/Jobs/Archived/SyncOpenOverheidDocumentsJob.php`
   - Queue job for syncing all Open Overheid documents
   - Was scheduled as an alternative to the command

2. **`SyncDocumentToTypesense`** - `app/Jobs/Archived/SyncDocumentToTypesense.php`
   - Queue job for syncing documents to Typesense (processes up to 100 documents per run)
   - Was scheduled to run every minute
   - Also used by the Data Management dashboard (now disabled)

## Reason for Archiving

Both commands were causing high load on the main application server. These commands will be moved to a separate service/project in the future to handle document synchronization independently.

## What Was Disabled

### SyncOpenOverheidDocuments Command

1. **Command File**: Moved from `app/Console/Commands/SyncOpenOverheidDocuments.php` to `app/Console/Archived/SyncOpenOverheidDocuments.php`
2. **Scheduled Execution**: Commented out in `routes/console.php` (lines 15-26)
3. **Namespace**: Updated to `App\Console\Archived`

### SyncTypesense Command

1. **Command File**: Moved from `app/Console/Commands/SyncTypesense.php` to `app/Console/Archived/SyncTypesense.php`
2. **Scheduled Execution**: Commented out in `routes/console.php` (lines 38-48)
3. **Namespace**: Updated to `App\Console\Archived`

### SyncOpenOverheidDocumentsJob

1. **Job File**: Moved from `app/Jobs/SyncOpenOverheidDocumentsJob.php` to `app/Jobs/Archived/SyncOpenOverheidDocumentsJob.php`
2. **Scheduled Execution**: Commented out in `routes/console.php` (lines 30-36)
3. **Namespace**: Updated to `App\Jobs\Archived`

### SyncDocumentToTypesense Job

1. **Job File**: Moved from `app/Jobs/SyncDocumentToTypesense.php` to `app/Jobs/Archived/SyncDocumentToTypesense.php`
2. **Scheduled Execution**: Commented out in `routes/console.php` (lines 43-52)
3. **Livewire Component**: Updated `app/Livewire/Admin/TypesenseSyncStatus.php` to disable manual trigger
4. **Namespace**: Updated to `App\Jobs\Archived`

## How to Re-enable These Commands

If you need to temporarily re-enable these commands, follow these steps for each command:

### Re-enabling SyncOpenOverheidDocuments

#### Step 1: Move the Command Back

```bash
# Move the file back
mv app/Console/Archived/SyncOpenOverheidDocuments.php app/Console/Commands/SyncOpenOverheidDocuments.php
```

#### Step 2: Update the Namespace

Edit `app/Console/Commands/SyncOpenOverheidDocuments.php` and change:

```php
namespace App\Console\Archived;
```

Back to:

```php
namespace App\Console\Commands;
```

Also remove the archived comment block at the top of the class.

#### Step 3: Re-enable Scheduled Execution

Edit `routes/console.php` and uncomment the scheduled command (lines 15-26):

```php
// Schedule Open Overheid sync command to run daily at 2 AM
// Syncs last 7 days by default
Schedule::command('open-overheid:sync --recent --days=7')
    ->dailyAt('02:00')
    ->name('sync-open-overheid-documents')
    ->withoutOverlapping()
    ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
        \Log::channel('sync_errors')->error('Open Overheid sync command failed', [
            'command' => $event->command,
            'exit_code' => $event->exitCode,
            'output' => $event->output ?? 'No output captured',
        ]);
    })
    ->appendOutputTo(storage_path('logs/sync-command-output.log'));
```

### Re-enabling SyncTypesense

#### Step 1: Move the Command Back

```bash
# Move the file back
mv app/Console/Archived/SyncTypesense.php app/Console/Commands/SyncTypesense.php
```

#### Step 2: Update the Namespace

Edit `app/Console/Commands/SyncTypesense.php` and change:

```php
namespace App\Console\Archived;
```

Back to:

```php
namespace App\Console\Commands;
```

Also remove the archived comment block at the top of the class.

#### Step 3: Re-enable Scheduled Execution

Edit `routes/console.php` and uncomment the scheduled command (lines 38-48):

```php
// Schedule Typesense sync every minute
Schedule::command('typesense:sync')
    ->everyMinute()
    ->name('sync-document-to-typesense')
    ->withoutOverlapping()
    ->onFailure(function (\Illuminate\Console\Scheduling\Event $event) {
        \Log::channel('typesense_errors')->error('Typesense sync command failed', [
            'command' => $event->command,
            'exit_code' => $event->exitCode,
        ]);
    });
```

### Step 4: Clear Cache (for both commands)

Clear Laravel's command cache:

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 5: Verify (for both commands)

Test that the commands are available:

```bash
php artisan list | grep open-overheid
php artisan list | grep typesense
```

You should see both commands in the list.

### Re-enabling Jobs

If you need to re-enable the jobs, follow these steps:

#### Step 1: Move the Jobs Back

```bash
# Move SyncDocumentToTypesense job back
mv app/Jobs/Archived/SyncDocumentToTypesense.php app/Jobs/SyncDocumentToTypesense.php

# Move SyncOpenOverheidDocumentsJob back
mv app/Jobs/Archived/SyncOpenOverheidDocumentsJob.php app/Jobs/SyncOpenOverheidDocumentsJob.php
```

#### Step 2: Update the Namespaces

Edit both job files and change:

```php
namespace App\Jobs\Archived;
```

Back to:

```php
namespace App\Jobs;
```

Also remove the archived comment blocks at the top of each class.

#### Step 3: Update References

1. **Update `routes/console.php`**: Uncomment the job imports and scheduled jobs
2. **Update `app/Console/Archived/SyncOpenOverheidDocuments.php`**: Change `use App\Jobs\Archived\SyncDocumentToTypesense;` back to `use App\Jobs\SyncDocumentToTypesense;`
3. **Update `app/Livewire/Admin/TypesenseSyncStatus.php`**: Uncomment the job dispatch in `triggerSync()` method

#### Step 4: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan queue:restart  # If using queue workers
```

## Manual Execution (Without Re-enabling)

If you need to run these commands manually without re-enabling the scheduled execution:

1. Temporarily move the file back (see Step 1 above for each command)
2. Update the namespace (see Step 2 above for each command)
3. Run the command manually:

```bash
# Open Overheid sync
php artisan open-overheid:sync --recent --days=7

# Typesense sync
php artisan typesense:sync
```

4. Move them back to Archived when done

## Future Plans

These commands are planned to be moved to a separate service/project that will:

- Run independently from the main application
- Handle high-load synchronization tasks
- Communicate with the main application via API or message queue
- Scale independently based on load
- Process both Open Overheid API sync and Typesense indexing

## Dependencies

### SyncOpenOverheidDocuments Dependencies

- `App\Services\OpenOverheid\OpenOverheidSyncService`
- `App\Jobs\SyncDocumentToTypesense`
- Database connection to PostgreSQL
- Open Overheid API access
- Typesense service (for document indexing)

### SyncTypesense Dependencies

- `App\Services\Typesense\TypesenseSyncService`
- Database connection to PostgreSQL
- Typesense service (for document indexing)
- Access to `open_overheid_documents` table

All these dependencies must be available when re-enabling these commands.

## Related Files

### SyncOpenOverheidDocuments

- **Service**: `app/Services/OpenOverheid/OpenOverheidSyncService.php`
- **Job**: `app/Jobs/Archived/SyncOpenOverheidDocumentsJob.php` (archived)
- **Scheduler Config**: `routes/console.php`
- **Config**: `config/open_overheid.php`

### SyncTypesense

- **Service**: `app/Services/Typesense/TypesenseSyncService.php`
- **Job**: `app/Jobs/Archived/SyncDocumentToTypesense.php` (archived)
- **Scheduler Config**: `routes/console.php`
- **Config**: `config/typesense.php` (if exists)
- **Livewire Component**: `app/Livewire/Admin/TypesenseSyncStatus.php` (trigger disabled)

## Notes

- Both commands and jobs work together: `open-overheid:sync` fetches documents from the API and stores them in PostgreSQL, while `typesense:sync` indexes those documents in Typesense for search.
- The Typesense sync was running every minute to keep the search index up-to-date.
- The Open Overheid sync was running daily to fetch new documents from the API.
- The manual trigger button in the Data Management dashboard (`app/Livewire/Admin/TypesenseSyncStatus`) has been disabled.
- All related files (commands and jobs) have been archived to `app/Console/Archived/` and `app/Jobs/Archived/` respectively.
- Consider using queue jobs instead of scheduled commands for better performance and scalability when re-enabling.
