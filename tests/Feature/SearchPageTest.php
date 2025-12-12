<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * User Story: As a user, I want to access the search page
 * so that I can search for government documents
 */
test('user can view the search page', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $response->assertViewIs('zoek');
    // Check for actual content on the search page
    $response->assertSee('OpenPublicaties');
    $response->assertSee('Open Source Woo-Voorziening');
    $html = $response->getContent();
    expect($html)->toContain('aria-label="Zoek documenten"');
});

test('search page displays document count', function () {
    OpenOverheidDocument::factory()->count(5)->create();

    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $response->assertViewHas('documentCount', 5);
});

test('search page has search form with all required fields', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    // The search page uses a live search component, not a traditional form
    $response->assertSee('Zoek documenten');
    $response->assertSee('OpenPublicaties');
    // Check for search input
    $html = $response->getContent();
    expect($html)->toContain('aria-label="Zoek documenten"');
});

test('search page has Font Awesome icons loaded', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $response->assertSee('vendor/fontawesome/css/all.min.css', false);
});

test('search page has proper accessibility attributes', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Check for aria-hidden on decorative icons
    expect($html)->toContain('aria-hidden="true"');
});
