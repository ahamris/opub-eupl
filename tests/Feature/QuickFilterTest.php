<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Document Discovery & Search
 * Feature: Quick Filter
 *
 * User Story: As a user, I want to quickly filter results
 * so that I can find documents faster
 */
test('quick filter appears on search results page', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $response->assertSee('Snel filteren');
});

test('quick filter appears on themes page', function () {
    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertSee('Snel filteren');
    $html = $response->getContent();
    expect($html)->toContain('Type om te filteren op thema...');
});

test('quick filter appears on dossiers page', function () {
    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $response->assertSee('Snel filteren');
    $html = $response->getContent();
    expect($html)->toContain('Type om te filteren op organisatie, documentsoort of informatiecategorie...');
});

test('quick filter on themes page only shows themes', function () {
    OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertViewHas('allFilterOptions');
    $options = $response->viewData('allFilterOptions');

    expect($options)->toHaveKey('thema');
    expect($options['thema'])->toContain('Klimaat');
});

test('quick filter on dossiers page excludes themes', function () {
    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $response->assertViewHas('allFilterOptions');
    $options = $response->viewData('allFilterOptions');

    expect($options)->not->toHaveKey('thema');
    expect($options)->toHaveKey('organisatie');
    expect($options)->toHaveKey('documentsoort');
    expect($options)->toHaveKey('informatiecategorie');
});

test('quick filter provides suggestions', function () {
    OpenOverheidDocument::factory()->create([
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/themas');

    $response->assertStatus(200);
    $html = $response->getContent();
    // Should have JavaScript for quick filter
    expect($html)->toContain('quick-filter');
    expect($html)->toContain('filterQuickOptions');
});
