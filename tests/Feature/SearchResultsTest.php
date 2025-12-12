<?php

use App\Models\OpenOverheidDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

/**
 * User Story: As a user, I want to search for documents
 * so that I can find relevant government information
 */
test('user can perform a basic text search', function () {
    OpenOverheidDocument::factory()->create([
        'title' => 'Klimaatbeleid 2023',
        'description' => 'Document over klimaatbeleid',
    ]);

    $response = $this->get('/zoeken?zoeken=klimaatbeleid');

    $response->assertStatus(200);
    $response->assertViewIs('zoekresultaten');
    $response->assertSee('Klimaatbeleid 2023');
});

test('search returns empty results when no matches found', function () {
    OpenOverheidDocument::factory()->create([
        'title' => 'Other Document',
    ]);

    $response = $this->get('/zoeken?zoeken=nonexistent');

    $response->assertStatus(200);
    $response->assertSee('Geen resultaten gevonden');
});

test('search results are paginated', function () {
    OpenOverheidDocument::factory()->count(25)->create([
        'title' => 'Test Document',
    ]);

    $response = $this->get('/zoeken?zoeken=test&per_page=10');

    $response->assertStatus(200);
    $response->assertViewHas('results');

    $results = $response->viewData('results');
    expect($results['items'])->toHaveCount(10);
});

/**
 * User Story: As a user, I want to filter by date
 * so that I can find documents from specific time periods
 */
test('user can filter by predefined date periods', function () {
    $recent = OpenOverheidDocument::factory()->create([
        'publication_date' => now()->subDays(3),
    ]);

    $old = OpenOverheidDocument::factory()->create([
        'publication_date' => now()->subYear(2),
    ]);

    $response = $this->get('/zoeken?beschikbaarSinds=week');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    // Should only show recent document
    $titles = collect($results['items'])->pluck('title')->toArray();
    expect($titles)->toContain($recent->title);
    expect($titles)->not->toContain($old->title);
});

test('user can filter by custom date range', function () {
    $inRange = OpenOverheidDocument::factory()->create([
        'publication_date' => Carbon::parse('2023-06-15'),
    ]);

    $outOfRange = OpenOverheidDocument::factory()->create([
        'publication_date' => Carbon::parse('2024-01-15'),
    ]);

    $response = $this->get('/zoeken?publicatiedatum_van=01-06-2023&publicatiedatum_tot=30-06-2023');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    $titles = collect($results['items'])->pluck('title')->toArray();
    expect($titles)->toContain($inRange->title);
    expect($titles)->not->toContain($outOfRange->title);
});

/**
 * User Story: As a user, I want to filter by document type
 * so that I can find specific types of documents
 */
test('user can filter by document type', function () {
    $advies = OpenOverheidDocument::factory()->create([
        'document_type' => 'advies',
    ]);

    $agenda = OpenOverheidDocument::factory()->create([
        'document_type' => 'agenda',
    ]);

    $response = $this->get('/zoeken?documentsoort[]=advies');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    $titles = collect($results['items'])->pluck('title')->toArray();
    expect($titles)->toContain($advies->title);
    expect($titles)->not->toContain($agenda->title);
});

test('user can filter by multiple document types', function () {
    $advies = OpenOverheidDocument::factory()->create(['document_type' => 'advies']);
    $agenda = OpenOverheidDocument::factory()->create(['document_type' => 'agenda']);
    $other = OpenOverheidDocument::factory()->create(['document_type' => 'other']);

    $response = $this->get('/zoeken?documentsoort[]=advies&documentsoort[]=agenda');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    $titles = collect($results['items'])->pluck('title')->toArray();
    expect($titles)->toContain($advies->title);
    expect($titles)->toContain($agenda->title);
    expect($titles)->not->toContain($other->title);
});

/**
 * User Story: As a user, I want to filter by theme
 * so that I can find documents about specific topics
 */
test('user can filter by theme', function () {
    $afval = OpenOverheidDocument::factory()->create([
        'theme' => 'afval',
    ]);

    $other = OpenOverheidDocument::factory()->create([
        'theme' => 'other',
    ]);

    $response = $this->get('/zoeken?thema[]=afval');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    $titles = collect($results['items'])->pluck('title')->toArray();
    expect($titles)->toContain($afval->title);
    expect($titles)->not->toContain($other->title);
});

/**
 * User Story: As a user, I want to filter by organization
 * so that I can find documents from specific ministries
 */
test('user can filter by organization', function () {
    $org1 = OpenOverheidDocument::factory()->create([
        'organisation' => 'ministerie van Justitie en Veiligheid',
    ]);

    $org2 = OpenOverheidDocument::factory()->create([
        'organisation' => 'ministerie van Volksgezondheid',
    ]);

    $response = $this->get('/zoeken?organisatie[]=ministerie van Justitie en Veiligheid');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    $titles = collect($results['items'])->pluck('title')->toArray();
    expect($titles)->toContain($org1->title);
    expect($titles)->not->toContain($org2->title);
});

/**
 * User Story: As a user, I want to sort results
 * so that I can view documents in my preferred order
 */
