@if(!empty($categories))
@once
    @push('styles')
        <style>[x-cloak]{display:none!important;}</style>
    @endpush

    @push('scripts')
        <script>
            // Ensure Alpine.js is loaded before registering
            (function() {
                function registerGdprBanner() {
                    const baseCategories = @json($categories);

                Alpine.data('gdprBanner', () => ({
                    visible: false,
                    showTrigger: false,
                    view: 'intro',
                    categories: JSON.parse(JSON.stringify(baseCategories)),
                    init() {
                        const stored = localStorage.getItem('gdprConsent');
                        if (stored) {
                            try {
                                const parsed = JSON.parse(stored);
                                this.categories = this.categories.map(category => ({
                                    ...category,
                                    enabled: parsed[category.key] ?? category.enabled,
                                }));
                                this.visible = false;
                                this.showTrigger = true;
                            } catch (error) {
                                this.visible = true;
                                this.showTrigger = false;
                            }
                        } else {
                            this.visible = true;
                            this.showTrigger = false;
                        }
                    },
                    persist() {
                        const consent = this.categories.reduce((acc, category) => {
                            acc[category.key] = category.enabled;
                            return acc;
                        }, {});
                        localStorage.setItem('gdprConsent', JSON.stringify(consent));
                        this.visible = false;
                        this.showTrigger = true;
                    },
                    acceptAll() {
                        this.categories = this.categories.map(category => ({ ...category, enabled: true }));
                        this.persist();
                    },
                    acceptEssential() {
                        this.categories = this.categories.map(category => ({ ...category, enabled: category.locked }));
                        const consent = this.categories.reduce((acc, category) => {
                            acc[category.key] = category.enabled;
                            return acc;
                        }, {});
                        localStorage.setItem('gdprConsent', JSON.stringify(consent));
                        this.view = 'preferences';
                    },
                    savePreferences() {
                        this.persist();
                    },
                    toggle(item) {
                        if (item.locked) return;
                        item.enabled = !item.enabled;
                    },
                    openPreferences() {
                        this.view = 'preferences';
                        this.visible = true;
                        this.showTrigger = false;
                    },
                    closeOverlay() {
                        this.visible = false;
                        this.view = 'intro';
                        this.showTrigger = true;
                    }
                }));
            }
            
            // Try to register immediately if Alpine is available
            if (typeof Alpine !== 'undefined' && window.Alpine) {
                registerGdprBanner();
            } else {
                // Wait for Alpine to load
                document.addEventListener('alpine:init', registerGdprBanner);
            }
        })();
        </script>
    @endpush
@endonce

{{-- Debug: Check if component is rendering --}}
@if(config('app.debug'))
    <!-- GDPR Banner Component Loaded - Categories: {{ count($categories) }} -->
@endif

