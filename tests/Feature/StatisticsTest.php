<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Statistics & Analytics
 * Feature: Statistics Display
 *
 * User Story: As a user, I want to see document statistics
 * so that I can understand the document collection
 */
test('homepage shows total document count', function () {
    OpenOverheidDocument::factory()->count(10)->create();

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewHas('documentCount', 10);
});

test('homepage shows dossier count', function () {
    OpenOverheidDocument::factory()->count(5)->create([
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewHas('statistics');
    $statistics = $response->viewData('statistics');
    expect($statistics)->toHaveKey('dossierCount');
});

test('homepage shows top categories', function () {
    OpenOverheidDocument::factory()->count(3)->create([
        'category' => 'Vergaderstukken Staten-Generaal',
    ]);

    OpenOverheidDocument::factory()->count(2)->create([
        'category' => 'Besluiten',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewHas('statistics');
    $statistics = $response->viewData('statistics');
    expect($statistics)->toHaveKey('topCategories');
});

test('homepage shows top themes', function () {
    OpenOverheidDocument::factory()->count(5)->create([
        'theme' => 'Klimaat',
    ]);

    OpenOverheidDocument::factory()->count(3)->create([
        'theme' => 'Afval',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewHas('statistics');
    $statistics = $response->viewData('statistics');
    expect($statistics)->toHaveKey('topThemes');
});

test('statistics exclude unknown categories', function () {
    OpenOverheidDocument::factory()->create([
        'category' => 'Onbekend',
    ]);

    OpenOverheidDocument::factory()->create([
        'category' => 'Vergaderstukken Staten-Generaal',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $statistics = $response->viewData('statistics');
    $topCategories = $statistics['topCategories'] ?? [];

    $categoryNames = collect($topCategories)->pluck('category')->toArray();
    expect($categoryNames)->not->toContain('Onbekend');
});

test('statistics exclude unknown themes', function () {
    OpenOverheidDocument::factory()->create([
        'theme' => 'Onbekend',
    ]);

    OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $statistics = $response->viewData('statistics');
    $topThemes = $statistics['topThemes'] ?? [];

    $themeNames = collect($topThemes)->pluck('theme')->toArray();
    expect($themeNames)->not->toContain('Onbekend');
});

test('statistics are clickable and link to search', function () {
    OpenOverheidDocument::factory()->create([
        'category' => 'Vergaderstukken Staten-Generaal',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('/zoeken?informatiecategorie=');
});

test('dossier count is clickable and links to dossiers page', function () {
    OpenOverheidDocument::factory()->create([
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('/dossiers');
});

test('theme statistics are clickable and link to search', function () {
    OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('/zoeken?thema[]=');
});

test('category statistics show formatted category names', function () {
    OpenOverheidDocument::factory()->create([
        'category' => 'vergaderstukken Staten-Generaal',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $statistics = $response->viewData('statistics');
    $topCategories = $statistics['topCategories'] ?? [];

    if (! empty($topCategories)) {
        $firstCategory = $topCategories[0];
        expect($firstCategory)->toHaveKey('formatted_category');
    }
});
