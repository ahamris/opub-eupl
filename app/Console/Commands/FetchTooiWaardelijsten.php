<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchTooiWaardelijsten extends Command
{
    protected $signature = 'tooi:fetch-waardelijsten';

    protected $description = 'Fetch TOOI waardelijsten and create flattened JSON file';

    private $registers = [
        'gemeenten' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_gemeenten_compleet/4/json/rwc_gemeenten_compleet_4.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_gemeenten_compleet',
            'type' => 'gemeente',
            'code_prefix' => 'gm',
        ],
        'provincies' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_provincies_compleet/1/json/rwc_provincies_compleet_1.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_provincies_compleet',
            'type' => 'provincie',
            'code_prefix' => 'pv',
        ],
        'ministeries' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_ministeries_compleet/1/json/rwc_ministeries_compleet_1.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_ministeries_compleet',
            'type' => 'ministerie',
            'code_prefix' => 'mnre',
        ],
        'waterschappen' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_waterschappen_compleet/1/json/rwc_waterschappen_compleet_1.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_waterschappen_compleet',
            'type' => 'waterschap',
            'code_prefix' => 'ws',
        ],
        'zbo' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_zbo_compleet/1/json/rwc_zbo_compleet_1.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_zbo_compleet',
            'type' => 'zbo',
            'code_prefix' => 'zbo',
        ],
        'caribische_openbare_lichamen' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_caribische_openbare_lichamen_compleet/1/json/rwc_caribische_openbare_lichamen_compleet_1.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_caribische_openbare_lichamen_compleet',
            'type' => 'col',
            'code_prefix' => 'col',
        ],
        'overige_overheidsorganisaties' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_overige_overheidsorganisaties_compleet/1/json/rwc_overige_overheidsorganisaties_compleet_1.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_overige_overheidsorganisaties_compleet',
            'type' => 'overige',
            'code_prefix' => '',
        ],
        'samenwerkingsorganisaties' => [
            'url' => 'https://repository.officiele-overheidspublicaties.nl/waardelijsten/rwc_samenwerkingsorganisaties_compleet/1/json/rwc_samenwerkingsorganisaties_compleet_1.json',
            'work_uri' => 'https://identifier.overheid.nl/tooi/set/rwc_samenwerkingsorganisaties_compleet',
            'type' => 'samenwerking',
            'code_prefix' => '',
        ],
    ];

    public function handle()
    {
        $this->info('Fetching TOOI waardelijsten...');

        $output = [
            'metadata' => [
                'exported_at' => now()->toIso8601String(),
                'tooi_version' => '1.4.0-rc',
                'source' => 'https://standaarden.overheid.nl/tooi/',
                'description' => 'Flattened JSON structure for TOOI waardelijsten (value lists) containing government organizations',
            ],
            'registers' => [],
        ];

        foreach ($this->registers as $key => $config) {
            $this->info("Fetching {$key}...");

            try {
                $response = Http::timeout(30)->get($config['url']);

                if (! $response->successful()) {
                    $this->warn("Failed to fetch {$key}: HTTP {$response->status()}");

                    continue;
                }

                $data = $response->json();

                if (! is_array($data)) {
                    $this->warn("Invalid JSON format for {$key}");

                    continue;
                }

                $items = $this->parseJsonLd($data, $config);

                $output['registers'][$key] = [
                    'work_uri' => $config['work_uri'],
                    'title' => $this->getTitle($key),
                    'description' => $this->getRegisterDescription($key),
                    'responsible' => 'https://identifier.overheid.nl/tooi/id/ministerie/mnre1034',
                    'item_count' => count($items),
                    'items' => $items,
                ];

                $this->info('  ✓ Fetched '.count($items).' items');
            } catch (\Exception $e) {
                $this->error("Error fetching {$key}: ".$e->getMessage());
            }
        }

        // Save to file
        $outputPath = storage_path('app/tooi_waardelijsten.json');
        file_put_contents(
            $outputPath,
            json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $this->newLine();
        $this->info("✓ Saved to: {$outputPath}");
        $this->info('Total registers: '.count($output['registers']));

        $totalItems = array_sum(array_map(fn ($r) => count($r['items']), $output['registers']));
        $this->info("Total items: {$totalItems}");

        return Command::SUCCESS;
    }

    private function parseJsonLd(array $data, array $config): array
    {
        $items = [];

        foreach ($data as $item) {
            if (! isset($item['@id'])) {
                continue;
            }

            $id = $item['@id'];
            $label = $this->extractValue($item, 'http://www.w3.org/2000/01/rdf-schema#label')
                  ?? $this->extractValue($item, 'https://identifier.overheid.nl/tooi/def/ont/officieleNaamInclSoort')
                  ?? $this->extractValue($item, 'https://identifier.overheid.nl/tooi/def/ont/voorkeursnaamInclSoort')
                  ?? 'Unknown';

            $code = $this->extractValue($item, 'https://identifier.overheid.nl/tooi/def/ont/organisatiecode')
                 ?? $this->extractValue($item, 'https://identifier.overheid.nl/tooi/def/ont/gemeentecode')
                 ?? $this->extractValue($item, 'https://identifier.overheid.nl/tooi/def/ont/provinciecode')
                 ?? $this->extractCodeFromId($id, $config['code_prefix']);

            $validFrom = $this->extractDate($item, 'https://identifier.overheid.nl/tooi/def/ont/begindatum');
            $validTo = $this->extractDate($item, 'https://identifier.overheid.nl/tooi/def/ont/einddatum');

            // Determine status
            $status = 'active';
            if ($validTo && strtotime($validTo) < time()) {
                $status = 'inactive';
            }
            if (isset($item['http://www.w3.org/ns/prov#invalidatedAtTime'])) {
                $status = 'inactive';
            }

            $type = 'overheid:'.ucfirst($config['type']);
            if ($config['type'] === 'gemeente') {
                $type = 'overheid:Gemeente';
            } elseif ($config['type'] === 'provincie') {
                $type = 'overheid:Provincie';
            } elseif ($config['type'] === 'ministerie') {
                $type = 'overheid:Ministerie';
            } elseif ($config['type'] === 'waterschap') {
                $type = 'overheid:Waterschap';
            } elseif ($config['type'] === 'zbo') {
                $type = 'overheid:ZBO';
            } elseif ($config['type'] === 'col') {
                $type = 'overheid:CaribischOpenbaarLichaam';
            }

            $items[] = [
                'id' => $id,
                'type' => $type,
                'label' => $label,
                'register' => array_search($config, $this->registers),
                'code' => $code,
                'valid_from' => $validFrom,
                'valid_to' => $validTo,
                'status' => $status,
                'metadata' => [
                    'work_uri' => $config['work_uri'],
                ],
            ];
        }

        return $items;
    }

    private function extractValue(array $item, string $key): ?string
    {
        if (! isset($item[$key])) {
            return null;
        }

        $value = $item[$key];

        if (is_array($value) && isset($value[0]['@value'])) {
            return $value[0]['@value'];
        }

        if (is_array($value) && isset($value[0])) {
            return is_string($value[0]) ? $value[0] : null;
        }

        return is_string($value) ? $value : null;
    }

    private function extractDate(array $item, string $key): ?string
    {
        $value = $this->extractValue($item, $key);
        if (! $value) {
            return null;
        }

        // Try to parse and format date
        try {
            $date = new \DateTime($value);

            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return $value;
        }
    }

    private function extractCodeFromId(string $id, string $prefix): ?string
    {
        if (preg_match('/\/(?:'.preg_quote($prefix, '/').')?([^\/]+)$/', $id, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function getTitle(string $key): string
    {
        $titles = [
            'gemeenten' => 'Register gemeenten compleet',
            'provincies' => 'Register provincies compleet',
            'ministeries' => 'Register ministeries compleet',
            'waterschappen' => 'Register waterschappen compleet',
            'zbo' => 'Register ZBO compleet',
            'caribische_openbare_lichamen' => 'Register Caribische openbare lichamen compleet',
            'overige_overheidsorganisaties' => 'Register overige overheidsorganisaties compleet',
            'samenwerkingsorganisaties' => 'Register samenwerkingsorganisaties compleet',
        ];

        return $titles[$key] ?? "Register {$key} compleet";
    }

    private function getRegisterDescription(string $key): string
    {
        return 'Waardelijst met '.str_replace('_', ' ', $key).' die voor zover bekend op de zichtdatum bestaan hebben, bestaan of zullen bestaan.';
    }
}