test('user can sort by relevance', function () {
    OpenOverheidDocument::factory()->count(5)->create();

    $response = $this->get('/zoeken?sort=relevance');

    $response->assertStatus(200);
    $response->assertViewHas('query');

    $query = $response->viewData('query');
    expect($query->sort)->toBe('relevance');
});

test('user can sort by publication date', function () {
    OpenOverheidDocument::factory()->count(5)->create();

    $response = $this->get('/zoeken?sort=publication_date');

    $response->assertStatus(200);
    $response->assertViewHas('query');

    $query = $response->viewData('query');
    expect($query->sort)->toBe('publication_date');
});

test('user can sort by modified date', function () {
    OpenOverheidDocument::factory()->count(5)->create();

    $response = $this->get('/zoeken?sort=modified_date');

    $response->assertStatus(200);
    $response->assertViewHas('query');

    $query = $response->viewData('query');
    expect($query->sort)->toBe('modified_date');
});

/**
 * User Story: As a user, I want to change results per page
 * so that I can control how many results I see
 */
test('user can change results per page', function () {
    OpenOverheidDocument::factory()->count(25)->create();

    $response = $this->get('/zoeken?per_page=10');

    $response->assertStatus(200);
    $results = $response->viewData('results');
    expect($results['items'])->toHaveCount(10);

    $response = $this->get('/zoeken?per_page=50');
    $results = $response->viewData('results');
    expect($results['items'])->toHaveCount(25); // Only 25 documents exist
});

/**
 * User Story: As a user, I want to see filter counts
 * so that I know how many documents match each filter
 */
test('search results page shows dynamic filter counts', function () {
    OpenOverheidDocument::factory()->create([
        'publication_date' => now()->subDays(3),
    ]);

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $response->assertViewHas('filterCounts');

    $filterCounts = $response->viewData('filterCounts');
    expect($filterCounts)->toBeArray();
    expect($filterCounts)->toHaveKey('week');
    expect($filterCounts)->toHaveKey('maand');
    expect($filterCounts)->toHaveKey('jaar');
});

/**
 * User Story: As a user, I want to see document metadata in results
 * so that I can quickly identify relevant documents
 */
test('search results display document metadata', function () {
    $document = OpenOverheidDocument::factory()->create([
        'title' => 'Test Document',
        'organisation' => 'Test Ministry',
        'publication_date' => Carbon::parse('2023-01-15'),
    ]);

    $response = $this->get('/zoeken?zoeken=test');

    $response->assertStatus(200);
    $response->assertSee('Test Document');
    $response->assertSee('Test Ministry');
    $response->assertSee('15-01-2023');
});

test('search results show PDF icon for documents', function () {
    $document = OpenOverheidDocument::factory()->create();

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $response->assertSee('fa-file-pdf', false);
    $response->assertSee('PDF');
});

test('search results show organization as clickable filter button', function () {
    $document = OpenOverheidDocument::factory()->create([
        'organisation' => 'Test Ministry',
    ]);

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $html = $response->getContent();

    // Check for organization filter link
    expect($html)->toContain('/zoeken?organisatie[]=');
    expect($html)->toContain('Test Ministry');
    expect($html)->toContain('fa-building', false);
});

test('search results show link to open.overheid.nl', function () {
    $document = OpenOverheidDocument::factory()->create([
        'external_id' => 'oep-test123',
    ]);

    $response = $this->get('/zoeken');

    $response->assertStatus(200);
    $response->assertSee('https://open.overheid.nl/details/oep-test123', false);
    $response->assertSee('fa-external-link-alt', false);
});

/**
 * User Story: As a user, I want to navigate pagination
 * so that I can browse through all results
 */
test('user can navigate to next page', function () {
    OpenOverheidDocument::factory()->count(25)->create();

    $response = $this->get('/zoeken?per_page=10&pagina=1');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    expect($results['hasNextPage'])->toBeTrue();
    expect($results['hasPreviousPage'])->toBeFalse();
});

test('user can navigate to previous page', function () {
    OpenOverheidDocument::factory()->count(25)->create();

    $response = $this->get('/zoeken?per_page=10&pagina=2');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    expect($results['hasPreviousPage'])->toBeTrue();
});

test('pagination shows correct page numbers', function () {
    OpenOverheidDocument::factory()->count(100)->create();

    $response = $this->get('/zoeken?per_page=10&pagina=1');

    $response->assertStatus(200);
    $response->assertSee('1');
    $response->assertSee('2');
});

/**
 * User Story: As a user, I want to search only in titles
 * so that I can find documents with specific titles
 */
test('user can search only in titles', function () {
    $inTitle = OpenOverheidDocument::factory()->create([
        'title' => 'Klimaat Document',
        'description' => 'Other content',
    ]);

    $inDescription = OpenOverheidDocument::factory()->create([
        'title' => 'Other Title',
        'description' => 'Klimaat Document',
    ]);

    $response = $this->get('/zoeken?zoeken=klimaat&titles_only=1');

    $response->assertStatus(200);
    $results = $response->viewData('results');

    $titles = collect($results['items'])->pluck('title')->toArray();
    expect($titles)->toContain($inTitle->title);
    expect($titles)->not->toContain($inDescription->title);
});
