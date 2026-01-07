<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReplyMail;
use App\Mail\ContactNotificationMail;
use App\Models\ContactSubmission;
use App\Models\SearchSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Store a new contact submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organisation' => 'nullable|string|max:255',
            'full-name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|in:algemeen,technisch,samenwerking,data,feedback,media,anders',
            'message' => 'required|string|max:10000',
        ]);

        $submission = ContactSubmission::create([
            'organisation' => $validated['organisation'] ?? null,
            'full_name' => $validated['full-name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // Send notification email to admin if configured
        $notificationEmail = get_setting('contact_notification_email');
        if ($notificationEmail) {
            try {
                Mail::to($notificationEmail)->send(new ContactNotificationMail($submission));
            } catch (\Exception $e) {
                Log::error('Failed to send contact notification email', [
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Send auto-reply email to user if enabled
        if (get_setting('contact_auto_reply_enabled', '0') == '1') {
            try {
                Mail::to($submission->email)->send(new ContactAutoReplyMail($submission));
            } catch (\Exception $e) {
                Log::error('Failed to send contact auto-reply email', [
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Bedankt voor uw bericht! Wij nemen zo spoedig mogelijk contact met u op.');
    }

    /**
     * Store a new search subscription.
     */
    public function storeSubscription(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'frequency' => 'required|string|in:immediate,daily,weekly',
            'consent' => 'required|accepted',
            'search_query' => 'nullable|string|max:500',
            'filters' => 'nullable|string|max:5000',
        ]);

        // Parse filters JSON if provided
        $filters = [];
        if ($request->filled('filters')) {
            $filtersJson = $request->filters;
            $decodedFilters = json_decode($filtersJson, true);
            
            // If JSON decode succeeded, use decoded filters
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFilters)) {
                $filters = $decodedFilters;
            } else {
                \Log::warning('Failed to decode filters JSON', [
                    'filters_string' => $filtersJson,
                    'json_error' => json_last_error_msg(),
                ]);
            }
        }
        
        // Always merge with direct request parameters as fallback/override
        // This ensures we capture all filters even if JSON parsing fails
        foreach (['thema', 'organisatie', 'documentsoort', 'bestandstype', 'status', 'informatiecategorie', 'beschikbaarSinds', 'publicatiedatum_van', 'publicatiedatum_tot', 'titles_only', 'zoeken'] as $key) {
            $value = $request->input($key);
            if ($value !== null && $value !== '') {
                if (in_array($key, ['thema', 'organisatie', 'documentsoort', 'bestandstype'])) {
                    // Array filters - handle both array and single value
                    $values = is_array($value) ? $value : [$value];
                    $values = array_filter(array_map('trim', $values)); // Remove empty values
                    if (!empty($values)) {
                        // Merge with existing or set new
                        if (isset($filters[$key]) && is_array($filters[$key])) {
                            $filters[$key] = array_unique(array_merge($filters[$key], $values));
                        } else {
                            $filters[$key] = $values;
                        }
                    }
                } else {
                    // Single value filters
                    $filters[$key] = $value;
                }
            }
        }
        
        // Log for debugging
        \Log::info('Subscription filters', [
            'filters_json' => $request->filters,
            'parsed_filters' => $filters,
        ]);

        // Create subscription with verification token
        $subscription = SearchSubscription::createWithVerification([
            'email' => $validated['email'],
            'frequency' => $validated['frequency'],
            'search_query' => $validated['search_query'] ?? null,
            'filters' => $filters,
            'is_active' => false, // Will be activated after email verification
        ]);
        
        // Log for debugging
        \Log::info('Subscription created', [
            'subscription_id' => $subscription->id,
            'email' => $subscription->email,
            'filters' => $subscription->filters,
            'search_query' => $subscription->search_query,
        ]);

        // TODO: Send verification email to user
        // You can use Laravel's Mail facade or a notification
        // Mail::to($subscription->email)->send(new SubscriptionVerificationMail($subscription));

        // Redirect to search results page with current query parameters
        $redirectUrl = route('zoeken', request()->except(['_token', 'email', 'frequency', 'consent', 'search_query', 'filters']));
        
        return redirect($redirectUrl)
            ->with('success', 'Uw abonnement is succesvol aangemaakt! U ontvangt binnenkort een bevestigingsmail om uw abonnement te activeren.');
    }
}
