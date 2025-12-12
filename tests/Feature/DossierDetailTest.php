<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Document Viewing & Details
 * Feature: Dossier Detail View
 *
 * User Story: As a user, I want to view dossier details with all members
 * so that I can see all related documents
 */
test('user can view dossier detail page', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc1',
        'title' => 'Main Document',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc2',
                ],
            ],
        ],
    ]);

    $related = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc2',
        'title' => 'Related Document',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc1',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers/oep-doc1');

    $response->assertStatus(200);
    $response->assertViewIs('dossiers.show');
    $response->assertSee('Main Document');
});

test('dossier detail page shows all dossier members', function () {
    $doc1 = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc1',
        'title' => 'Document 1',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc2',
                ],
            ],
        ],
    ]);

    $doc2 = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc2',
        'title' => 'Document 2',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc1',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers/oep-doc1');

    $response->assertStatus(200);
    $response->assertSee('Document 1');
    $response->assertSee('Document 2');
});

test('dossier detail page highlights current document', function () {
    $current = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc1',
        'title' => 'Current Document',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc2',
                ],
            ],
        ],
    ]);

    $related = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc2',
        'title' => 'Related Document',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc1',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers/oep-doc1');

    $response->assertStatus(200);
    $html = $response->getContent();
    // Should have some indication of current document
    expect($html)->toContain('Current Document');
});

test('dossier detail page shows dossier count', function () {
    $doc1 = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc1',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc2',
                ],
            ],
        ],
    ]);

    $doc2 = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc2',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc1',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers/oep-doc1');

    $response->assertStatus(200);
    $response->assertViewHas('dossierCount');
    $dossierCount = $response->viewData('dossierCount');
    expect($dossierCount)->toBeGreaterThanOrEqual(1);
});

test('dossier detail page has breadcrumbs', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc1',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc2',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers/oep-doc1');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('Dossiers');
});

test('dossier detail page has link back to dossiers listing', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc1',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc2',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers/oep-doc1');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('/dossiers');
});

test('dossier detail page shows member document details', function () {
    $doc1 = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc1',
        'title' => 'Document 1',
        'organisation' => 'Test Ministry',
        'publication_date' => now()->subDays(5),
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc2',
                ],
            ],
        ],
    ]);

    $doc2 = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-doc2',
        'title' => 'Document 2',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-doc1',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers/oep-doc1');

    $response->assertStatus(200);
    $response->assertSee('Document 1');
    $response->assertSee('Document 2');
    $response->assertSee('Test Ministry');
});

test('dossier detail page returns 404 for non-existent dossier', function () {
    $response = $this->get('/dossiers/non-existent');

    $response->assertNotFound();
});
