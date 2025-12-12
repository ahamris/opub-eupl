<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * User Story: As a developer, I want to access the API
 * so that I can integrate the search functionality programmatically
 */
test('API endpoint returns JSON response', function () {
    OpenOverheidDocument::factory()->count(5)->create();

    $response = $this->getJson('/open-overheid/search?q=test');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/json');

    $data = $response->json();
    expect($data)->toBeArray();
    expect($data)->toHaveKey('items');
    expect($data)->toHaveKey('total');
});

test('API supports pagination', function () {
    // Create documents with searchable content
    OpenOverheidDocument::factory()->count(25)->create([
        'title' => 'Test Document',
        'description' => 'Test description',
    ]);

    $response = $this->getJson('/open-overheid/search?q=test&per_page=10&page=1');

    $response->assertStatus(200);
    $data = $response->json();

    expect($data['items'])->toHaveCount(10);
    expect($data)->toHaveKey('page');
    expect($data)->toHaveKey('perPage');
});

test('API supports filtering', function () {
    $advies = OpenOverheidDocument::factory()->create(['document_type' => 'advies']);
    $agenda = OpenOverheidDocument::factory()->create(['document_type' => 'agenda']);

    $response = $this->getJson('/open-overheid/search?documentsoort=advies');

    $response->assertStatus(200);
    $data = $response->json();

    $titles = collect($data['items'])->pluck('title')->toArray();
    expect($titles)->toContain($advies->title);
    expect($titles)->not->toContain($agenda->title);
});

test('API supports sorting', function () {
    OpenOverheidDocument::factory()->count(5)->create();

    $response = $this->getJson('/open-overheid/search?sort=publication_date');

    $response->assertStatus(200);
    $data = $response->json();

    expect($data)->toHaveKey('items');
});
