<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionVerificationMail;
use App\Models\SearchSubscription;
use App\Services\EmailQueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Create a subscription via API (for SPA).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'frequency' => ['required', 'in:immediate,daily,weekly'],
            'search_query' => ['nullable', 'string', 'max:500'],
            'filters' => ['nullable', 'array'],
            'filters.organisation' => ['nullable', 'string'],
            'filters.category' => ['nullable', 'string'],
            'filters.theme' => ['nullable', 'string'],
            'filters.document_type' => ['nullable', 'string'],
            'filters.date_from' => ['nullable', 'date'],
            'filters.date_to' => ['nullable', 'date'],
        ]);

        // Prevent duplicate active subscriptions for same email + query
        $existing = SearchSubscription::where('email', $validated['email'])
            ->where('search_query', $validated['search_query'] ?? null)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'U heeft al een actieve attendering voor deze zoekopdracht.',
                'subscription_id' => $existing->id,
            ], 409);
        }

        $subscription = SearchSubscription::createWithVerification([
            'email' => $validated['email'],
            'frequency' => $validated['frequency'],
            'search_query' => $validated['search_query'] ?? null,
            'filters' => $validated['filters'] ?? null,
            'is_active' => false,
        ]);

        // Send verification email
        $this->sendVerificationEmail($subscription);

        return response()->json([
            'message' => 'Attendering aangemaakt. Controleer uw e-mail om te bevestigen.',
            'subscription_id' => $subscription->id,
        ], 201);
    }

    /**
     * Verify a subscription via token (clicked from email).
     */
    public function verify(string $token)
    {
        $subscription = SearchSubscription::where('verification_token', $token)->first();

        if (! $subscription) {
            return redirect('/zoeken')->with('error', 'Ongeldige of verlopen verificatielink.');
        }

        if ($subscription->isVerified()) {
            return redirect('/zoeken')->with('info', 'Uw attendering is al bevestigd.');
        }

        $subscription->verify();

        Log::info('Subscription verified', ['id' => $subscription->id, 'email' => $subscription->email]);

        // Redirect to SPA with success message
        return redirect('/zoeken?q=' . urlencode($subscription->search_query ?? '*') . '&verified=1');
    }

    /**
     * Unsubscribe (one-click from email footer).
     */
    public function unsubscribe(string $id, string $hash)
    {
        $subscription = SearchSubscription::find($id);

        if (! $subscription || sha1($subscription->email) !== $hash) {
            return redirect('/')->with('error', 'Ongeldige link.');
        }

        $subscription->update(['is_active' => false]);

        Log::info('Subscription unsubscribed', ['id' => $id, 'email' => $subscription->email]);

        return redirect('/zoeken?unsubscribed=1');
    }

    /**
     * Resend verification email.
     */
    public function resendVerification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'subscription_id' => ['required', 'integer'],
        ]);

        $subscription = SearchSubscription::where('id', $validated['subscription_id'])
            ->where('email', $validated['email'])
            ->whereNull('verified_at')
            ->first();

        if (! $subscription) {
            return response()->json(['message' => 'Attendering niet gevonden of al bevestigd.'], 404);
        }

        // Regenerate token
        $subscription->update(['verification_token' => SearchSubscription::generateVerificationToken()]);
        $this->sendVerificationEmail($subscription);

        return response()->json(['message' => 'Verificatie-e-mail opnieuw verzonden.']);
    }

    /**
     * Send verification email via the email queue.
     */
    protected function sendVerificationEmail(SearchSubscription $subscription): void
    {
        try {
            $emailService = app(EmailQueueService::class);
            $mailable = new SubscriptionVerificationMail($subscription);
            $emailService->queueEmail($mailable, $subscription->email);

            Log::info('Verification email queued', ['subscription_id' => $subscription->id]);
        } catch (\Exception $e) {
            Log::error('Failed to queue verification email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
