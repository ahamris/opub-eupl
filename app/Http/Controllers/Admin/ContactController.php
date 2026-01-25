<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');
        
        $query = Contact::query()->withCount(['submissions as unread_count' => function ($q) {
            $q->unread();
        }, 'submissions as active_count' => function ($q) {
            $q->active();
        }]);
        
        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('organisation', 'like', "%{$search}%");
            });
        }
        
        // Apply filters
        if ($filter === 'with_unread') {
            $query->withUnread();
        } elseif ($filter === 'active') {
            $query->active();
        } elseif ($filter === 'new') {
            $query->byStatus('new');
        } elseif ($filter === 'resolved') {
            $query->byStatus('resolved');
        } elseif ($filter === 'closed') {
            $query->byStatus('closed');
        }
        
        $contacts = $query->orderByDesc('last_contacted_at')
            ->orderByDesc('created_at')
            ->paginate(20);
        
        $counts = [
            'all' => Contact::count(),
            'with_unread' => Contact::withUnread()->count(),
            'active' => Contact::active()->count(),
            'new' => Contact::byStatus('new')->count(),
            'resolved' => Contact::byStatus('resolved')->count(),
            'closed' => Contact::byStatus('closed')->count(),
        ];
        
        return view('admin.contacts.index', compact('contacts', 'filter', 'search', 'counts'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create(): View
    {
        return view('admin.contacts.create');
    }

    /**
     * Store a newly created contact.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:contacts,email'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'organisation' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'in:new,active,pending,resolved,closed'],
            'priority' => ['nullable', 'string', 'in:low,normal,high,urgent'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $contact = Contact::create([
            'email' => $validated['email'],
            'full_name' => $validated['full_name'] ?? null,
            'organisation' => $validated['organisation'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'] ?? 'new',
            'priority' => $validated['priority'] ?? 'normal',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.contacts.show', $contact)
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact): View
    {
        $contact->load(['submissions' => function ($q) {
            $q->latest()->limit(10);
        }]);
        
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact): View
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:contacts,email,' . $contact->id],
            'full_name' => ['nullable', 'string', 'max:255'],
            'organisation' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'in:new,active,pending,resolved,closed'],
            'priority' => ['nullable', 'string', 'in:low,normal,high,urgent'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $contact->update($validated);

        return redirect()
            ->route('admin.contacts.show', $contact)
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}
