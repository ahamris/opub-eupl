<?php

namespace App\Services\OpenOverheid;

use App\DataTransferObjects\OpenOverheid\OpenOverheidSearchQuery;
use App\Models\OpenOverheidDocument;
use Illuminate\Support\Facades\Log;

class OpenOverheidLocalSearchService
{
    /**
     * Search documents in local PostgreSQL database.
     */
    public function search(OpenOverheidSearchQuery $query): array
    {
        try {
            $builder = OpenOverheidDocument::query();

            // Full-text search on zoektekst
            if (! empty($query->zoektekst)) {
                // If titles_only is set, only search in title using LIKE for exact column matching
                if (! empty($query->titlesOnly)) {
                    // Use ILIKE for case-insensitive search on title column only
                    $builder->where('title', 'ilike', '%'.$query->zoektekst.'%');
                } else {
                    $builder->whereFullText(['title', 'description', 'content'], $query->zoektekst);
                }
            }

            // Apply filters
            $builder->dateRange($query->publicatiedatumVan, $query->publicatiedatumTot);
            $builder->byDocumentType($query->documentsoort);
            $builder->byCategory($query->informatiecategorie);
            $builder->byTheme($query->thema);
            $builder->byOrganisation($query->organisatie);

            // Apply sorting
            switch ($query->sort ?? 'relevance') {
                case 'publication_date':
                    $builder->orderBy('publication_date', 'desc');
                    break;
                case 'modified_date':
                    $builder->orderBy('updated_at', 'desc');
                    break;
                case 'relevance':
                default:
                    // For relevance, if there's a search term, PostgreSQL full-text search
                    // ranks by relevance automatically. Otherwise, sort by publication_date.
                    if (! empty($query->zoektekst)) {
                        // PostgreSQL full-text search already orders by relevance by default
                        // Add publication_date as secondary sort
                        $builder->orderBy('publication_date', 'desc');
                    } else {
                        $builder->orderBy('publication_date', 'desc');
                    }
                    break;
            }

            $results = $builder->paginate($query->perPage, ['*'], 'page', $query->page);

            // Transform to match API response format
            return [
                'items' => $results->items(),
                'total' => $results->total(),
                'page' => $results->currentPage(),
                'perPage' => $results->perPage(),
                'hasNextPage' => $results->hasMorePages(),
                'hasPreviousPage' => $results->currentPage() > 1,
            ];
        } catch (\Exception $e) {
            Log::error('Open Overheid local search error', [
                'query' => $query,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