<div x-data="gdprBanner" x-cloak>
    <div
        x-show="visible"
        class="fixed inset-0 z-[9999] grid place-items-center bg-slate-900/60 backdrop-blur-sm p-4"
    >
    <template x-if="view === 'intro'">
        <div class="w-full max-w-lg rounded-md border bg-white shadow-sm" style="border-color: rgba(7, 41, 116, 0.15);">
            <div class="flex items-center gap-3 border-b px-6 py-5" style="border-color: rgba(7, 41, 116, 0.1);">
                <div class="flex h-12 w-12 items-center justify-center rounded-full" style="background-color: rgba(7, 41, 116, 0.1);">
                    <i class="fa-solid fa-cookie-bite text-xl" style="color: var(--color-primary-dark);"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide font-semibold" style="color: rgba(7, 41, 116, 0.7);">Cookies & privacy</p>
                    <p class="text-xl font-semibold" style="color: var(--color-primary-dark);">{{ $introTitle }}</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4 text-slate-700 text-sm leading-6">
                <p>
                    {{ $introSummary }}
                </p>
                <p>
                    Read more in our
                    <a x-ref="settingsLink" href="{{ $settingsUrl }}" class="underline underline-offset-4" style="color: var(--color-secondary)">
                        {{ $settingsLabel }}
                    </a>.
                </p>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-end">
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md border px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white"
                    style="border-color: rgba(7, 41, 116, 0.2); color: var(--color-primary-dark); background-color: rgba(7, 41, 116, 0.05); --tw-ring-color: rgba(7, 41, 116, 0.3);"
                    @mouseenter="$el.style.backgroundColor = 'rgba(7, 41, 116, 0.1)'"
                    @mouseleave="$el.style.backgroundColor = 'rgba(7, 41, 116, 0.05)'"
                    @click="openPreferences()"
                >
                    Manage preferences
                </button>
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md px-4 py-2 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white"
                    style="background-color: var(--color-primary-dark); --tw-ring-color: rgba(7, 41, 116, 0.4);"
                    @click="acceptAll()"
                >
                    Accept all
                </button>
            </div>
        </div>
    </template>

    <template x-if="view === 'preferences'">
        <div class="w-full max-w-lg rounded-md border bg-white shadow-sm" style="border-color: rgba(7, 41, 116, 0.15);">
            <div class="flex items-center gap-3 border-b px-6 py-5" style="border-color: rgba(7, 41, 116, 0.1);">
                <button
                    type="button"
                    class="flex h-8 w-8 items-center justify-center rounded-full border transition"
                    style="border-color: rgba(7, 41, 116, 0.2); color: var(--color-primary-dark);"
                    @mouseenter="$el.style.backgroundColor = 'rgba(7, 41, 116, 0.05)'"
                    @mouseleave="$el.style.backgroundColor = 'transparent'"
                    @click="view = 'intro'"
                    aria-label="Back"
                >
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <div>
                    <p class="text-xs uppercase tracking-wide font-semibold" style="color: rgba(7, 41, 116, 0.7);">Preferences</p>
                    <p class="text-xl font-semibold" style="color: var(--color-primary-dark);">{{ $preferencesTitle }}</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4 text-slate-700 text-sm leading-6">
                <p>
                    {{ $preferencesSummary }}
                    <a :href="$refs.settingsLink?.href ?? '{{ $settingsUrl }}'" class="underline underline-offset-4" style="color: var(--color-secondary)">
                        {{ $settingsLabel }}
                    </a>.
                </p>

                <div class="space-y-4">
                    <template x-for="item in categories" :key="item.key">
                        <div class="rounded-md border px-4 py-3" style="border-color: rgba(7, 41, 116, 0.1); background-color: rgba(7, 41, 116, 0.05);">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold" style="color: var(--color-primary-dark);" x-text="item.label"></p>
                                    <p class="mt-1 text-xs leading-5 text-slate-600" x-text="item.description"></p>
                                </div>
                                <button
                                    type="button"
                                    class="relative inline-flex h-6 w-11 shrink-0 rounded-full border-2 border-transparent transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white"
                                    :style="item.enabled ? 'background-color: var(--color-primary-dark);' : 'background-color: rgb(226 232 240);'"
                                    style="--tw-ring-color: rgba(7, 41, 116, 0.4);"
                                    @click="toggle(item)"
                                    :disabled="item.locked"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200"
                                        :class="item.enabled ? 'translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-end">
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md border px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white"
                    style="border-color: rgba(7, 41, 116, 0.2); color: var(--color-primary-dark); background-color: rgba(7, 41, 116, 0.05); --tw-ring-color: rgba(7, 41, 116, 0.3);"
                    @mouseenter="$el.style.backgroundColor = 'rgba(7, 41, 116, 0.1)'"
                    @mouseleave="$el.style.backgroundColor = 'rgba(7, 41, 116, 0.05)'"
                    @click="acceptEssential()"
                >
                    Functional only
                </button>
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md px-4 py-2 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white"
                    style="background-color: var(--color-primary-dark); --tw-ring-color: rgba(7, 41, 116, 0.4);"
                    @click="savePreferences()"
                >
                    Save preferences
                </button>
            </div>
        </div>
    </template>
    </div>

    <div
        x-show="showTrigger"
        x-transition.opacity
        class="fixed bottom-6 left-6 z-[9998]"
    >
    <button
        type="button"
        class="flex items-center gap-2 rounded-full border border-white bg-white px-3 py-3 text-lg font-semibold transition-shadow duration-200"
        style="color: var(--color-primary-dark); box-shadow: 0 4px 6px -1px rgba(7, 41, 116, 0.2), 0 2px 4px -1px rgba(7, 41, 116, 0.1);"
        @mouseenter="$el.style.boxShadow = '0 10px 15px -3px rgba(7, 41, 116, 0.4), 0 4px 6px -2px rgba(7, 41, 116, 0.2)'"
        @mouseleave="$el.style.boxShadow = '0 4px 6px -1px rgba(7, 41, 116, 0.2), 0 2px 4px -1px rgba(7, 41, 116, 0.1)'"
        @click="openPreferences()"
    >
        <i class="fa-solid fa-cookie-bite" style="color: var(--color-primary-dark);"></i>
    </button>
    </div>
</div>
@endif
