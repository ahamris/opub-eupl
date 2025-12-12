<?php

namespace App\Services\OpenOverheid;

/**
 * Service for handling WOO (Wet Open Overheid) information categories.
 * 
 * The WOO defines specific categories of government information that must be
 * actively published. This service handles normalization and formatting of
 * these category names.
 */
class WooCategoryService
{
    /**
     * WOO article 3.3 information categories with their codes and descriptions.
     * 
     * @var array<string, string>
     */
    protected array $categories = [
        '2a' => 'Wet- en regelgeving',
        '2b' => 'Organisatiegegevens',
        '2c' => 'Raadstukken',
        '2d' => 'Bestuursstukken',
        '2e' => 'Stukken van adviescolleges',
        '2f' => 'Convenanten',
        '2g' => 'Jaarplannen en -verslagen',
        '2h' => 'Subsidieverplichtingen',
        '2i' => 'Woo-verzoeken en -besluiten',
        '2j' => 'Onderzoeksrapporten',
        '2k' => 'Beschikkingen',
        '2l' => 'Klachtoordelen',
        '2m' => 'Bij vertegenwoordigende organen ingekomen stukken',
        '2n' => 'Vergaderstukken Staten-Generaal',
        '2o' => 'Vergaderstukken decentrale overheden',
        '2p' => 'Agenda\'s en besluitenlijsten',
    ];

    /**
     * Mapping of common variations to normalized category names.
     * 
     * @var array<string, string>
     */
    protected array $categoryMappings = [
        // Common variations and abbreviations
        'wet- en regelgeving' => '2a',
        'wet en regelgeving' => '2a',
        'regelgeving' => '2a',
        'organisatiegegevens' => '2b',
        'organisatie' => '2b',
        'raadstukken' => '2c',
        'raadstuk' => '2c',
        'bestuursstukken' => '2d',
        'bestuursstuk' => '2d',
        'adviescolleges' => '2e',
        'stukken van adviescolleges' => '2e',
        'convenanten' => '2f',
        'convenant' => '2f',
        'jaarplannen' => '2g',
        'jaarverslagen' => '2g',
        'jaarplannen en -verslagen' => '2g',
        'jaarplan' => '2g',
        'jaarverslag' => '2g',
        'subsidieverplichtingen' => '2h',
        'subsidie' => '2h',
        'woo-verzoeken' => '2i',
        'woo-besluiten' => '2i',
        'woo-verzoeken en -besluiten' => '2i',
        'wob-verzoeken' => '2i',
        'onderzoeksrapporten' => '2j',
        'onderzoeksrapport' => '2j',
        'onderzoek' => '2j',
        'beschikkingen' => '2k',
        'beschikking' => '2k',
        'klachtoordelen' => '2l',
        'klachtoordeel' => '2l',
        'klacht' => '2l',
        'ingekomen stukken' => '2m',
        'vergaderstukken staten-generaal' => '2n',
        'staten-generaal' => '2n',
        'vergaderstukken decentrale overheden' => '2o',
        'decentrale overheden' => '2o',
        'agenda\'s en besluitenlijsten' => '2p',
        'agendas en besluitenlijsten' => '2p',
        'agenda' => '2p',
        'besluitenlijst' => '2p',
    ];

    /**
     * Normalize a category string to its canonical form.
     * Returns the category description without the code prefix.
     */
    public function normalizeCategory(?string $category): ?string
    {
        if (empty($category)) {
            return null;
        }

        $normalized = strtolower(trim($category));

        // Check if it's already a code (e.g., "2j")
        if (isset($this->categories[$normalized])) {
            return $this->categories[$normalized];
        }

        // Check if it matches a code with description (e.g., "2j. Onderzoeksrapporten")
        if (preg_match('/^(2[a-p])[\.\s]+(.+)$/i', $category, $matches)) {
            $code = strtolower($matches[1]);
            if (isset($this->categories[$code])) {
                return $this->categories[$code];
            }
        }

        // Check mappings for common variations
        if (isset($this->categoryMappings[$normalized])) {
            $code = $this->categoryMappings[$normalized];
            return $this->categories[$code];
        }

        // Try partial matching
        foreach ($this->categoryMappings as $variation => $code) {
            if (str_contains($normalized, $variation) || str_contains($variation, $normalized)) {
                return $this->categories[$code];
            }
        }

        // Check if it directly matches a category description
        foreach ($this->categories as $code => $description) {
            if (strtolower($description) === $normalized) {
                return $description;
            }
        }

        // Return as-is if no match found
        return $category;
    }

    /**
     * Format a category for display with its code prefix.
     * E.g., "Onderzoeksrapporten" becomes "2j. Onderzoeksrapporten"
     */
    public function formatCategoryForDisplay(?string $category): ?string
    {
        if (empty($category)) {
            return null;
        }

        // If already formatted with code, return as-is
        if (preg_match('/^2[a-p][\.\s]+.+$/i', $category)) {
            return $category;
        }

        $normalized = strtolower(trim($category));

        // Check if it's a code
        if (isset($this->categories[$normalized])) {
            return strtoupper($normalized) . '. ' . $this->categories[$normalized];
        }

        // Check mappings
        if (isset($this->categoryMappings[$normalized])) {
            $code = $this->categoryMappings[$normalized];
            return strtoupper($code) . '. ' . $this->categories[$code];
        }

        // Check if it matches a category description
        foreach ($this->categories as $code => $description) {
            if (strtolower($description) === $normalized) {
                return strtoupper($code) . '. ' . $description;
            }
        }

        // Return as-is if no code found
        return $category;
    }

    /**
     * Get all available WOO categories.
     * 
     * @return array<string, string> Code => Description
     */
    public function getAllCategories(): array
    {
        return $this->categories;
    }

    /**
     * Get all categories formatted for display.
     * 
     * @return array<string> List of formatted categories (e.g., "2A. Wet- en regelgeving")
     */
    public function getFormattedCategories(): array
    {
        $formatted = [];
        foreach ($this->categories as $code => $description) {
            $formatted[] = strtoupper($code) . '. ' . $description;
        }
        return $formatted;
    }

    /**
     * Get the code for a category.
     */
    public function getCategoryCode(?string $category): ?string
    {
        if (empty($category)) {
            return null;
        }

        $normalized = strtolower(trim($category));

        // Check if it's already a code
        if (isset($this->categories[$normalized])) {
            return $normalized;
        }

        // Check if it matches a code with description
        if (preg_match('/^(2[a-p])[\.\s]+/i', $category, $matches)) {
            return strtolower($matches[1]);
        }

        // Check mappings
        if (isset($this->categoryMappings[$normalized])) {
            return $this->categoryMappings[$normalized];
        }

        // Check if it matches a category description
        foreach ($this->categories as $code => $description) {
            if (strtolower($description) === $normalized) {
                return $code;
            }
        }

        return null;
    }
}

