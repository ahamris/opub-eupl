<?php

namespace App\Services\OpenOverheid;

use Illuminate\Support\Facades\Cache;

/**
 * Service for normalizing and formatting Woo information categories
 * according to the official Woo guidelines.
 */
class WooCategoryService
{
    /**
     * Mapping of database category names to official Woo format
     * Format: [article_number => [variations => official_name]]
     */
    private const CATEGORY_MAPPING = [
        // Tranche 1
        '3.3 1a' => [
            'official' => 'Wetten en algemeen verbindende voorschriften',
            'variations' => ['wetten en algemeen verbindende voorschriften', 'wetten', 'algemeen verbindende voorschriften'],
        ],
        '3.3 1b' => [
            'official' => 'Overige besluiten van algemene strekking',
            'variations' => ['overige besluiten van algemene strekking', 'besluiten van algemene strekking', 'overige besluiten'],
        ],
        '3.3 2b' => [
            'official' => 'Vergaderstukken Staten-Generaal',
            'variations' => ['vergaderstukken staten-generaal', 'vergaderstukken staten generaal', 'vergladerstukken staten-generaal'],
        ],
        '3.3 1d' => [
            'official' => 'Organisatie en werkwijze',
            'variations' => ['organisatie en werkwijze', 'organisatie', 'werkwijze'],
        ],
        '3.3 1e' => [
            'official' => 'Bereikbaarheidsgegevens',
            'variations' => ['bereikbaarheidsgegevens', 'bereikbaarheid', 'contactgegevens'],
        ],
        // Tranche 2
        '3.3 1c' => [
            'official' => 'Ontwerpen van wet- en regelgeving met adviesaanvraag',
            'variations' => ['ontwerpen van wet- en regelgeving met adviesaanvraag', 'ontwerpen wet- en regelgeving', 'adviesaanvraag'],
        ],
        '3.3 2a' => [
            'official' => 'Bij vertegenwoordigende organen ingekomen stukken',
            'variations' => ['bij vertegenwoordigende organen ingekomen stukken', 'ingekomen stukken', 'vertegenwoordigende organen'],
        ],
        '3.3 2c' => [
            'official' => 'Vergaderstukken decentrale overheden',
            'variations' => ['vergaderstukken decentrale overheden', 'vergaderstukken decentraal', 'decentrale overheden'],
        ],
        '3.3 2d' => [
            'official' => "Agenda's en besluitenlijsten bestuurscolleges",
            'variations' => ["agenda's en besluitenlijsten bestuurscolleges", 'agendas en besluitenlijsten', 'besluitenlijsten bestuurscolleges', "agenda's bestuurscolleges"],
        ],
        '3.3 2e' => [
            'official' => 'Adviezen',
            'variations' => ['adviezen', 'advies'],
        ],
        '3.3 2g' => [
            'official' => 'Jaarplannen en jaarverslagen',
            'variations' => ['jaarplannen en jaarverslagen', 'jaarplannen', 'jaarverslagen'],
        ],
        '3.3 2i' => [
            'official' => 'Woo-verzoeken en -besluiten',
            'variations' => ['woo-verzoeken en -besluiten', 'woo verzoeken', 'woo besluiten', 'woo-verzoeken', 'woo-besluiten'],
        ],
        // Tranche 3
        '3.3 2f' => [
            'official' => 'Convenanten',
            'variations' => ['convenanten', 'convenant'],
        ],
        '3.3 2h' => [
            'official' => 'Subsidieverplichtingen anders dan met beschikking',
            'variations' => ['subsidieverplichtingen anders dan met beschikking', 'subsidieverplichtingen', 'subsidies'],
        ],
        '3.3 2j' => [
            'official' => 'Onderzoeksrapporten',
            'variations' => ['onderzoeksrapporten', 'onderzoeksrapport', 'rapporten'],
        ],
        '3.3 2l' => [
            'official' => 'Klachtoordelen',
            'variations' => ['klachtoordelen', 'klacht oordelen', 'klachtbeslissingen'],
        ],
        // Tranche 4
        '3.3 2k' => [
            'official' => 'Beschikkingen',
            'variations' => ['beschikkingen', 'beschikking'],
        ],
    ];

    /**
     * Normalize a category name from the database to the official format.
     *
     * @param  string|null  $category  The category name from database
     * @return string|null The normalized category name, or null if not found
     */
    public function normalizeCategory(?string $category): ?string
    {
        if (empty($category)) {
            return null;
        }

        // Cache key for normalization (1 hour cache)
        $cacheKey = 'woo:category:normalized:'.md5(mb_strtolower(trim($category)));

        // Try to get from cache first
        return Cache::remember($cacheKey, 3600, function () use ($category) {
            $normalized = mb_strtolower(trim($category));

            foreach (self::CATEGORY_MAPPING as $article => $data) {
                if ($normalized === mb_strtolower($data['official'])) {
                    return $data['official'];
                }

                foreach ($data['variations'] as $variation) {
                    if ($normalized === mb_strtolower($variation)) {
                        return $data['official'];
                    }
                }
            }

            // If no match found, return original (may be a new or unknown category)
            return $category;
        });
    }

