<?php

namespace App\Livewire\Admin;

use App\Models\ContactSubmission;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ContactSubmissionsTable extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'filter')]
    public string $filter = 'unread';

    #[Url(as: 'sort')]
    public string $sortField = 'created_at';

    #[Url(as: 'direction')]
    public string $sortDirection = 'desc';

    public array $selected = [];
    public bool $selectAll = false;

    public array $columns = [
        ['key' => 'id', 'label' => 'ID', 'sortable' => true],
        ['key' => 'full_name', 'label' => 'Name', 'sortable' => true],
        ['key' => 'email', 'label' => 'Email', 'sortable' => true],
        ['key' => 'subject', 'label' => 'Subject', 'sortable' => true],
        ['key' => 'is_read', 'label' => 'Status', 'type' => 'status'],
        ['key' => 'created_at', 'label' => 'Received', 'sortable' => true, 'format' => 'date'],
    ];

    #[Computed]
    public function items()
    {
        $query = ContactSubmission::query();

        // Apply filter
        if ($this->filter === 'unread') {
            $query->where('is_read', false)->where('is_archived', false);
        } elseif ($this->filter === 'read') {
            $query->where('is_read', true)->where('is_archived', false);
        } elseif ($this->filter === 'archived') {
            $query->where('is_archived', true);
        }

        // Apply search
        if ($this->search) {
            $query->where(function (Builder $q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('organisation', 'like', "%{$this->search}%")
                  ->orWhere('message', 'like', "%{$this->search}%");
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate(15);
    }

    #[Computed]
    public function counts()
    {
        return [
            'unread' => ContactSubmission::where('is_read', false)->where('is_archived', false)->count(),
            'read' => ContactSubmission::where('is_read', true)->where('is_archived', false)->count(),
            'archived' => ContactSubmission::where('is_archived', true)->count(),
        ];
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
        $this->selected = [];
        $this->selectAll = false;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selected = $this->items->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected(): void
    {
        $this->selectAll = count($this->selected) > 0 && count($this->selected) === $this->items->count();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Bulk Actions
    public function markSelectedAsRead(): void
    {
        if (empty($this->selected)) return;

        ContactSubmission::whereIn('id', $this->selected)->update(['is_read' => true]);
        
        $count = count($this->selected);
        $this->selected = [];
        $this->selectAll = false;
        
        $this->dispatch('notify', type: 'success', message: "{$count} message(s) marked as read.");
    }

    public function markSelectedAsUnread(): void
    {
        if (empty($this->selected)) return;

        ContactSubmission::whereIn('id', $this->selected)->update(['is_read' => false]);
        
        $count = count($this->selected);
        $this->selected = [];
        $this->selectAll = false;
        
        $this->dispatch('notify', type: 'success', message: "{$count} message(s) marked as unread.");
    }

    public function archiveSelected(): void
    {
        if (empty($this->selected)) return;

        ContactSubmission::whereIn('id', $this->selected)->update(['is_archived' => true]);
        
        $count = count($this->selected);
        $this->selected = [];
        $this->selectAll = false;
        
        $this->dispatch('notify', type: 'success', message: "{$count} message(s) archived.");
    }

    public function unarchiveSelected(): void
    {
        if (empty($this->selected)) return;

        ContactSubmission::whereIn('id', $this->selected)->update(['is_archived' => false]);
        
        $count = count($this->selected);
        $this->selected = [];
        $this->selectAll = false;
        
        $this->dispatch('notify', type: 'success', message: "{$count} message(s) restored from archive.");
    }

    public function deleteSelected(): void
    {
        if (empty($this->selected)) return;

        ContactSubmission::whereIn('id', $this->selected)->delete();
        
        $count = count($this->selected);
        $this->selected = [];
        $this->selectAll = false;
        
        $this->dispatch('notify', type: 'success', message: "{$count} message(s) permanently deleted.");
    }

    // Single item actions
    public function toggleRead(int $id): void
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->update(['is_read' => !$submission->is_read]);
        
        $status = $submission->is_read ? 'read' : 'unread';
        $this->dispatch('notify', type: 'success', message: "Message marked as {$status}.");
    }

    public function toggleArchive(int $id): void
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->update(['is_archived' => !$submission->is_archived]);
        
        $status = $submission->is_archived ? 'archived' : 'restored';
        $this->dispatch('notify', type: 'success', message: "Message {$status}.");
    }

    public function delete(int $id): void
    {
        ContactSubmission::findOrFail($id)->delete();
        $this->selected = array_filter($this->selected, fn ($item) => $item !== $id);
        
        $this->dispatch('notify', type: 'success', message: 'Message permanently deleted.');
    }

    public function render(): View
    {
        return view('livewire.admin.contact-submissions-table');
    }
}
