<?php

use App\Models\User;
use App\Services\Typesense\TypesenseGuiService;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

// ============================================================================
// EPIC 1: Collection Management
// ============================================================================

describe('EPIC 1: Collection Management', function () {
    test('US-1.1: View Collections List - requires authentication', function () {
        get('/tsgui')
            ->assertRedirect('/login');
    });

    test('US-1.1: View Collections List - shows collections for authenticated users', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        // Layout also calls listCollections, so allow multiple calls
        $mockService->shouldReceive('listCollections')
            ->andReturn([
                [
                    'name' => 'open_overheid_documents',
                    'num_documents' => 731,
                    'created_at' => time(),
                ],
            ]);

        $mockService->shouldReceive('getCollectionStats')
            ->once()
            ->andReturn([
                'name' => 'open_overheid_documents',
                'num_documents' => 731,
                'created_at' => time(),
                'fields' => [],
            ]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui')
            ->assertSuccessful()
            ->assertSee('open_overheid_documents')
            ->assertSee('731');
    });

    test('US-1.1: View Collections List - shows empty state when no collections', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        // Layout also calls listCollections
        $mockService->shouldReceive('listCollections')
            ->andReturn([]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui')
            ->assertSuccessful()
            ->assertSee('No collections found');
    });

    test('US-1.2: View Collection Details - requires authentication', function () {
        get('/tsgui/collections/test_collection')
            ->assertRedirect('/login');
    });

    test('US-1.2: View Collection Details - shows collection info', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $mockService->shouldReceive('getCollection')
            ->once()
            ->with('test_collection')
            ->andReturn([
                'name' => 'test_collection',
                'num_documents' => 100,
                'fields' => [
                    ['name' => 'title', 'type' => 'string', 'index' => true],
                ],
            ]);

        $mockService->shouldReceive('getCollectionStats')
            ->once()
            ->with('test_collection')
            ->andReturn([
                'name' => 'test_collection',
                'num_documents' => 100,
                'created_at' => time(),
                'fields' => [
                    ['name' => 'title', 'type' => 'string', 'index' => true],
                ],
            ]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui/collections/test_collection')
            ->assertSuccessful()
            ->assertSee('test_collection')
            ->assertSee('100')
            ->assertSee('title');
    });

    test('US-1.3: Delete Collection - requires authentication', function () {
        delete('/tsgui/collections/test_collection')
            ->assertRedirect('/login');
    });

    test('US-1.3: Delete Collection - deletes successfully', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $mockService->shouldReceive('deleteCollection')
            ->once()
            ->with('test_collection')
            ->andReturn(['name' => 'test_collection']);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->delete('/tsgui/collections/test_collection')
            ->assertRedirect(route('tsgui.index'));
    });
});

// ============================================================================
// EPIC 2: Document Search & Discovery
// ============================================================================

describe('EPIC 2: Document Search & Discovery', function () {
    test('US-2.1: Search Documents - requires authentication', function () {
        get('/tsgui/collections/test_collection/search')
            ->assertRedirect('/login');
    });

    test('US-2.1: Search Documents - performs search', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        // Layout also calls listCollections
        $mockService->shouldReceive('listCollections')
            ->andReturn([
                ['name' => 'test_collection', 'num_documents' => 100],
            ]);

        $mockService->shouldReceive('searchCollection')
            ->once()
            ->andReturn([
                'hits' => [
                    [
                        'document' => [
                            'id' => '1',
                            'title' => 'Test Document',
                            'description' => 'Test description',
                        ],
                    ],
                ],
                'found' => 1,
                'page' => 1,
                'search_time_ms' => 10,
                'facet_counts' => [],
            ]);

        $mockService->shouldReceive('getCollection')
            ->once()
            ->andReturn([
                'name' => 'test_collection',
                'fields' => [],
            ]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        $response = actingAs($this->user)
            ->get('/tsgui/collections/test_collection/search?q=test')
            ->assertSuccessful();

        // Check if search results page loads (may not show exact text due to highlighting)
        $response->assertSee('Search Results')
            ->assertSee('results found');
    });

    test('US-2.1: Search Documents - shows empty state when no results', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        // Layout also calls listCollections
        $mockService->shouldReceive('listCollections')
            ->andReturn([
                ['name' => 'test_collection', 'num_documents' => 100],
            ]);

        $mockService->shouldReceive('searchCollection')
            ->once()
            ->andReturn([
                'hits' => [],
                'found' => 0,
                'page' => 1,
                'search_time_ms' => 5,
                'facet_counts' => [],
            ]);

        $mockService->shouldReceive('getCollection')
            ->once()
            ->andReturn([
                'name' => 'test_collection',
                'fields' => [],
            ]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui/collections/test_collection/search?q=nonexistent')
            ->assertSuccessful()
            ->assertSee('No results found');
    });

    test('US-2.3: View Document - requires authentication', function () {
        get('/tsgui/collections/test_collection/documents/1')
            ->assertRedirect('/login');
    });

    test('US-2.3: View Document - displays document', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        // Layout also calls listCollections
        $mockService->shouldReceive('listCollections')
            ->andReturn([
                ['name' => 'test_collection', 'num_documents' => 100],
            ]);

        $mockService->shouldReceive('getDocument')
            ->once()
            ->with('test_collection', '1')
            ->andReturn([
                'id' => '1',
                'title' => 'Test Document',
                'content' => 'Test content',
            ]);

        $mockService->shouldReceive('getCollection')
            ->once()
            ->andReturn([
                'name' => 'test_collection',
                'fields' => [],
            ]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui/collections/test_collection/documents/1')
            ->assertSuccessful()
            ->assertSee('Test Document')
            ->assertSee('Test content');
    });

    test('US-2.3: View Document - redirects when document not found', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $mockService->shouldReceive('getDocument')
            ->once()
            ->with('test_collection', '999')
            ->andReturn(null);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui/collections/test_collection/documents/999')
            ->assertRedirect();
    });
});

