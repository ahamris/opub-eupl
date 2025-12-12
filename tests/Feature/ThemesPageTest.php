<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Navigation & Organization
 * Feature: Themes Page
 *
 * User Story: As a user, I want to browse documents by theme
 * so that I can find documents about specific topics
 */
test('user can view themes page', function () {
    OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertViewIs('themas.index');
    $response->assertSee('Thema\'s');
});

test('themes page only shows documents with themes', function () {
    $withTheme = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'title' => 'Klimaat Document',
    ]);

    $withoutTheme = OpenOverheidDocument::factory()->create([
        'theme' => null,
        'title' => 'No Theme Document',
    ]);

    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertSee('Klimaat Document');
    $response->assertDontSee('No Theme Document');
});

test('themes page excludes unknown themes', function () {
    $knownTheme = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'title' => 'Known Theme',
    ]);

    $unknownTheme = OpenOverheidDocument::factory()->create([
        'theme' => 'Onbekend',
        'title' => 'Unknown Theme',
    ]);

    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertSee('Known Theme');
    $response->assertDontSee('Unknown Theme');
});

test('user can search within themes page', function () {
    $matching = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'title' => 'Klimaat Document',
    ]);

    $nonMatching = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'title' => 'Other Document',
    ]);

    $response = $this->get('/themas?zoeken=klimaat');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Klimaat Document');
});

test('user can filter by theme on themes page', function () {
    $theme1 = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'title' => 'Klimaat Doc',
    ]);

    $theme2 = OpenOverheidDocument::factory()->create([
        'theme' => 'Afval',
        'title' => 'Afval Doc',
    ]);

    $response = $this->get('/themas?thema[]=Klimaat');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Klimaat Doc');
    expect($titles)->not->toContain('Afval Doc');
});

test('user can filter by organization on themes page', function () {
    $org1 = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'organisation' => 'Ministerie A',
        'title' => 'Org A Doc',
    ]);

    $org2 = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'organisation' => 'Ministerie B',
        'title' => 'Org B Doc',
    ]);

    $response = $this->get('/themas?organisatie[]=Ministerie A');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Org A Doc');
    expect($titles)->not->toContain('Org B Doc');
});

test('user can filter by category on themes page', function () {
    $cat1 = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'category' => 'Vergaderstukken Staten-Generaal',
        'title' => 'Category 1 Doc',
    ]);

    $cat2 = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'category' => 'Besluiten',
        'title' => 'Category 2 Doc',
    ]);

    $response = $this->get('/themas?informatiecategorie=Vergaderstukken Staten-Generaal');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Category 1 Doc');
});

test('themes page shows theme badge in results', function () {
    $document = OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
        'title' => 'Test Document',
    ]);

    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertSee('Klimaat');
    $html = $response->getContent();
    expect($html)->toContain('fa-tag');
});

test('themes page has sidebar with filters', function () {
    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertSee('Verfijn zoekopdracht');
    $response->assertSee('Zoekwoorden');
    $response->assertSee('Datum beschikbaar');
});

test('themes page has quick filter for themes only', function () {
    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertSee('Snel filteren');
    $html = $response->getContent();
    expect($html)->toContain('Type om te filteren op thema...');
});

test('themes page results are paginated', function () {
    OpenOverheidDocument::factory()->count(25)->create([
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/themas?per_page=10');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    expect($results['items'])->toHaveCount(10);
});

test('themes page shows correct result count', function () {
    OpenOverheidDocument::factory()->count(15)->create([
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertSee('15');
});
