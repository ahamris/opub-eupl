<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\SearchSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchSubscriptionController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.search-subscriptions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchSubscription $searchSubscription): View
    {
        return view('admin.search-subscriptions.show', compact('searchSubscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchSubscription $searchSubscription): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => 'sometimes|boolean',
            'frequency' => 'sometimes|in:immediate,daily,weekly',
        ]);

        $searchSubscription->update($validated);

        return redirect()
            ->route('admin.search-subscriptions.index')
            ->with('success', 'Abonnement bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchSubscription $searchSubscription): RedirectResponse
    {
        $searchSubscription->delete();

        return redirect()
            ->route('admin.search-subscriptions.index')
            ->with('success', 'Abonnement verwijderd.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(SearchSubscription $searchSubscription): RedirectResponse
    {
        $searchSubscription->update([
            'is_active' => !$searchSubscription->is_active,
        ]);

        return redirect()
            ->route('admin.search-subscriptions.index')
            ->with('success', 'Abonnement status bijgewerkt.');
    }
}
