<?php

namespace App\Console\Commands;

use App\Models\SentEmail;
use App\Services\EmailQueueService;
use Illuminate\Console\Command;

class SendQueuedEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-queued
                            {--limit= : Maximum number of emails to send (default: all)}
                            {--retry-failed : Also retry failed emails}
                            {--max-attempts=3 : Maximum attempts before skipping}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send queued emails from the sent_emails table';

    /**
     * Execute the console command.
     */
    public function handle(EmailQueueService $emailQueueService): int
    {
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $retryFailed = $this->option('retry-failed');
        $maxAttempts = (int) $this->option('max-attempts');

        $this->info('Starting to send queued emails...');

        // Build query for unsent emails
        $query = SentEmail::unsent();
        
        if ($retryFailed) {
            // Include failed emails that haven't exceeded max attempts
            $query->orWhere(function ($q) use ($maxAttempts) {
                $q->whereNotNull('error_message')
                  ->where('attempts', '<', $maxAttempts);
            });
        }

        $emails = $query->orderBy('created_at', 'asc');
        
        if ($limit) {
            $emails = $emails->limit($limit);
        }
        
        $emails = $emails->get();

        if ($emails->isEmpty()) {
            $this->info('No queued emails to send.');
            return Command::SUCCESS;
        }

        $this->info("Found {$emails->count()} email(s) to send.");

        $successCount = 0;
        $failureCount = 0;
        $bar = $this->output->createProgressBar($emails->count());
        $bar->start();

        foreach ($emails as $sentEmail) {
            try {
                $success = $emailQueueService->sendQueuedEmail($sentEmail);
                
                if ($success) {
                    $successCount++;
                } else {
                    $failureCount++;
                    if ($sentEmail->attempts >= $maxAttempts) {
                        $this->newLine();
                        $this->warn("Email #{$sentEmail->id} exceeded max attempts ({$maxAttempts}). Skipping.");
                    }
                }
            } catch (\Exception $e) {
                $failureCount++;
                $sentEmail->recordFailure($e->getMessage());
                $this->newLine();
                $this->error("Error sending email #{$sentEmail->id}: {$e->getMessage()}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info("Email sending completed!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Sent Successfully', $successCount],
                ['Failed', $failureCount],
                ['Total Processed', $emails->count()],
            ]
        );

        // Show failed emails if any
        if ($failureCount > 0) {
            $failedEmails = SentEmail::whereIn('id', $emails->pluck('id'))
                ->whereNotNull('error_message')
                ->where('is_sent', false)
                ->get(['id', 'to', 'subject', 'error_message', 'attempts']);
            
            if ($failedEmails->isNotEmpty()) {
                $this->newLine();
                $this->warn('Failed Emails:');
                $this->table(
                    ['ID', 'To', 'Subject', 'Attempts', 'Error'],
                    $failedEmails->map(function ($email) {
                        return [
                            $email->id,
                            $email->to,
                            \Str::limit($email->subject, 40),
                            $email->attempts,
                            \Str::limit($email->error_message, 50),
                        ];
                    })->toArray()
                );
            }
        }

        return Command::SUCCESS;
    }
}
