<?php

namespace App\Mail;

use App\Models\SearchSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SearchSubscription $subscription,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bevestig uw attendering — oPub.nl',
        );
    }

    public function content(): Content
    {
        $verifyUrl = url("/attendering/verify/{$this->subscription->verification_token}");

        return new Content(
            view: 'emails.subscription.verification',
            with: [
                'subscription' => $this->subscription,
                'verifyUrl' => $verifyUrl,
            ],
        );
    }
}
