<?php

namespace App\Console\Commands;

use App\Models\Bestuursorgaan;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use XMLReader;

class ImportBestuursorganen extends Command
{
    protected $signature = 'bestuursorganen:import
                            {--url=https://organisaties.overheid.nl/archive/exportOO.xml : URL of the XML export}
                            {--dry-run : Show what would be imported without writing}';

    protected $description = 'Import bestuursorganen from organisaties.overheid.nl XML export';

    private const NS = 'https://organisaties.overheid.nl/static/schema/oo/export/2.6.9';

    public function handle(): int
    {
        $url = $this->option('url');
        $dryRun = $this->option('dry-run');

        $this->info("Downloading XML from {$url}...");

        $tempFile = tempnam(sys_get_temp_dir(), 'oo_xml_');
        $context = stream_context_create(['http' => ['timeout' => 120]]);
        $data = @file_get_contents($url, false, $context);

        if ($data === false) {
            $this->error('Failed to download XML.');
            return self::FAILURE;
        }

        file_put_contents($tempFile, $data);
        $this->info('XML downloaded (' . round(strlen($data) / 1024 / 1024, 1) . ' MB). Parsing...');
        unset($data);

        $reader = new XMLReader();
        $reader->open($tempFile);

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        while ($reader->read()) {
            if ($reader->nodeType !== XMLReader::ELEMENT) continue;
            if ($reader->localName !== 'organisatie') continue;

            $systeemId = $reader->getAttributeNs('systeemId', self::NS)
                      ?? $reader->getAttribute('p:systeemId');
            $tooiId = $reader->getAttributeNs('resourceIdentifierTOOI', self::NS)
                   ?? $reader->getAttribute('p:resourceIdentifierTOOI');
            $owmsId = $reader->getAttributeNs('resourceIdentifierOWMS', self::NS)
                   ?? $reader->getAttribute('p:resourceIdentifierOWMS');

            if (!$systeemId) {
                $skipped++;
                continue;
            }

            $orgXml = $reader->readOuterXml();
            $orgData = $this->parseOrganisatie($orgXml, $systeemId, $tooiId, $owmsId);

            if (!$orgData || empty($orgData['naam'])) {
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  [{$orgData['type']}] {$orgData['naam']} ({$orgData['systeem_id']})");
                $imported++;
                continue;
            }

            $existing = Bestuursorgaan::where('systeem_id', $orgData['systeem_id'])->first();

            if ($existing) {
                // Don't overwrite claimed custom fields
                $protectedFields = ['logo_url', 'custom_beschrijving', 'claimed_by_user_id', 'claimed_at', 'document_match_name'];
                foreach ($protectedFields as $field) {
                    if ($existing->$field) {
                        unset($orgData[$field]);
                    }
                }
                $existing->update($orgData);
                $updated++;
            } else {
                Bestuursorgaan::create($orgData);
                $imported++;
            }
        }

        $reader->close();
        @unlink($tempFile);

        $action = $dryRun ? 'Would import' : 'Imported';
        $this->info("{$action}: {$imported} new, {$updated} updated, {$skipped} skipped.");

        return self::SUCCESS;
    }

    private function parseOrganisatie(string $xml, string $systeemId, ?string $tooiId, ?string $owmsId): ?array
    {
        // Wrap in root with namespace
        $wrappedXml = '<root xmlns:p="' . self::NS . '">' . $xml . '</root>';

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        if (!$doc->loadXML($wrappedXml)) {
            return null;
        }

        $xpath = new \DOMXPath($doc);
        $xpath->registerNamespace('p', self::NS);

        $naam = $this->xpathValue($xpath, '//p:naam');
        if (!$naam) return null;

        $type = $this->xpathValue($xpath, '//p:types/p:type') ?: 'Overig';

        // Parse addresses
        $addresses = [];
        $adresNodes = $xpath->query('//p:adressen/p:adres');
        foreach ($adresNodes as $adresNode) {
            $adresType = $this->nodeValue($xpath, 'p:adresType', $adresNode);
            $addresses[$adresType] = [
                'straat' => $this->nodeValue($xpath, 'p:openbareRuimte', $adresNode),
                'huisnummer' => $this->nodeValue($xpath, 'p:huisnummer', $adresNode),
                'postbus' => $this->nodeValue($xpath, 'p:postbus', $adresNode),
                'postcode' => $this->nodeValue($xpath, 'p:postcode', $adresNode),
                'woonplaats' => $this->nodeValue($xpath, 'p:woonplaats', $adresNode),
                'provincie' => $this->nodeValue($xpath, 'p:provincie', $adresNode),
                'toelichting' => $this->nodeValue($xpath, 'p:toelichting', $adresNode),
            ];
        }

        $bezoek = $addresses['Bezoekadres'] ?? [];
        $post = $addresses['Postadres'] ?? [];
        $woo = $addresses['Woo-Adres'] ?? [];

        // Check Woo classification
        $isWoo = false;
        $wooUrl = null;
        $classNodes = $xpath->query('//p:classificaties/p:classificatie');
        foreach ($classNodes as $classNode) {
            $classType = $classNode->getAttributeNS(self::NS, 'type')
                      ?: $classNode->getAttribute('p:type');
            if ($classType === 'Woo') {
                $isWoo = true;
                $wooUrl = $classNode->getAttributeNS(self::NS, 'url')
                       ?: $classNode->getAttribute('p:url');
            }
        }

        // Parse WOO email
        $wooEmail = null;
        $emailNodes = $xpath->query('//p:contact/p:emailadressen/p:emailadres');
        foreach ($emailNodes as $emailNode) {
            $label = $this->nodeValue($xpath, 'p:label', $emailNode);
            $emailValue = $this->nodeValue($xpath, 'p:email', $emailNode);
            if (stripos($label, 'woo') !== false || stripos($label, 'wob') !== false) {
                $wooEmail = $emailValue;
            }
        }

        // Description
        $beschrijving = $this->xpathValue($xpath, '//p:organisatieBeschrijving/p:beschrijvingText');

        return [
            'systeem_id' => $systeemId,
            'naam' => trim($naam),
            'afkorting' => $this->xpathValue($xpath, '//p:afkorting'),
            'slug' => $this->uniqueSlug($naam, $systeemId),
            'type' => $type,
            'subtype' => $this->xpathValue($xpath, '//p:soortAdviescollege')
                      ?: $this->xpathValue($xpath, '//p:rechtsvorm'),

            // Bezoekadres
            'straat' => trim($bezoek['straat'] ?? ''),
            'huisnummer' => trim($bezoek['huisnummer'] ?? ''),
            'postcode' => trim($bezoek['postcode'] ?? ''),
            'woonplaats' => trim($bezoek['woonplaats'] ?? ''),
            'provincie' => $this->cleanProvincie($bezoek['provincie'] ?? ''),

            // Postadres
            'postbus' => trim($post['postbus'] ?? ''),
            'post_postcode' => trim($post['postcode'] ?? ''),
            'post_woonplaats' => trim($post['woonplaats'] ?? ''),

            // Woo-adres
            'woo_straat' => trim($woo['straat'] ?? ''),
            'woo_huisnummer' => trim($woo['huisnummer'] ?? ''),
            'woo_postbus' => trim($woo['postbus'] ?? ''),
            'woo_postcode' => trim($woo['postcode'] ?? ''),
            'woo_woonplaats' => trim($woo['woonplaats'] ?? ''),
            'woo_email' => $wooEmail,

            // Contact
            'telefoon' => $this->xpathValue($xpath, '//p:contact/p:telefoonnummers/p:telefoonnummer/p:nummer'),
            'email' => $this->xpathValue($xpath, '//p:contact/p:emailadressen/p:emailadres/p:email'),
            'website' => $this->xpathValue($xpath, '//p:contact/p:internetadressen/p:internetadres/p:url'),
            'contactformulier_url' => $this->xpathValue($xpath, '//p:contact/p:contactformulieren/p:contactformulier/p:url'),

            // Identifiers
            'tooi_identifier' => $tooiId,
            'owms_identifier' => $owmsId,

            // Ministerie
            'relatie_ministerie' => $this->xpathValue($xpath, '//p:relatieMetMinisterie'),

            // Woo
            'is_woo_plichtig' => $isWoo,
            'woo_url' => $wooUrl,

            // Description
            'beschrijving' => $beschrijving ? trim(strip_tags($beschrijving)) : null,

            // Document matching — use naam by default
            'document_match_name' => trim($naam),
        ];
    }

    private function uniqueSlug(string $naam, string $systeemId): string
    {
        $base = Str::slug(trim($naam));
        $slug = $base;

        if (Bestuursorgaan::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $systeemId;
        }

        return $slug;
    }

    private function xpathValue(\DOMXPath $xpath, string $expression, ?\DOMNode $context = null): ?string
    {
        $nodes = $context ? $xpath->query($expression, $context) : $xpath->query($expression);
        if ($nodes && $nodes->length > 0) {
            $value = trim($nodes->item(0)->textContent);
            return $value !== '' ? $value : null;
        }
        return null;
    }

    private function nodeValue(\DOMXPath $xpath, string $expression, \DOMNode $context): ?string
    {
        return $this->xpathValue($xpath, $expression, $context);
    }

    private function cleanProvincie(string $value): ?string
    {
        if (empty($value)) return null;
        // Extract province name from TOOI URL like .../pv27
        if (str_contains($value, 'identifier.overheid.nl')) {
            return $value; // Keep as reference, can resolve later
        }
        return trim($value);
    }
}
