<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bestuursorgaan;
use App\Models\SearchSubscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthApiController extends Controller
{
    /**
     * Get current authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(null, 401);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'created_at' => $user->created_at,
        ]);
    }

    /**
     * Login.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Onjuist e-mailadres of wachtwoord.',
            ], 422);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'last_name' => Auth::user()->last_name,
                'email' => Auth::user()->email,
            ],
        ]);
    }

    /**
     * Register.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        Auth::login($user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /**
     * Logout.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['ok' => true]);
    }

    /**
     * Update profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Huidig wachtwoord is onjuist.'], 422);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return response()->json(['message' => 'Wachtwoord gewijzigd.']);
    }

    /**
     * List user's subscriptions.
     */
    public function subscriptions(Request $request): JsonResponse
    {
        $user = $request->user();

        $subscriptions = SearchSubscription::where('email', $user->email)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'search_query' => $s->search_query,
                'filters' => $s->filters,
                'formatted_filters' => $s->formatted_filters,
                'frequency' => $s->frequency,
                'frequency_label' => $s->frequency_label,
                'is_active' => $s->is_active,
                'is_verified' => $s->isVerified(),
                'created_at' => $s->created_at?->toISOString(),
                'last_sent_at' => $s->last_sent_at?->toISOString(),
            ]);

        return response()->json($subscriptions);
    }

    /**
     * Toggle subscription active state.
     */
    public function toggleSubscription(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $sub = SearchSubscription::where('id', $id)->where('email', $user->email)->first();

        if (! $sub) {
            return response()->json(['message' => 'Niet gevonden.'], 404);
        }

        $sub->update(['is_active' => ! $sub->is_active]);

        return response()->json(['is_active' => $sub->is_active]);
    }

    /**
     * Delete subscription.
     */
    public function deleteSubscription(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $deleted = SearchSubscription::where('id', $id)->where('email', $user->email)->delete();

        return response()->json(['deleted' => $deleted > 0]);
    }

    /**
     * Claim an organisation page.
     */
    public function claimOrganisation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bestuursorgaan_id' => ['required', 'integer', 'exists:bestuursorganen,id'],
        ]);

        $org = Bestuursorgaan::findOrFail($validated['bestuursorgaan_id']);

        if ($org->isClaimed()) {
            return response()->json(['message' => 'Deze organisatie is al geclaimd.'], 409);
        }

        $org->update([
            'claimed_by_user_id' => $request->user()->id,
            'claimed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Organisatie succesvol geclaimd.',
            'organisation' => [
                'id' => $org->id,
                'naam' => $org->naam,
                'slug' => $org->slug,
            ],
        ]);
    }

    /**
     * Update a claimed organisation page.
     */
    public function updateOrganisation(Request $request, int $id): JsonResponse
    {
        $org = Bestuursorgaan::findOrFail($id);

        if ($org->claimed_by_user_id !== $request->user()->id) {
            return response()->json(['message' => 'U bent niet gemachtigd.'], 403);
        }

        $validated = $request->validate([
            'custom_beschrijving' => ['nullable', 'string', 'max:5000'],
            'logo_url' => ['nullable', 'string', 'url', 'max:500'],
            'document_match_name' => ['nullable', 'string', 'max:255'],
        ]);

        $org->update($validated);

        return response()->json([
            'message' => 'Organisatie bijgewerkt.',
            'organisation' => [
                'id' => $org->id,
                'naam' => $org->naam,
                'custom_beschrijving' => $org->custom_beschrijving,
                'logo_url' => $org->logo_url,
            ],
        ]);
    }
}
