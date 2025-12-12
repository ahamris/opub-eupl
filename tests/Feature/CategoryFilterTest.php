<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Document Discovery & Search
 * Feature: Category Filtering
 *
 * User Story: As a user, I want to filter by information category
 * so that I can find documents of specific types according to Woo
 */
test('user can filter by category', function () {
    $cat1 = OpenOverheidDocument::factory()->create([
        'category' => 'Vergaderstukken Staten-Generaal',
        'title' => 'Category 1 Doc',
    ]);

    $cat2 = OpenOverheidDocument::factory()->create([
        'category' => 'Besluiten',
        'title' => 'Category 2 Doc',
    ]);

    $response = $this->get('/zoeken?informatiecategorie=Vergaderstukken Staten-Generaal');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Category 1 Doc');
    expect($titles)->not->toContain('Category 2 Doc');
});

test('category filter is case insensitive', function () {
    $document = OpenOverheidDocument::factory()->create([
        'category' => 'vergaderstukken Staten-Generaal',
        'title' => 'Lowercase Category',
    ]);

    $response = $this->get('/zoeken?informatiecategorie=Vergaderstukken Staten-Generaal');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Lowercase Category');
});

test('category filter normalizes category names', function () {
    $document = OpenOverheidDocument::factory()->create([
        'category' => 'vergaderstukken Staten-Generaal',
        'title' => 'Normalized Category',
    ]);

    $response = $this->get('/zoeken?informatiecategorie=vergaderstukken Staten-Generaal');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Normalized Category');
});

test('category filter works with formatted category names', function () {
    $document = OpenOverheidDocument::factory()->create([
        'category' => 'vergaderstukken Staten-Generaal',
        'title' => 'Formatted Category',
    ]);

    $response = $this->get('/zoeken?informatiecategorie=vergaderstukken%20Staten-Generaal');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Formatted Category');
});

test('category filter excludes unknown categories', function () {
    $known = OpenOverheidDocument::factory()->create([
        'category' => 'Vergaderstukken Staten-Generaal',
        'title' => 'Known Category',
    ]);

    $unknown = OpenOverheidDocument::factory()->create([
        'category' => 'Onbekend',
        'title' => 'Unknown Category',
    ]);

    $response = $this->get('/zoeken?informatiecategorie=Vergaderstukken Staten-Generaal');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Known Category');
    expect($titles)->not->toContain('Unknown Category');
});

test('category filter shows formatted category in results', function () {
    $document = OpenOverheidDocument::factory()->create([
        'category' => 'vergaderstukken Staten-Generaal',
        'title' => 'Test Document',
    ]);

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();
    // Should show formatted category
    expect($html)->toContain('Vergaderstukken Staten-Generaal');
});

test('category filter can be combined with other filters', function () {
    $matching = OpenOverheidDocument::factory()->create([
        'category' => 'Vergaderstukken Staten-Generaal',
        'organisation' => 'Ministerie A',
        'title' => 'Matching Doc',
    ]);

    $nonMatching = OpenOverheidDocument::factory()->create([
        'category' => 'Vergaderstukken Staten-Generaal',
        'organisation' => 'Ministerie B',
        'title' => 'Non Matching Doc',
    ]);

    $response = $this->get('/zoeken?informatiecategorie=Vergaderstukken Staten-Generaal&organisatie[]=Ministerie A');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Matching Doc');
    expect($titles)->not->toContain('Non Matching Doc');
});