    /**
     * Format a category name with its article number for display.
     *
     * @param  string|null  $category  The category name (should be normalized first)
     * @return string|null The formatted category like "2j. Onderzoeksrapporten", or null
     */
    public function formatCategoryForDisplay(?string $category): ?string
    {
        if (empty($category)) {
            return null;
        }

        // Cache key for this category (1 hour cache)
        $cacheKey = 'woo:category:formatted:'.md5(mb_strtolower(trim($category)));

        // Try to get from cache first
        return Cache::remember($cacheKey, 3600, function () use ($category) {
            $normalized = mb_strtolower(trim($category));

            foreach (self::CATEGORY_MAPPING as $article => $data) {
                if ($normalized === mb_strtolower($data['official'])) {
                    // Extract short article reference (e.g., "3.3 2j" -> "2j")
                    $shortArticle = $this->getShortArticleReference($article);

                    return "{$shortArticle}. {$data['official']}";
                }

                // Also check variations
                foreach ($data['variations'] as $variation) {
                    if ($normalized === mb_strtolower($variation)) {
                        $shortArticle = $this->getShortArticleReference($article);

                        return "{$shortArticle}. {$data['official']}";
                    }
                }
            }

            // If no match found, return original without formatting
            return $category;
        });
    }

    /**
     * Get the short article reference from full article format.
     *
     * @param  string  $article  Full article like "3.3 2j"
     * @return string Short reference like "2j"
     */
    private function getShortArticleReference(string $article): string
    {
        // Extract the part after "3.3 " (e.g., "2j", "1a", "2b")
        if (preg_match('/3\.3\s+(.+)$/', $article, $matches)) {
            return $matches[1];
        }

        return $article;
    }

    /**
     * Get all official category names.
     *
     * @return array Array of official category names
     */
    public function getAllOfficialCategories(): array
    {
        return array_column(self::CATEGORY_MAPPING, 'official');
    }

    /**
     * Get category mapping with article references.
     *
     * @return array Array with article => official name
     */
    public function getCategoryMapping(): array
    {
        $mapping = [];
        foreach (self::CATEGORY_MAPPING as $article => $data) {
            $shortArticle = $this->getShortArticleReference($article);
            $mapping[$shortArticle] = $data['official'];
        }

        return $mapping;
    }

    /**
     * Get tranche information for a category.
     *
     * @param  string|null  $category  The category name
     * @return array|null Array with 'tranche', 'article', 'status', or null
     */
    public function getCategoryTranche(?string $category): ?array
    {
        if (empty($category)) {
            return null;
        }

        $normalized = mb_strtolower(trim($category));

        // Tranche 1
        $tranche1 = ['3.3 1a', '3.3 1b', '3.3 2b', '3.3 1d', '3.3 1e'];
        // Tranche 2
        $tranche2 = ['3.3 1c', '3.3 2a', '3.3 2c', '3.3 2d', '3.3 2e', '3.3 2g', '3.3 2i'];
        // Tranche 3
        $tranche3 = ['3.3 2f', '3.3 2h', '3.3 2j', '3.3 2l'];
        // Tranche 4
        $tranche4 = ['3.3 2k'];

        foreach (self::CATEGORY_MAPPING as $article => $data) {
            if ($normalized === mb_strtolower($data['official'])) {
                $tranche = $this->determineTranche($article, $tranche1, $tranche2, $tranche3, $tranche4);

                return [
                    'tranche' => $tranche,
                    'article' => $article,
                    'short_article' => $this->getShortArticleReference($article),
                    'status' => $tranche === 1 ? 'active' : 'preparation',
                    'official_name' => $data['official'],
                ];
            }

            foreach ($data['variations'] as $variation) {
                if ($normalized === mb_strtolower($variation)) {
                    $tranche = $this->determineTranche($article, $tranche1, $tranche2, $tranche3, $tranche4);

                    return [
                        'tranche' => $tranche,
                        'article' => $article,
                        'short_article' => $this->getShortArticleReference($article),
                        'status' => $tranche === 1 ? 'active' : 'preparation',
                        'official_name' => $data['official'],
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Determine which tranche an article belongs to.
     */
    private function determineTranche(string $article, array $t1, array $t2, array $t3, array $t4): int
    {
        if (in_array($article, $t1)) {
            return 1;
        }
        if (in_array($article, $t2)) {
            return 2;
        }
        if (in_array($article, $t3)) {
            return 3;
        }
        if (in_array($article, $t4)) {
            return 4;
        }

        return 0;
    }
}
