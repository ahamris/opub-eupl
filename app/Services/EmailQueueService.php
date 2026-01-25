<?php

namespace App\Services;

use App\Models\SentEmail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class EmailQueueService
{
    /**
     * Check if mailing is configured.
     */
    public function isMailingConfigured(): bool
    {
        $mailDriver = config('mail.default');
        
        // Check if mail driver is set and not 'log' or 'array' (testing drivers)
        if (in_array($mailDriver, ['log', 'array'])) {
            return false;
        }
        
        // For SMTP, check if host is configured
        if ($mailDriver === 'smtp') {
            $host = config('mail.mailers.smtp.host');
            return !empty($host);
        }
        
        // For other drivers (sendmail, etc.), assume configured if driver is set
        return !empty($mailDriver);
    }

    /**
     * Queue an email by rendering the mailable and storing it in the database.
     */
    public function queueEmail(Mailable $mailable, string $to, ?string $cc = null): ?SentEmail
    {
        // Check if mailing is configured
        if (!$this->isMailingConfigured()) {
            Log::info('Email queuing skipped - mailing not configured', [
                'to' => $to,
                'mailable' => get_class($mailable),
            ]);
            return null;
        }

        try {
            // Render the mailable to get subject and body
            $envelope = $mailable->envelope();
            $subject = $envelope->subject;
            
            // Render the email body
            $content = $mailable->content();
            $view = $content->view;
            $viewData = $mailable->buildViewData();
            
            // Render the view to HTML
            $body = view($view, $viewData)->render();
            
            // Determine mailable type and ID
            $mailableType = get_class($mailable);
            $mailableId = null;
            
            // Try to extract ID from mailable properties (common pattern)
            if (isset($mailable->submission)) {
                $mailableId = $mailable->submission->id;
                $mailableType = 'ContactSubmission';
            } elseif (isset($mailable->contactSubmission)) {
                $mailableId = $mailable->contactSubmission->id;
                $mailableType = 'ContactSubmission';
            }
            
            // Create SentEmail record
            $sentEmail = SentEmail::create([
                'to' => $to,
                'cc' => $cc,
                'subject' => $subject,
                'body' => $body,
                'is_sent' => false,
                'mailable_type' => $mailableType,
                'mailable_id' => $mailableId,
                'attempts' => 0,
            ]);
            
            Log::info('Email queued successfully', [
                'sent_email_id' => $sentEmail->id,
                'to' => $to,
                'subject' => $subject,
            ]);
            
            return $sentEmail;
        } catch (\Exception $e) {
            Log::error('Failed to queue email', [
                'to' => $to,
                'mailable' => get_class($mailable),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return null;
        }
    }

    /**
     * Send a queued email.
     */
    public function sendQueuedEmail(SentEmail $sentEmail): bool
    {
        try {
            \Illuminate\Support\Facades\Mail::html($sentEmail->body, function ($message) use ($sentEmail) {
                $message->to($sentEmail->to)
                        ->subject($sentEmail->subject);
                
                if ($sentEmail->cc) {
                    $message->cc($sentEmail->cc);
                }
            });
            
            $sentEmail->markAsSent();
            
            Log::info('Queued email sent successfully', [
                'sent_email_id' => $sentEmail->id,
                'to' => $sentEmail->to,
            ]);
            
            return true;
        } catch (\Exception $e) {
            $sentEmail->recordFailure($e->getMessage());
            
            Log::error('Failed to send queued email', [
                'sent_email_id' => $sentEmail->id,
                'to' => $sentEmail->to,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
}
