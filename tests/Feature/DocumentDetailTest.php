<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * User Story: As a user, I want to view document details
 * so that I can see all information about a document
 */
test('user can view document detail page', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'title' => 'Test Document',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertViewIs('detail');
    $response->assertSee('Test Document');
});

test('document detail page shows all metadata', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'title' => 'Test Document',
        'description' => 'Test Description',
        'organisation' => 'Test Ministry',
        'document_type' => 'advies',
        'category' => 'test category',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertSee('Test Document');
    $response->assertSee('Test Description');
    $response->assertSee('Test Ministry');
    $response->assertSee('advies');
});

test('document detail page shows PDF icon', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertSee('fa-file-pdf', false);
    $response->assertSee('PDF');
});

test('document detail page shows link to open.overheid.nl', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertSee('https://open.overheid.nl/details/oep-test123', false);
    $response->assertSee('Bekijk op open.overheid.nl');
});

test('document detail page shows organization as clickable filter', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'organisation' => 'Test Ministry',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Check for URL-encoded version (spaces become + or %20)
    expect($html)->toContain('/zoeken?organisatie[]=Test');
    expect($html)->toContain('Ministry');
    expect($html)->toContain('fa-building', false);
    expect($html)->toContain('fa-filter', false);
});

/**
 * User Story: As a user, I want to toggle between metadata and JSON view
 * so that I can see different representations of the document
 */
test('user can toggle to JSON view', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertSee('JSON');
    $response->assertSee('Metadata');
});

test('user can toggle to metadata view', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertSee('Kenmerken');
});

/**
 * User Story: As a user, I want to expand/collapse characteristics
 * so that I can see more or less information
 */
test('document detail page has show more characteristics button', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertSee('Toon alle kenmerken');
});

test('document detail page has show less characteristics button', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Button should exist but be hidden initially
    expect($html)->toContain('Toon minder kenmerken');
});

/**
 * User Story: As a user, I want to export document as JSON
 * so that I can use the data programmatically
 */
test('user can export document as JSON', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123?format=json');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/json');

    $data = json_decode($response->getContent(), true);
    expect($data)->toBeArray();
    expect($data)->toHaveKey('title');
});

/**
 * User Story: As a user, I want to export document as XML
 * so that I can use the data in XML format
 */
test('user can export document as XML', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123?format=xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

    $content = $response->getContent();
    expect($content)->toStartWith('<?xml');
});

test('document detail page has back to search results link', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertSee('Terug naar zoekresultaten');
    $response->assertSee('fa-arrow-left', false);
});
