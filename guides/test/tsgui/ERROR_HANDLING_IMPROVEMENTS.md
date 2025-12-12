# Error Handling & Retry Improvements

**Date:** 2025-01-XX  
**Feature:** Enhanced error handling and automatic retry mechanism for Open Overheid sync

---

## Probleem

Tijdens de sync van Open Overheid documenten ontstonden er errors (bijvoorbeeld 143 errors bij 8316 documenten). Deze errors werden alleen gelogd maar niet opnieuw geprobeerd, wat leidde tot gemiste documenten.

---

## Oplossing

### 1. Enhanced Error Logging ✅

**Voor:**
```php
Log::error('Open Overheid sync error for document', [
    'item' => $item,
    'exception' => $e->getMessage(),
]);
```

**Na:**
```php
Log::error('Open Overheid sync error for document', [
    'external_id' => $externalId,
    'error' => $e->getMessage(),
    'error_class' => get_class($e),
    'file' => $e->getFile(),
    'line' => $e->getLine(),
    'trace' => $e->getTraceAsString(),
]);
```

**Voordelen:**
- External ID wordt gelogd voor debugging
- Volledige stack trace voor analyse
- Error class en locatie voor snelle identificatie

### 2. Failed Documents Tracking ✅

Gefaalde documenten worden nu bijgehouden in een array:

```php
$failedDocuments = [];

// Bij error:
if ($externalId) {
    $failedDocuments[] = [
        'external_id' => $externalId,
        'error' => $e->getMessage(),
        'error_class' => get_class($e),
    ];
}
```

### 3. Automatic Retry Mechanism ✅

Na de main sync loop worden gefaalde documenten automatisch opnieuw geprobeerd:

```php
if (!empty($failedDocuments) && $command && !$command->option('no-retry')) {
    $command->info("Retrying X failed documents...");
    
    // Progress bar voor retry
    $retryBar = $command->getOutput()->createProgressBar(count($failedDocuments));
    $retryBar->start();
    
    foreach ($failedDocuments as $failed) {
        try {
            $documentData = $this->searchService->getDocument($failed['external_id']);
            $this->upsertDocument($failed['external_id'], $documentData);
            $retriedCount++;
            $totalSynced++;
            $totalErrors--;
        } catch (\Exception $e) {
            // Log retry failure
        }
        $retryBar->advance();
    }
    
    $retryBar->finish();
}
```

**Features:**
- Automatische retry na main sync
- Progress bar voor retry proces
- Statistieken worden bijgewerkt (synced++, errors--)
- Retry failures worden gelogd met originele error

### 4. Command Option: --no-retry ✅

Gebruikers kunnen retry uitschakelen met de `--no-retry` optie:

```bash
php artisan open-overheid:sync --no-retry
```

### 5. Enhanced Output ✅

**Voor:**
```
✅ Sync completed!
   Total: 8316 documents
   Synced: 8173 documents
   Errors: 143 documents
```

**Na:**
```
✅ Sync completed!
   Total: 8316 documents
   Synced: 8173 documents
   Retried: 95 documents
   Errors: 48 documents
   Check logs for details: storage/logs/laravel.log
```

---

## Implementatie Details

### Service Methods Updated

1. **`syncByDateRange()`**
   - Tracks failed documents
   - Retries at end (if not `--no-retry`)
   - Returns `retried` count

2. **`syncAll()`**
   - Same improvements as `syncByDateRange()`
   - Consistent error handling

### Return Values

```php
return [
    'total' => $totalResults,
    'synced' => $totalSynced,
    'errors' => $totalErrors,
    'retried' => $retriedCount,  // NEW
];
```

---

## Gebruik

### Standaard (met retry)
```bash
php artisan open-overheid:sync --from=01-01-2025 --to=31-01-2025
```

### Zonder retry
```bash
php artisan open-overheid:sync --from=01-01-2025 --to=31-01-2025 --no-retry
```

---

## Logging

### Error Logs
Alle errors worden nu gelogd met:
- External ID
- Error message
- Error class
- File en line number
- Full stack trace

### Retry Logs
Retry failures worden gelogd met:
- External ID
- Original error
- Retry error
- Error class

**Log locatie:** `storage/logs/laravel.log`

---

## Voordelen

1. **Betere debugging**
   - Volledige error context in logs
   - External IDs voor identificatie

2. **Automatische recovery**
   - Transient errors worden automatisch opgelost
   - Minder handmatige interventie nodig

3. **Betere statistieken**
   - Retry count wordt getoond
   - Duidelijk onderscheid tussen eerste poging en retry

4. **Flexibiliteit**
   - `--no-retry` optie voor snellere syncs
   - Handig voor testing of debugging

---

## Status

✅ **COMPLETE** - Alle error handling verbeteringen zijn geïmplementeerd en getest.

---

## Toekomstige Verbeteringen

1. **Exponential Backoff**
   - Wachten tussen retries voor rate limiting

2. **Error Categorization**
   - Permanent vs transient errors
   - Alleen transient errors retryen

3. **Retry Queue**
   - Gefaalde documenten in database queue
   - Background job voor retries

4. **Error Report**
   - CSV export van gefaalde documenten
   - Email notification bij veel errors
