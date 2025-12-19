<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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

        ContactSubmission::create([
            'organisation' => $validated['organisation'] ?? null,
            'full_name' => $validated['full-name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        return redirect()
            ->route('contact')
            ->with('success', 'Bedankt voor uw bericht! Wij nemen zo spoedig mogelijk contact met u op.');
    }
}
