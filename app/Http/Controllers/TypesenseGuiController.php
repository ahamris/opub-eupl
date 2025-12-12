<?php

namespace App\Http\Controllers;

use App\Services\Typesense\TypesenseGuiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TypesenseGuiController extends Controller
{
    public function __construct(
        protected TypesenseGuiService $service
    ) {}

    /**
     * List all collections
     */
    public function index(): View
    {
        try {
            $collections = $this->service->listCollections();

            // Get stats for each collection
            $collectionsWithStats = [];
            foreach ($collections as $collection) {
                try {
                    $stats = $this->service->getCollectionStats($collection['name']);
                    $collectionsWithStats[] = $stats;
                } catch (\Exception $e) {
                    // If we can't get stats, just use the basic collection info
                    $collectionsWithStats[] = $collection;
                }
            }

            return view('tsgui.index', [
                'collections' => $collectionsWithStats,
            ]);
        } catch (\Exception $e) {
            return view('tsgui.index', [
                'collections' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show collection detail with search interface
     */
    public function show(string $collection): View|RedirectResponse
    {
        try {
            $collectionData = $this->service->getCollection($collection);
            $stats = $this->service->getCollectionStats($collection);

            return view('tsgui.collection', [
                'collection' => $collectionData,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('tsgui.index')
                ->with('error', "Collection '{$collection}' not found: {$e->getMessage()}");
        }
    }

    /**
     * Search documents in a collection
     */
    public function search(Request $request, string $collection): View|RedirectResponse
    {
        try {
            $query = $request->get('q', '*');
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 20);
            $filterBy = $request->get('filter_by');
            $sortBy = $request->get('sort_by');
            $facetBy = $request->get('facet_by');

            $params = [
                'q' => $query,
                'page' => $page,
                'per_page' => $perPage,
            ];

            if ($filterBy) {
                $params['filter_by'] = $filterBy;
            }

            if ($sortBy) {
                $params['sort_by'] = $sortBy;
            }

            if ($facetBy) {
                $params['facet_by'] = $facetBy;
            }

            $results = $this->service->searchCollection($collection, $params);
            $collectionData = $this->service->getCollection($collection);

            return view('tsgui.search', [
                'collection' => $collectionData,
                'results' => $results,
                'query' => $query,
                'page' => $page,
                'perPage' => $perPage,
                'filterBy' => $filterBy,
                'sortBy' => $sortBy,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('tsgui.collection', ['collection' => $collection])
                ->with('error', "Search failed: {$e->getMessage()}");
        }
    }

    /**
     * View a single document
     */
    public function document(string $collection, string $id): View|RedirectResponse
    {
        try {
            $document = $this->service->getDocument($collection, $id);

            if (! $document) {
                return redirect()->route('tsgui.collection', ['collection' => $collection])
                    ->with('error', "Document '{$id}' not found");
            }

            $collectionData = $this->service->getCollection($collection);

            return view('tsgui.document', [
                'collection' => $collectionData,
                'document' => $document,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('tsgui.collection', ['collection' => $collection])
                ->with('error', "Error loading document: {$e->getMessage()}");
        }
    }

    /**
     * Store a new document
     */
    public function storeDocument(Request $request, string $collection): RedirectResponse
    {
        $request->validate([
            'document' => ['required', 'json'],
        ]);

        try {
            $documentData = json_decode($request->input('document'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()
                    ->with('error', 'Invalid JSON format');
            }

            $this->service->addDocument($collection, $documentData);

            return redirect()->route('tsgui.collection', ['collection' => $collection])
                ->with('success', 'Document added successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Failed to add document: {$e->getMessage()}");
        }
    }

    /**
     * Delete a document
     */
    public function destroyDocument(string $collection, string $id): RedirectResponse
    {
        try {
            $this->service->deleteDocument($collection, $id);

            return redirect()->route('tsgui.collection', ['collection' => $collection])
                ->with('success', 'Document deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Failed to delete document: {$e->getMessage()}");
        }
    }

    /**
     * Delete a collection
     */
    public function destroyCollection(string $collection): RedirectResponse
    {
        try {
            $this->service->deleteCollection($collection);

            return redirect()->route('tsgui.index')
                ->with('success', "Collection '{$collection}' deleted successfully");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Failed to delete collection: {$e->getMessage()}");
        }
    }

    /**
     * Create a new collection
     */
    public function createCollection(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-z0-9_]+$/', 'max:255'],
            'schema' => ['required', 'json'],
        ]);

        try {
            $schema = json_decode($request->input('schema'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()
                    ->with('error', 'Invalid JSON schema format');
            }

            // Ensure name matches
            $schema['name'] = $request->input('name');

            // Validate required fields
            if (! isset($schema['fields']) || ! is_array($schema['fields'])) {
                return redirect()->back()
                    ->with('error', 'Schema must include a fields array');
            }

            $this->service->createCollection($schema);

            return redirect()->route('tsgui.index')
                ->with('success', "Collection '{$schema['name']}' created successfully");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Failed to create collection: {$e->getMessage()}");
        }
    }
}
