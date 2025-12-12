<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Missing Feature Tests
 * Based on guides/missing-features-analysis.md
 *
 * These tests verify which features are MISSING and should fail
 * until the features are implemented
 */

/**
 * Missing Feature 1: Custom Date Range Picker
 * Status: ❌ NOT IMPLEMENTED
 */
test('MISSING: user can select custom date range with date picker', function () {
    // This test should fail until custom date range picker is implemented
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have date input fields with date picker
    // Currently: Only radio buttons exist
    expect($html)->toContain('publicatiedatum_van');
    expect($html)->toContain('publicatiedatum_tot');
    // Note: Controller supports it, but UI doesn't show it
})->skip('Custom date range picker not yet implemented in UI');

/**
 * Missing Feature 2: File Type Filter
 * Status: ❌ NOT IMPLEMENTED
 */
test('MISSING: user can filter by file type', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have "Type bronbestand" filter section
    expect($html)->toContain('Type bronbestand');
    expect($html)->toContain('Word-document');
    expect($html)->toContain('E-mailbericht');
    expect($html)->toContain('PDF');
})->skip('File type filter not yet implemented');

test('MISSING: search results show correct file type icons', function () {
    $document = OpenOverheidDocument::factory()->create([
        'metadata' => [
            'versies' => [
                [
                    'bestanden' => [
                        ['mime-type' => 'application/pdf'],
                    ],
                ],
            ],
        ],
    ]);

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    // Should show PDF icon for PDF, Word icon for Word, etc.
    // Currently: Always shows PDF icon
    $response->assertSee('fa-file-pdf', false);
})->skip('File type detection not yet implemented');

/**
 * Missing Feature 3: Hierarchical/Expandable Filter Categories
 * Status: ❌ NOT IMPLEMENTED
 */
test('MISSING: filters have expandable subcategories', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have expandable filter sections
    // Currently: Flat structure, "Toon meer" doesn't expand subcategories
    expect($html)->toContain('Toon meer');
})->skip('Hierarchical filters not yet implemented');

/**
 * Missing Feature 4: Decision Type Filter
 * Status: ❌ NOT IMPLEMENTED
 */
test('MISSING: user can filter by decision type', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have "Soort besluit" filter
    expect($html)->toContain('Soort besluit');
    expect($html)->toContain('Geen openbaarmaking');
    expect($html)->toContain('Gedeeltelijke openbaarmaking');
})->skip('Decision type filter not yet implemented');

/**
 * Missing Feature 5: Assessment Grounds Filter
 * Status: ❌ NOT IMPLEMENTED (Low Priority)
 */
test('MISSING: user can filter by assessment grounds', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have "Beoordelingsgronden" filter
    expect($html)->toContain('Beoordelingsgronden');
})->skip('Assessment grounds filter not yet implemented - Low Priority');

/**
 * Missing Feature 6: Result Limit Notice
 * Status: ❌ NOT IMPLEMENTED
 */
test('MISSING: search results show limit notice when results exceed limit', function () {
    OpenOverheidDocument::factory()->count(10001)->create();

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should show notice about result limit
    expect($html)->toContain('De eerste');
    expect($html)->toContain('resultaten');
})->skip('Result limit notice not yet implemented');

/**
 * Missing Feature 7: Enhanced Result Display
 * Status: ⚠️ PARTIALLY IMPLEMENTED
 */
test('MISSING: search results show page count', function () {
    $document = OpenOverheidDocument::factory()->create([
        'metadata' => [
            'versies' => [
                [
                    'bestanden' => [
                        ['aantalPaginas' => 25],
                    ],
                ],
            ],
        ],
    ]);

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should show page count
    expect($html)->toContain('pagina');
})->skip('Page count display not yet implemented in search results');

test('MISSING: search results show disclosure status', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should show disclosure status
    expect($html)->toContain('Gedeeltelijke openbaarmaking');
    // OR
    expect($html)->toContain('Reeds openbaar');
})->skip('Disclosure status display not yet implemented');

test('MISSING: search results show document number', function () {
    $document = OpenOverheidDocument::factory()->create([
        'metadata' => [
            'document' => [
                'identifiers' => [
                    ['value' => '665555'],
                ],
            ],
        ],
    ]);

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should show document number
    expect($html)->toContain('Documentnummer');
    expect($html)->toContain('665555');
})->skip('Document number display not yet implemented');

test('MISSING: search results show "Onderdeel van" relationship', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should show "Onderdeel van:" with link to parent
    expect($html)->toContain('Onderdeel van');
})->skip('"Onderdeel van" relationship display not yet implemented');

/**
 * Missing Feature 8: Enhanced Sorting Options
 * Status: ⚠️ PARTIALLY IMPLEMENTED
 */
test('MISSING: sorting has separate "Nieuwste bovenaan" and "Oudste bovenaan" options', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have explicit "Nieuwste bovenaan" and "Oudste bovenaan"
    expect($html)->toContain('Nieuwste bovenaan');
    expect($html)->toContain('Oudste bovenaan');
})->skip('Enhanced sorting labels not yet implemented');

/**
 * Missing Feature 9: Collapsible Filter Sections
 * Status: ❌ NOT IMPLEMENTED
 */
test('MISSING: filter sections can be collapsed and expanded', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have collapse/expand functionality
    // Check for JavaScript or data attributes
    expect($html)->toContain('collapse');
    // OR check for aria-expanded attributes
})->skip('Collapsible filter sections not yet implemented');

/**
 * Missing Feature 10: "Ga naar de zoekresultaten" Links
 * Status: ❌ NOT IMPLEMENTED (Low Priority)
 */
test('MISSING: filter sections have "Ga naar de zoekresultaten" links', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Should have quick navigation links
    expect($html)->toContain('Ga naar de zoekresultaten');
})->skip('Quick navigation links not yet implemented - Low Priority');
