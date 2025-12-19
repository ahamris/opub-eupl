<x-layouts.admin title="Message Details">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Message Details</h1>
                <p class="text-zinc-600 dark:text-zinc-400">View contact message and manage response</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.contact-submissions.index') }}">Back to Messages</x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Message Card -->
                <x-ui.card >
                    <x-slot:header>
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-3">
                                <x-badge variant="primary">{{ $contactSubmission->subject_label }}</x-badge>
                                @if(!$contactSubmission->is_read)
                                    <x-badge variant="error" icon="envelope">Unread</x-badge>
                                @else
                                    <x-badge variant="success" icon="check">Read</x-badge>
                                @endif
                                @if($contactSubmission->is_archived)
                                    <x-badge variant="warning" icon="archive">Archived</x-badge>
                                @endif
                            </div>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $contactSubmission->created_at->format('d-m-Y H:i') }}
                            </span>
                        </div>
                    </x-slot:header>
                    
                    <div class="prose prose-zinc dark:prose-invert max-w-none">
                        <p class="whitespace-pre-wrap text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $contactSubmission->message }}</p>
                    </div>
                </x-ui.card>
                
                <!-- Notes Section -->
                <x-ui.card >
                    <x-slot:header>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-sticky-note text-[var(--color-accent)]"></i>
                            Internal Notes
                        </h3>
                    </x-slot:header>
                    
                    <form action="{{ route('admin.contact-submissions.update', $contactSubmission) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <x-ui.textarea 
                            name="notes" 
                            rows="4"
                            placeholder="Add internal notes about this message..."
                            :value="old('notes', $contactSubmission->notes)"
                        />
                        
                        <div class="mt-4 flex justify-end">
                            <x-button variant="primary" icon="save" icon-position="left" type="submit">Save Notes</x-button>
                        </div>
                    </form>
                </x-ui.card>

                <!-- Actions Card -->
                <x-ui.card >
                    <x-slot:header>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-cog text-[var(--color-accent)]"></i>
                            Actions
                        </h3>
                    </x-slot:header>
                    
                    <div class="flex flex-wrap items-center gap-3" x-data="{ showDeleteModal: false }">
                        <!-- Toggle Read Status -->
                        <form action="{{ route('admin.contact-submissions.toggle-read', $contactSubmission) }}" method="POST" class="inline">
                            @csrf
                            <x-button 
                                variant="{{ $contactSubmission->is_read ? 'secondary' : 'primary' }}" 
                                icon="{{ $contactSubmission->is_read ? 'envelope' : 'envelope-open' }}" 
                                icon-position="left" 
                                type="submit"
                            >{{ $contactSubmission->is_read ? 'Mark as Unread' : 'Mark as Read' }}</x-button>
                        </form>
                        
                        <!-- Toggle Archive Status -->
                        <form action="{{ route('admin.contact-submissions.toggle-archive', $contactSubmission) }}" method="POST" class="inline">
                            @csrf
                            <x-button 
                                variant="{{ $contactSubmission->is_archived ? 'warning' : 'secondary' }}" 
                                icon="archive" 
                                icon-position="left" 
                                type="submit"
                            >{{ $contactSubmission->is_archived ? 'Restore from Archive' : 'Archive' }}</x-button>
                        </form>
                        
                        <!-- Delete Button -->
                        <x-button 
                            variant="error" 
                            icon="trash" 
                            icon-position="left" 
                            type="button"
                            x-on:click="showDeleteModal = true"
                        >Delete</x-button>
                        
                        <!-- Delete Modal -->
                        <x-ui.modal alpine-show="showDeleteModal" size="sm">
                            <x-slot:title>Delete Message</x-slot:title>
                            <p class="text-zinc-600 dark:text-zinc-400">Are you sure you want to permanently delete this message from <strong>{{ $contactSubmission->full_name }}</strong>? This action cannot be undone.</p>
                            <x-slot:footer>
                                <x-button variant="secondary" x-on:click="showDeleteModal = false">Cancel</x-button>
                                <form action="{{ route('admin.contact-submissions.destroy', $contactSubmission) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="primary" color="red" type="submit">Delete</x-button>
                                </form>
                            </x-slot:footer>
                        </x-ui.modal>
                    </div>
                </x-ui.card>
            </div>

            <!-- Sidebar - Contact Info -->
            <div class="space-y-6">
                <x-ui.card >
                    <x-slot:header>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-user text-[var(--color-accent)]"></i>
                            Contact Details
                        </h3>
                    </x-slot:header>
                    
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Name</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $contactSubmission->full_name }}</p>
                        </div>
                        
                        <!-- Organisation -->
                        @if($contactSubmission->organisation)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Organisation</label>
                                <p class="text-zinc-900 dark:text-white mt-1">{{ $contactSubmission->organisation }}</p>
                            </div>
                        @endif
                        
                        <!-- Email -->
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Email Address</label>
                            <p class="mt-1">
                                <a href="mailto:{{ $contactSubmission->email }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $contactSubmission->email }}
                                </a>
                            </p>
                        </div>
                        
                        <!-- Phone -->
                        @if($contactSubmission->phone)
                            <div>
                                <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Phone Number</label>
                                <p class="mt-1">
                                    <a href="tel:{{ $contactSubmission->phone }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $contactSubmission->phone }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
                
                <!-- Timestamps Card -->
                <x-ui.card >
                    <x-slot:header>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-clock text-[var(--color-accent)]"></i>
                            Timeline
                        </h3>
                    </x-slot:header>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Received On</label>
                            <p class="text-zinc-900 dark:text-white mt-1">{{ $contactSubmission->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Relative Time</label>
                            <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ $contactSubmission->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </x-ui.card>
                
                <!-- Quick Actions -->
                <x-ui.card >
                    <x-slot:header>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-bolt text-[var(--color-accent)]"></i>
                            Quick Actions
                        </h3>
                    </x-slot:header>
                    
                    <div class="space-y-2">
                        <x-button variant="secondary" icon="reply" icon-position="left" href="mailto:{{ $contactSubmission->email }}" class="w-full justify-start">
                            Reply via Email
                        </x-button>
                        
                        @if($contactSubmission->phone)
                            <x-button variant="secondary" icon="phone" icon-position="left" href="tel:{{ $contactSubmission->phone }}" class="w-full justify-start">
                                Call Back
                            </x-button>
                        @endif
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-layouts.admin>
