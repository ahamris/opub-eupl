<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Navigation & Organization
 * Feature: Dossiers Page
 *
 * User Story: As a user, I want to browse dossiers
 * so that I can find related government documents
 */
test('user can view dossiers page', function () {
    $document = OpenOverheidDocument::factory()->create([
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $response->assertViewIs('dossiers.index');
    $response->assertSee('Dossiers');
});

test('dossiers page only shows documents in dossiers', function () {
    $inDossier = OpenOverheidDocument::factory()->create([
        'title' => 'In Dossier',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $notInDossier = OpenOverheidDocument::factory()->create([
        'title' => 'Not In Dossier',
        'metadata' => [],
    ]);

    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $response->assertSee('In Dossier');
    $response->assertDontSee('Not In Dossier');
});

test('dossiers page shows dossier member count', function () {
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

    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('gerelateerd');
});

test('user can search within dossiers page', function () {
    $matching = OpenOverheidDocument::factory()->create([
        'title' => 'Klimaat Dossier',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $nonMatching = OpenOverheidDocument::factory()->create([
        'title' => 'Other Dossier',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test456',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers?zoeken=klimaat');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Klimaat Dossier');
    expect($titles)->not->toContain('Other Dossier');
});

test('user can filter by organization on dossiers page', function () {
    $org1 = OpenOverheidDocument::factory()->create([
        'organisation' => 'Ministerie A',
        'title' => 'Org A Dossier',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $org2 = OpenOverheidDocument::factory()->create([
        'organisation' => 'Ministerie B',
        'title' => 'Org B Dossier',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test456',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers?organisatie[]=Ministerie A');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    $titles = collect($results['items'])->pluck('title')->toArray();

    expect($titles)->toContain('Org A Dossier');
    expect($titles)->not->toContain('Org B Dossier');
});

test('dossiers page does not show theme filter', function () {
    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $html = $response->getContent();
    // Should not have theme filter section in sidebar
    expect($html)->not->toContain('<!-- Theme Filter -->');
});

test('dossiers page has quick filter without themes', function () {
    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('Type om te filteren op organisatie, documentsoort of informatiecategorie...');
});

test('dossiers page shows dossier icon', function () {
    $document = OpenOverheidDocument::factory()->create([
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('fa-folder-open');
});

test('dossiers page has links to dossier detail and document detail', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'metadata' => [
            'documentrelaties' => [
                [
                    'role' => 'identiteitsgroep',
                    'relation' => 'https://open.overheid.nl/documenten/oep-test123',
                ],
            ],
        ],
    ]);

    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('/dossiers/oep-test123');
    expect($html)->toContain('/open-overheid/documents/oep-test123');
    expect($html)->toContain('Bekijk dossier');
    expect($html)->toContain('Bekijk document');
});

test('dossiers page results are paginated', function () {
    for ($i = 0; $i < 25; $i++) {
        OpenOverheidDocument::factory()->create([
            'metadata' => [
                'documentrelaties' => [
                    [
                        'role' => 'identiteitsgroep',
                        'relation' => 'https://open.overheid.nl/documenten/oep-test'.$i,
                    ],
                ],
            ],
        ]);
    }

    $response = $this->get('/dossiers?per_page=10');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    expect($results['items'])->toHaveCount(10);
});
