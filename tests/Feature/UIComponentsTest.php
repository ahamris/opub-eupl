<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * User Story: As a user, I want a modern, accessible UI
 * so that I can use the platform effectively
 */
test('all pages load Font Awesome CSS', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $response->assertSee('vendor/fontawesome/css/all.min.css', false);
});

test('all pages have proper accessibility attributes', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Check for proper semantic HTML
    expect($html)->toContain('<header');
    expect($html)->toContain('<main');
    expect($html)->toContain('<footer');

    // Check for ARIA attributes on icons
    expect($html)->toContain('aria-hidden="true"');
});

test('checkboxes are properly styled and accessible', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Checkboxes should have proper classes
    expect($html)->toContain('w-4 h-4');
    expect($html)->toContain('focus:ring-2');
    expect($html)->toContain('checked:bg-primary');
});

test('radio buttons are properly styled and accessible', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Radio buttons should have proper classes
    expect($html)->toContain('type="radio"');
    expect($html)->toContain('w-4 h-4');
    expect($html)->toContain('focus:ring-2');
});

test('buttons have proper minimum touch target size', function () {
    // Check search results page which has buttons with proper sizing
    \App\Models\OpenOverheidDocument::factory()->create([
        'title' => 'Test Document',
    ]);

    $response = $this->get('/zoeken?zoeken=test');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Buttons should have min-h-[44px] or min-h-[48px] for accessibility
    // Check for either class in the HTML
    $hasMinHeight = str_contains($html, 'min-h-[44px]') || str_contains($html, 'min-h-[48px]');
    expect($hasMinHeight)->toBeTrue();
});

test('organization filter buttons are styled as ribbons', function () {
    // Create a document with organization to test the ribbon button
    \App\Models\OpenOverheidDocument::factory()->create([
        'organisation' => 'Test Ministry',
        'title' => 'Test Document',
    ]);

    $response = $this->get('/zoeken?zoeken=test');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Organization buttons should have ribbon styling
    expect($html)->toContain('rounded-full');
    expect($html)->toContain('bg-primary/10');
    expect($html)->toContain('fa-building', false);
    expect($html)->toContain('fa-filter', false);
});

test('card buttons are properly sized', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Card buttons (10, 20, 50) should have proper height
    expect($html)->toContain('min-h-[48px]');
    expect($html)->toContain('px-4 py-2.5');
});

test('PDF badges are properly styled', function () {
    // Create a document with PDF metadata
    \App\Models\OpenOverheidDocument::factory()->create([
        'title' => 'Test PDF Document',
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

    $response = $this->get('/zoeken?zoeken=test');

    $response->assertStatus(200);
    $html = $response->getContent();

    // PDF badges should have proper styling
    // In search results, PDF icon uses text-red-600 (not bg-red-50)
    expect($html)->toContain('text-red-600');
    expect($html)->toContain('fa-file-pdf', false);
});

test('external links have proper security attributes', function () {
    // Create a document with external link
    \App\Models\OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
        'title' => 'Test Document',
    ]);

    $response = $this->get('/zoeken?zoeken=test');

    $response->assertStatus(200);
    $html = $response->getContent();

    // External links should have rel="noopener noreferrer"
    expect($html)->toContain('rel="noopener noreferrer"');
    expect($html)->toContain('target="_blank"');
});

test('all interactive elements have focus states', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Interactive elements should have focus:outline classes
    expect($html)->toContain('focus:outline-2');
    expect($html)->toContain('focus:outline-primary');
});
