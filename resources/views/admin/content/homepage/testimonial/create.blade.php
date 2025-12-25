<x-layouts.admin title="Create Testimonial">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Testimonial</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new customer testimonial</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.content.homepage.testimonial.index') }}">Back</x-button>
        </div>

        <form action="{{ route('admin.content.homepage.testimonial.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Testimonial</h2>
                        
                        <x-ui.textarea 
                            label="Quote" 
                            name="quote" 
                            placeholder="What did the customer say?"
                            rows="4"
                            value="{{ old('quote') }}"
                            required
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <x-input 
                                label="Author Name" 
                                name="author" 
                                type="text" 
                                placeholder="Full name"
                                value="{{ old('author') }}"
                                required
                            />
                            <x-input 
                                label="Role/Title" 
                                name="role" 
                                type="text" 
                                placeholder="e.g. Journalist"
                                value="{{ old('role') }}"
                            />
                        </div>

                        <x-input 
                            label="Organization" 
                            name="organization" 
                            type="text" 
                            placeholder="Company or organization name"
                            value="{{ old('organization') }}"
                        />
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-3">Settings</h2>
                        
                        <x-ui.checkbox 
                            label="Active" 
                            name="is_active" 
                            value="1"
                            :checked="old('is_active', true)"
                            hint="Show this testimonial"
                        />

                        <!-- Star Rating -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Rating</label>
                            <div x-data="{ rating: {{ old('rating', 5) }} }" class="flex items-center gap-1">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button 
                                        type="button"
                                        @click="rating = star"
                                        class="p-1 transition hover:scale-110"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
                                            class="w-6 h-6 transition-colors"
                                            :class="star <= rating ? 'text-orange-500' : 'text-zinc-300 dark:text-zinc-600'"
                                        >
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </template>
                                <input type="hidden" name="rating" :value="rating">
                                <span class="ml-2 text-sm text-zinc-500" x-text="rating + '/5'"></span>
                            </div>
                        </div>

                        <x-input 
                            label="Sort Order"
                            name="sort_order" 
                            type="number" 
                            placeholder="0"
                            value="{{ old('sort_order', 0) }}"
                        />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <x-button variant="secondary" type="button" href="{{ route('admin.content.homepage.testimonial.index') }}">Cancel</x-button>
                <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Testimonial</x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
