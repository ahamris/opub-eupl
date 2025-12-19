<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSubmissionController extends AdminBaseController
{
    /**
     * Display a listing of contact submissions.
     */
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'all');
        
        $query = ContactSubmission::query()->latest();
        
        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'archived') {
            $query->archived();
        } elseif ($filter === 'active') {
            $query->active();
        }
        
        $submissions = $query->paginate(15);
        
        $counts = [
            'all' => ContactSubmission::count(),
            'unread' => ContactSubmission::unread()->count(),
            'active' => ContactSubmission::active()->count(),
            'archived' => ContactSubmission::archived()->count(),
        ];
        
        return view('admin.contact-submissions.index', compact('submissions', 'filter', 'counts'));
    }

    /**
     * Display the specified contact submission.
     */
    public function show(ContactSubmission $contactSubmission): View
    {
        // Mark as read when viewing
        $contactSubmission->markAsRead();
        
        return view('admin.contact-submissions.show', compact('contactSubmission'));
    }

    /**
     * Update the notes for a submission.
     */
    public function update(Request $request, ContactSubmission $contactSubmission): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:5000',
        ]);
        
        $contactSubmission->update($validated);
        
        return redirect()
            ->route('admin.contact-submissions.show', $contactSubmission)
            ->with('success', 'Notes updated successfully.');
    }

    /**
     * Toggle read status.
     */
    public function toggleRead(ContactSubmission $contactSubmission): RedirectResponse
    {
        if ($contactSubmission->is_read) {
            $contactSubmission->markAsUnread();
            $message = 'Message marked as unread.';
        } else {
            $contactSubmission->markAsRead();
            $message = 'Message marked as read.';
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Toggle archive status.
     */
    public function toggleArchive(ContactSubmission $contactSubmission): RedirectResponse
    {
        if ($contactSubmission->is_archived) {
            $contactSubmission->unarchive();
            $message = 'Message restored from archive.';
        } else {
            $contactSubmission->archive();
            $message = 'Message archived successfully.';
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified contact submission.
     */
    public function destroy(ContactSubmission $contactSubmission): RedirectResponse
    {
        $contactSubmission->delete();

        return redirect()
            ->route('admin.contact-submissions.index')
            ->with('success', 'Message permanently deleted.');
    }
}
