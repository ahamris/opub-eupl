<?php

use App\Models\User;
use App\Services\Typesense\TypesenseGuiService;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('requires authentication to access tsgui index', function () {
    get('/tsgui')
        ->assertRedirect('/login');
});

it('allows authenticated users to access tsgui index', function () {
    actingAs($this->user)
        ->get('/tsgui')
        ->assertSuccessful();
});

it('displays collections list for authenticated users', function () {
    // Mock the service to avoid actual Typesense connection in tests
    $mockService = $this->mock(TypesenseGuiService::class);
    $mockService->shouldReceive('listCollections')
        ->once()
        ->andReturn([
            [
                'name' => 'test_collection',
                'num_documents' => 100,
                'created_at' => time(),
            ],
        ]);

    $mockService->shouldReceive('getCollectionStats')
        ->once()
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
        ->assertSee('test_collection')
        ->assertSee('100');
});

it('requires authentication to view collection details', function () {
    get('/tsgui/collections/test_collection')
        ->assertRedirect('/login');
});

it('allows authenticated users to view collection details', function () {
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
        ->assertSee('100');
});

it('requires authentication to search collection', function () {
    get('/tsgui/collections/test_collection/search')
        ->assertRedirect('/login');
});

it('allows authenticated users to search collection', function () {
    $mockService = $this->mock(TypesenseGuiService::class);
    $mockService->shouldReceive('searchCollection')
        ->once()
        ->andReturn([
            'hits' => [
                [
                    'document' => [
                        'id' => '1',
                        'title' => 'Test Document',
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

    actingAs($this->user)
        ->get('/tsgui/collections/test_collection/search?q=test')
        ->assertSuccessful()
        ->assertSee('Test Document');
});

it('requires authentication to view document', function () {
    get('/tsgui/collections/test_collection/documents/1')
        ->assertRedirect('/login');
});

it('allows authenticated users to view document', function () {
    $mockService = $this->mock(TypesenseGuiService::class);
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
        ->assertSee('Test Document');
});

it('requires authentication to delete document', function () {
    delete('/tsgui/collections/test_collection/documents/1')
        ->assertRedirect('/login');
});

it('allows authenticated users to delete document', function () {
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

it('requires authentication to delete collection', function () {
    delete('/tsgui/collections/test_collection')
        ->assertRedirect('/login');
});

it('allows authenticated users to delete collection', function () {
    $mockService = $this->mock(TypesenseGuiService::class);
    $mockService->shouldReceive('deleteCollection')
        ->once()
        ->with('test_collection')
        ->andReturn(['name' => 'test_collection']);

    $this->app->instance(TypesenseGuiService::class, $mockService);

    actingAs($this->user)
        ->delete('/tsgui/collections/test_collection')
        ->assertRedirect();
});

it('validates json format when adding document', function () {
    $mockService = $this->mock(TypesenseGuiService::class);
    $this->app->instance(TypesenseGuiService::class, $mockService);

    actingAs($this->user)
        ->post('/tsgui/collections/test_collection/documents', [
            'document' => 'invalid json',
        ])
        ->assertSessionHasErrors();
});

it('allows authenticated users to add document', function () {
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
