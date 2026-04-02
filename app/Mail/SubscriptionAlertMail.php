<?php

namespace App\Mail;

use App\Models\SearchSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SearchSubscription $subscription,
        public array $documents,
    ) {}

    public function envelope(): Envelope
    {
        $count = count($this->documents);
        $query = $this->subscription->search_query ?: 'Alle documenten';

        return new Envelope(
            subject: "{$count} nieuwe documenten — {$query} — oPub.nl",
        );
    }

    public function content(): Content
    {
        $unsubscribeUrl = url("/attendering/unsubscribe/{$this->subscription->id}/" . sha1($this->subscription->email));

        return new Content(
            view: 'emails.subscription.alert',
            with: [
                'subscription' => $this->subscription,
                'documents' => $this->documents,
                'unsubscribeUrl' => $unsubscribeUrl,
            ],
        );
    }
}
