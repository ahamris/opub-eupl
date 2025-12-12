<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Document Viewing & Details
 * Feature: Export Functionality
 *
 * User Story: As a user, I want to export documents
 * so that I can use the data programmatically
 */
test('user can export document as JSON', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'title' => 'Test Document',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123?format=json');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/json');

    $data = json_decode($response->getContent(), true);
    expect($data)->toBeArray();
    expect($data)->toHaveKey('title');
    expect($data['title'])->toBe('Test Document');
});

test('user can export document as XML', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'title' => 'Test Document',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123?format=xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

    $content = $response->getContent();
    expect($content)->toStartWith('<?xml');
    expect($content)->toContain('Test Document');
});

test('export JSON includes all document fields', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'title' => 'Test Document',
        'description' => 'Test Description',
        'organisation' => 'Test Ministry',
        'theme' => 'Klimaat',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123?format=json');

    $response->assertStatus(200);
    $data = json_decode($response->getContent(), true);

    expect($data)->toHaveKey('title');
    expect($data)->toHaveKey('description');
    expect($data)->toHaveKey('organisation');
    expect($data)->toHaveKey('theme');
});

test('export XML includes all document fields', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'title' => 'Test Document',
        'description' => 'Test Description',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123?format=xml');

    $response->assertStatus(200);
    $content = $response->getContent();

    expect($content)->toContain('Test Document');
    expect($content)->toContain('Test Description');
});

test('export returns 404 for non-existent document', function () {
    $response = $this->get('/open-overheid/documents/non-existent?format=json');

    $response->assertNotFound();
});

test('export without format parameter shows HTML view', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/open-overheid/documents/oep-test123');

    $response->assertStatus(200);
    $response->assertViewIs('detail');
});
