<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionAlertMail;
use App\Models\SearchSubscription;
use App\Services\EmailQueueService;
use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSubscriptionAlerts extends Command
{
    protected $signature = 'subscriptions:send-alerts
                            {--frequency=daily : Frequency to process (immediate|daily|weekly)}
                            {--dry-run : Show what would be sent without sending}
                            {--limit=100 : Maximum subscriptions to process}';

    protected $description = 'Check for new documents matching active subscriptions and send alert emails';

    public function handle(): int
    {
        $frequency = $this->option('frequency');
        $dryRun = $this->option('dry-run');
        $limit = (int) $this->option('limit');

        $this->info("Processing {$frequency} subscriptions" . ($dryRun ? ' (dry run)' : ''));

        $subscriptions = SearchSubscription::active()
            ->verified()
            ->where('frequency', $frequency)
            ->limit($limit)
            ->get();

        $this->info("Found {$subscriptions->count()} active subscriptions");

        $sent = 0;
        $skipped = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $documents = $this->findNewDocuments($subscription);

                if (empty($documents)) {
                    $skipped++;
                    continue;
                }

                $count = count($documents);
                $this->line("  [{$subscription->email}] \"{$subscription->search_query}\" → {$count} new docs");

                if ($dryRun) {
                    $sent++;
                    continue;
                }

                $this->sendAlertEmail($subscription, $documents);
                $subscription->update(['last_sent_at' => now()]);
                $sent++;
            } catch (\Exception $e) {
                $this->error("  Error for subscription {$subscription->id}: {$e->getMessage()}");
                Log::error('Subscription alert failed', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Done: {$sent} alerts sent, {$skipped} skipped (no new docs)");

        return self::SUCCESS;
    }

    /**
     * Find documents matching the subscription that are newer than last_sent_at.
     */
    protected function findNewDocuments(SearchSubscription $subscription): array
    {
        $searchService = app(TypesenseSearchService::class);

        $query = $subscription->search_query ?: '*';
        $options = ['per_page' => 10];

        // Build filters
        $filters = [];
        $filterData = $subscription->filters ?? [];

        // Map subscription filter keys to Typesense field names
        $fieldMap = [
            'organisation' => 'organisation',
            'organisatie' => 'organisation',
            'category' => 'category',
            'informatiecategorie' => 'category',
            'theme' => 'theme',
            'thema' => 'theme',
            'document_type' => 'document_type',
            'documentsoort' => 'document_type',
        ];

        foreach ($filterData as $key => $value) {
            $field = $fieldMap[$key] ?? null;
            if (! $field || empty($value)) {
                continue;
            }

            if (is_array($value)) {
                $escaped = array_map(fn ($v) => '`' . str_replace('`', '', $v) . '`', $value);
                $filters[] = "{$field}:=[" . implode(',', $escaped) . ']';
            } else {
                $filters[] = "{$field}:={$value}";
            }
        }

        // Time filter: only documents since last alert
        $since = $subscription->last_sent_at ?? $subscription->verified_at ?? $subscription->created_at;
        if ($since) {
            $filters[] = 'publication_date:>=' . $since->timestamp;
        }

        if (! empty($filters)) {
            $options['filter_by'] = implode(' && ', $filters);
        }

        $options['sort_by'] = 'publication_date:desc';

        try {
            $results = $searchService->search($query, $options);
            $hits = $results['hits'] ?? [];

            return array_map(function ($hit) {
                $doc = $hit['document'] ?? $hit;
                $pubDate = $doc['publication_date'] ?? 0;

                return [
                    'external_id' => $doc['external_id'] ?? $doc['id'] ?? '',
                    'title' => $doc['title'] ?? 'Geen titel',
                    'description' => mb_substr($doc['description'] ?? '', 0, 300),
                    'organisation' => $doc['organisation'] ?? '',
                    'publication_date' => is_numeric($pubDate) && $pubDate > 0
                        ? date('d-m-Y', (int) $pubDate)
                        : ($pubDate ?: ''),
                ];
            }, $hits);
        } catch (\Exception $e) {
            Log::warning('Search failed for subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Send the alert email via the email queue.
     */
    protected function sendAlertEmail(SearchSubscription $subscription, array $documents): void
    {
        $emailService = app(EmailQueueService::class);
        $mailable = new SubscriptionAlertMail($subscription, $documents);
        $emailService->queueEmail($mailable, $subscription->email);
    }
}