// ============================================================================
// EPIC 3: Document Management
// ============================================================================

describe('EPIC 3: Document Management', function () {
    test('US-3.1: Add Document - requires authentication', function () {
        post('/tsgui/collections/test_collection/documents')
            ->assertRedirect('/login');
    });

    test('US-3.1: Add Document - validates JSON format', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->post('/tsgui/collections/test_collection/documents', [
                'document' => 'invalid json',
            ])
            ->assertSessionHasErrors();
    });

    test('US-3.1: Add Document - adds document successfully', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $mockService->shouldReceive('addDocument')
            ->once()
            ->with('test_collection', ['id' => '1', 'title' => 'Test'])
            ->andReturn(['id' => '1']);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->post('/tsgui/collections/test_collection/documents', [
                'document' => json_encode(['id' => '1', 'title' => 'Test']),
            ])
            ->assertRedirect();
    });

    test('US-2.4: Delete Document - requires authentication', function () {
        delete('/tsgui/collections/test_collection/documents/1')
            ->assertRedirect('/login');
    });

    test('US-2.4: Delete Document - deletes successfully', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $mockService->shouldReceive('deleteDocument')
            ->once()
            ->with('test_collection', '1')
            ->andReturn(['id' => '1']);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->delete('/tsgui/collections/test_collection/documents/1')
            ->assertRedirect();
    });
});

// ============================================================================
// EPIC 4: System Integration
// ============================================================================

describe('EPIC 4: System Integration', function () {
    test('EP-4.1: Typesense Connection - uses config from .env', function () {
        $config = config('open_overheid.typesense');

        expect($config)->toHaveKey('api_key')
            ->and($config)->toHaveKey('host')
            ->and($config)->toHaveKey('port')
            ->and($config)->toHaveKey('protocol');
    });

    test('EP-4.2: Authentication Integration - protects all routes', function () {
        $routes = [
            '/tsgui',
            '/tsgui/collections/test',
            '/tsgui/collections/test/search',
            '/tsgui/collections/test/documents/1',
        ];

        foreach ($routes as $route) {
            get($route)->assertRedirect('/login');
        }
    });
});

// ============================================================================
// EPIC 5: User Experience
// ============================================================================

describe('EPIC 5: User Experience', function () {
    test('EP-5.1: Navigation - sidebar displays collections', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $mockService->shouldReceive('listCollections')
            ->andReturn([
                [
                    'name' => 'test_collection',
                    'num_documents' => 100,
                ],
            ]);

        $mockService->shouldReceive('getCollectionStats')
            ->andReturn([
                'name' => 'test_collection',
                'num_documents' => 100,
                'created_at' => time(),
                'fields' => [],
            ]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui')
            ->assertSuccessful()
            ->assertSee('test_collection');
    });

    test('EP-5.2: Error Handling - shows error messages', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        // Layout also calls listCollections
        $mockService->shouldReceive('listCollections')
            ->andReturn([]);

        $mockService->shouldReceive('getCollection')
            ->once()
            ->andThrow(new \Exception('Collection not found'));

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->get('/tsgui/collections/nonexistent')
            ->assertRedirect()
            ->assertSessionHas('error');
    });
});

// ============================================================================
// Additional Features
// ============================================================================

describe('Additional Features', function () {
    test('Create Collection - requires authentication', function () {
        post('/tsgui/collections')
            ->assertRedirect('/login');
    });

    test('Create Collection - validates input', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->post('/tsgui/collections', [
                'name' => '',
                'schema' => 'invalid',
            ])
            ->assertSessionHasErrors();
    });

    test('Create Collection - creates successfully', function () {
        $mockService = $this->mock(TypesenseGuiService::class);
        $mockService->shouldReceive('createCollection')
            ->once()
            ->andReturn([
                'name' => 'test_collection',
                'num_documents' => 0,
            ]);

        $this->app->instance(TypesenseGuiService::class, $mockService);

        actingAs($this->user)
            ->post('/tsgui/collections', [
                'name' => 'test_collection',
                'schema' => json_encode([
                    'name' => 'test_collection',
                    'fields' => [
                        ['name' => 'id', 'type' => 'string'],
                    ],
                ]),
            ])
            ->assertRedirect();
    });
});
