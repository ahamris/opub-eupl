<div class="space-y-4">
    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-1">Theme Settings</h1>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Customize your admin panel's appearance and colors.</p>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Left Column: Theme Configuration --}}
        <div class="space-y-4">
            <x-ui.card>
                <x-slot:header>
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Theme Configuration</h2>
                </x-slot:header>
                <x-slot:body>
                    <form wire:submit="save" class="space-y-6">
                        {{-- Accent Color Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-3">
                                Accent Color
                                <span class="text-xs font-normal text-zinc-500 dark:text-zinc-400 ml-1">Choose your primary accent color</span>
                            </label>
                            <div class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-8 gap-2">
                                @foreach($this->availableAccentColors as $value => $label)
                                    <label class="relative cursor-pointer group">
                                        <input 
                                            type="radio" 
                                            name="accentColor" 
                                            value="{{ $value }}" 
                                            wire:model.live="accentColor"
                                            class="sr-only peer"
                                        >
                                        <div class="relative flex flex-col items-center p-2 rounded-md transition-all duration-200 
                                            hover:scale-105
                                            bg-white dark:bg-zinc-800
                                            peer-checked:ring-2 peer-checked:ring-[var(--color-accent)] dark:peer-checked:ring-[var(--color-accent-content)]
                                            peer-checked:ring-offset-2 peer-checked:ring-offset-white dark:peer-checked:ring-offset-zinc-800
                                            shadow-sm hover:shadow-md peer-checked:shadow-lg">
                                            <div class="w-7 h-7 rounded mb-1.5 shadow-sm
                                                @if($value === 'base') bg-zinc-900 @endif
                                                @if($value === 'sky') bg-sky-600 @endif
                                                @if($value === 'indigo') bg-indigo-600 @endif
                                                @if($value === 'blue') bg-blue-600 @endif
                                                @if($value === 'green') bg-green-600 @endif
                                                @if($value === 'emerald') bg-emerald-600 @endif
                                                @if($value === 'teal') bg-teal-600 @endif
                                                @if($value === 'cyan') bg-cyan-600 @endif
                                                @if($value === 'purple') bg-purple-600 @endif
                                                @if($value === 'violet') bg-violet-600 @endif
                                                @if($value === 'fuchsia') bg-fuchsia-600 @endif
                                                @if($value === 'pink') bg-pink-600 @endif
                                                @if($value === 'rose') bg-rose-600 @endif
                                                @if($value === 'red') bg-red-600 @endif
                                                @if($value === 'orange') bg-orange-600 @endif
                                                @if($value === 'amber') bg-amber-600 @endif
                                                @if($value === 'yellow') bg-yellow-600 @endif
                                                @if($value === 'lime') bg-lime-600 @endif
                                            ">
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                                    <i class="fa-solid fa-check text-white text-xs drop-shadow-lg"></i>
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-medium text-zinc-700 dark:text-zinc-300 text-center leading-tight">{{ $label }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Base Color Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-3">
                                Base Color
                                <span class="text-xs font-normal text-zinc-500 dark:text-zinc-400 ml-1">Choose your base neutral color</span>
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($this->availableBaseColors as $value => $label)
                                    <label class="relative cursor-pointer group">
                                        <input 
                                            type="radio" 
                                            name="baseColor" 
                                            value="{{ $value }}" 
                                            wire:model.live="baseColor"
                                            class="sr-only peer"
                                        >
                                        <div class="relative flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200 
                                            hover:scale-105
                                            bg-white dark:bg-zinc-800
                                            peer-checked:bg-[var(--color-accent)]/10 dark:peer-checked:bg-[var(--color-accent)]/20
                                            peer-checked:ring-2 peer-checked:ring-[var(--color-accent)] dark:peer-checked:ring-[var(--color-accent-content)]
                                            shadow-sm hover:shadow-md peer-checked:shadow-lg
                                            border border-zinc-200 dark:border-zinc-700 peer-checked:border-[var(--color-accent)] dark:peer-checked:border-[var(--color-accent-content)]">
                                            <div class="w-6 h-6 rounded shadow-sm flex-shrink-0
                                                @if($value === 'zinc') bg-gradient-to-br from-zinc-100 to-zinc-900 @endif
                                                @if($value === 'stone') bg-gradient-to-br from-stone-100 to-stone-900 @endif
                                                @if($value === 'slate') bg-gradient-to-br from-slate-100 to-slate-900 @endif
                                                @if($value === 'gray') bg-gradient-to-br from-gray-100 to-gray-900 @endif
                                                @if($value === 'neutral') bg-gradient-to-br from-neutral-100 to-neutral-900 @endif
                                            "></div>
                                            <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300 peer-checked:text-[var(--color-accent)] dark:peer-checked:text-[var(--color-accent-content)]">{{ $label }}</span>
                                            <i class="fa-solid fa-check text-[var(--color-accent)] dark:text-[var(--color-accent-content)] opacity-0 peer-checked:opacity-100 transition-opacity text-xs ml-auto"></i>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Save Button --}}
                        <div class="flex justify-end pt-3 border-t border-zinc-200 dark:border-zinc-700">
                            <x-button variant="primary" type="submit" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">
                                    <i class="fa-solid fa-save mr-2"></i> Save Theme Settings
                                </span>
                                <span wire:loading wire:target="save">
                                    <i class="fa-solid fa-spinner mr-2 animate-spin"></i> Saving...
                                </span>
                            </x-button>
                        </div>
                    </form>
                </x-slot:body>
            </x-ui.card>
        </div>

        {{-- Right Column: Live Preview --}}
        <div class="space-y-4">
            <x-ui.card>
                <x-slot:header>
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-white">Live Preview</h2>
                </x-slot:header>
                <x-slot:body>
                    {{-- UI Component Examples Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Buttons --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-md border border-zinc-200 dark:border-zinc-700 p-3">
                            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Buttons</h3>
                            <div class="space-y-2">
                                <x-button variant="primary" size="sm" class="w-full text-xs">Primary</x-button>
                                <x-button variant="secondary" size="sm" class="w-full text-xs">Secondary</x-button>
                                <x-button variant="outline-primary" size="sm" class="w-full text-xs">Outline</x-button>
                            </div>
                        </div>

                        {{-- Badges --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-md border border-zinc-200 dark:border-zinc-700 p-3">
                            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Badges</h3>
                            <div class="space-y-1.5">
                                <div class="flex flex-wrap gap-1">
                                    <x-badge variant="primary" class="text-xs">Primary</x-badge>
                                    <x-badge variant="success" class="text-xs">Success</x-badge>
                                    <x-badge variant="warning" class="text-xs">Warning</x-badge>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <x-badge variant="primary" icon="check" class="text-xs">With Icon</x-badge>
                                    <x-badge variant="error" icon="exclamation" class="text-xs">Error</x-badge>
                                </div>
                            </div>
                        </div>

                        {{-- Alerts --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-md border border-zinc-200 dark:border-zinc-700 p-3">
                            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Alerts</h3>
                            <div class="space-y-1.5">
                                <x-ui.alert variant="primary" icon="info-circle" title="Primary" message="" class="text-xs" />
                                <x-ui.alert variant="success" icon="check-circle" title="Success" message="" class="text-xs" />
                            </div>
                        </div>

                        {{-- Inputs --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-md border border-zinc-200 dark:border-zinc-700 p-3">
                            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Inputs</h3>
                            <div class="space-y-2">
                                <x-input name="preview_text" placeholder="Text..." size="sm" class="text-xs" />
                                <x-input name="preview_icon" icon="search" placeholder="Search..." size="sm" class="text-xs" />
                            </div>
                        </div>

                        {{-- Checkboxes --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-md border border-zinc-200 dark:border-zinc-700 p-3">
                            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Checkboxes</h3>
                            <div class="space-y-1.5">
                                <x-checkbox name="preview_cb1" label="Primary" color="primary" checked class="text-xs" />
                                <x-checkbox name="preview_cb2" label="Success" color="success" class="text-xs" />
                            </div>
                        </div>

                        {{-- Cards --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-md border border-zinc-200 dark:border-zinc-700 p-3">
                            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Cards</h3>
                            <x-ui.card 
                                icon="users" 
                                icon-color="primary" 
                                title="Users" 
                                value="1,234" 
                                size="sm"
                                :hover="false"
                            />
                        </div>
                    </div>
                </x-slot:body>
            </x-ui.card>

            {{-- Information Card --}}
            <x-ui.card variant="filled" size="sm">
                <x-slot:body>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-lg bg-[var(--color-accent)]/10 dark:bg-[var(--color-accent)]/20 flex items-center justify-center">
                                <i class="fa-solid fa-info-circle text-[var(--color-accent)] dark:text-[var(--color-accent-content)] text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-1">About Theme System</h3>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400">
                                This theme system uses CSS variables for dynamic theming. Changes are applied instantly and persist across sessions. Use <code class="text-[10px] bg-zinc-100 dark:bg-zinc-800 px-1 py-0.5 rounded">var(--color-accent)</code> in your custom CSS.
                            </p>
                        </div>
                    </div>
                </x-slot:body>
            </x-ui.card>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('theme-updated', () => {
        // Reload the page to apply new theme variables
        setTimeout(() => {
            window.location.reload();
        }, 500);
    });
</script>
@endscript
