# Typesense Facets Status Check

## Current Implementation

The `/zoeken` page is configured to use **Typesense facets** for filter counts when Typesense is enabled and working.

## How It Works

1. **Typesense Search** → Returns results + `facet_counts`
2. **Facet Conversion** → Converts Typesense facets to filter counts format
3. **Date Counts** → Calculated separately using lightweight SQL queries (3 queries)
4. **Fallback** → If facets missing, uses FilterCountService (SQL GROUP BY queries)

## Check if Facets Are Working

### Method 1: Check Logs
Look for these log messages:
- ✅ `"Typesense facets received"` → Facets are working!
- ⚠️ `"Typesense search succeeded but no facet_counts returned"` → Facets not working, using SQL fallback
- ❌ `"Typesense search failed"` → Typesense not available, using SQL

### Method 2: Check Browser Network Tab
1. Open browser DevTools → Network tab
2. Visit `/zoeken`
3. Look for the request
4. Check response - should include `facet_counts` in the data

### Method 3: Test Typesense Directly
```bash
# Check if collection exists and has documents
php artisan tinker
>>> $service = app(\App\Services\Typesense\TypesenseGuiService::class);
>>> $collections = $service->listCollections();
>>> print_r($collections);

# Test search with facets
>>> $search = app(\App\Services\Typesense\TypesenseSearchService::class);
>>> $results = $search->search('test', ['facet_by' => 'document_type,theme,organisation,category']);
>>> print_r($results['facet_counts']);
```

## Expected Facet Format

Typesense should return:
```json
{
  "facet_counts": [
    {
      "field_name": "document_type",
      "counts": [
        {"value": "Kamerstuk", "count": 41872},
        {"value": "Besluit", "count": 476}
      ]
    },
    {
      "field_name": "theme",
      "counts": [
        {"value": "ruimte en infrastructuur", "count": 14975}
      ]
    }
  ]
}
```

## If Facets Are Not Working

### Possible Causes:
1. **Collection doesn't exist** → Run `php artisan typesense:sync`
2. **Collection has no documents** → Run `php artisan typesense:sync`
3. **Facets not configured** → Check collection schema (should have `'facet' => true`)
4. **Typesense connection issue** → Check Typesense server is running

### Fix:
```bash
# 1. Sync documents to Typesense
php artisan typesense:sync

# 2. Verify collection exists
# Visit /tsgui in browser (if authenticated)
# Or check Typesense directly

# 3. Clear cache
php artisan cache:clear
```

## Performance Comparison

| Method | Filter Count Queries | Speed |
|--------|---------------------|-------|
| **Typesense Facets** | 0 (from search response) | < 100ms |
| **SQL GROUP BY** | 4-5 queries | 1-3 seconds |
| **SQL Fallback** | 4-5 queries | 1-3 seconds |

## Current Status

Based on the code:
- ✅ **Typesense facets are configured** in the search
- ✅ **Fallback to SQL** if facets missing
- ✅ **Date counts** use lightweight SQL (3 queries)
- ⚠️ **Needs verification** if facets are actually being returned

## Next Steps

1. **Check logs** for "Typesense facets received" message
2. **Verify Typesense collection** exists and has documents
3. **Test search** and check if filter counts appear
4. **Monitor performance** - should be < 1 second if using facets


