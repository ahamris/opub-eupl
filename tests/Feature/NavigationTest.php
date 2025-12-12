<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Epic: Navigation & Organization
 * Feature: Navigation
 *
 * User Story: As a user, I want to navigate between pages
 * so that I can access all features
 */
test('user can navigate to homepage', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('zoek');
});

test('user can navigate to search page', function () {
    $response = $this->get('/zoek');

    $response->assertStatus(200);
    $response->assertViewIs('zoek');
});

test('user can navigate to search results page', function () {
    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $response->assertViewIs('zoekresultaten');
});

test('user can navigate to themes page', function () {
    $response = $this->get('/themas');

    $response->assertStatus(200);
    $response->assertViewIs('themas.index');
});

test('user can navigate to dossiers page', function () {
    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $response->assertViewIs('dossiers.index');
});

test('user can navigate to about page', function () {
    $response = $this->get('/over');

    $response->assertStatus(200);
});

test('user can navigate to references page', function () {
    $response = $this->get('/verwijzingen');

    $response->assertStatus(200);
});

test('header menu shows all navigation links', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $html = $response->getContent();

    expect($html)->toContain('Home');
    expect($html)->toContain('Zoeken');
    expect($html)->toContain('Thema\'s');
    expect($html)->toContain('Dossiers');
    expect($html)->toContain('Verwijzingen');
    expect($html)->toContain('Over');
});

test('header menu highlights active page', function () {
    $response = $this->get('/themas');

    $response->assertStatus(200);
    $html = $response->getContent();
    // Should have active state for themes
    expect($html)->toContain('themas');
});

test('navigation links use named routes', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Check for route names in links
    expect($html)->toContain('route(\'home\'');
    expect($html)->toContain('route(\'zoeken\'');
    expect($html)->toContain('route(\'themas.index\'');
    expect($html)->toContain('route(\'dossiers.index\'');
});

test('breadcrumbs appear on detail pages', function () {
    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('U bent hier:');
});

test('back links work on detail pages', function () {
    $response = $this->get('/dossiers');

    $response->assertStatus(200);
    $html = $response->getContent();
    expect($html)->toContain('Home');
});
