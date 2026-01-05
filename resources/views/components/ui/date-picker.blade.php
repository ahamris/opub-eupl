@once
    @push('styles')
        <style>
            .flatpickr-day.selected,
            .flatpickr-day.startRange,
            .flatpickr-day.endRange {
                background-color: var(--color-accent) !important;
                @apply text-white;
            }
            .flatpickr-day.selected:hover,
            .flatpickr-day.startRange:hover,
            .flatpickr-day.endRange:hover {
                background-color: var(--color-accent) !important;
                @apply opacity-90;
            }
            .flatpickr-day.inRange {
                background-color: color-mix(in srgb, var(--color-accent) 20%, transparent) !important;
            }
            .flatpickr-day.today {
                @apply border border-[var(--color-accent)];
            }
        </style>
    @endpush
@endonce

<div>
    @if($label)
        <label for="{{ $inputId }}" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="relative {{ $label ? 'mt-2' : '' }}">
        @if(!empty($icon) && $iconPosition === 'left')
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 pointer-events-none z-10">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
            <input
                    type="text"
                    name="{{ $name }}"
                    id="{{ $inputId }}"
                    value="{{ $value ?? '' }}"
                    placeholder="{{ $placeholder }}"
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                    @if($readonly) readonly @endif
                    class="{{ $classes }}"
                    data-flatpickr
                    data-theme="{{ $theme ?? 'auto' }}"
                    data-locale="{{ $locale ?? '' }}"
                    data-options="{{ json_encode($flatpickrOptions) }}"
                    {{ $attributes->except(['class']) }}
            >
        @elseif(!empty($icon) && $iconPosition === 'right')
            <input
                    type="text"
                    name="{{ $name }}"
                    id="{{ $inputId }}"
                    value="{{ $value ?? '' }}"
                    placeholder="{{ $placeholder }}"
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                    @if($readonly) readonly @endif
                    class="{{ $classes }}"
                    data-flatpickr
                    data-theme="{{ $theme ?? 'auto' }}"
                    data-locale="{{ $locale ?? '' }}"
                    data-options="{{ json_encode($flatpickrOptions) }}"
                    {{ $attributes->except(['class']) }}
            >
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 pointer-events-none z-10">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
        @else
            <input
                    type="text"
                    name="{{ $name }}"
                    id="{{ $inputId }}"
                    value="{{ $value ?? '' }}"
                    placeholder="{{ $placeholder }}"
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                    @if($readonly) readonly @endif
                    class="{{ $classes }}"
                    data-flatpickr
                    data-theme="{{ $theme ?? 'auto' }}"
                    data-locale="{{ $locale ?? '' }}"
                    data-options="{{ json_encode($flatpickrOptions) }}"
                    {{ $attributes->except(['class']) }}
            >
        @endif
    </div>

    @if($hint && !$error)
        <div class="text-xs leading-4 tracking-[0.4px] text-gray-600 dark:text-gray-400 mt-1.5">{{ $hint }}</div>
    @endif

    @if($error && $errorMessage)
        <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif
</div>

@once
    @push('scripts')
        <script>
            (function() {
                // Check if already initialized
                if (window._flatpickrUIIInitialized) {
                    return;
                }
                window._flatpickrUIIInitialized = true;

                function isDarkMode() {
                    return document.documentElement.classList.contains('dark');
                }

                function getTheme(element) {
                    const themeAttr = element.getAttribute('data-theme');
                    if (!themeAttr || themeAttr === 'auto') {
                        return isDarkMode() ? 'dark' : 'light';
                    }
                    return themeAttr;
                }

                const availableThemes = ['dark', 'light', 'material_blue', 'material_green', 'material_orange', 'material_red', 'airbnb', 'confetti'];

                function applyFlatpickrTheme(instance, element) {
                    if (!instance || !element) return;

                    const theme = getTheme(element);

                    // Load theme CSS dynamically (only the needed theme)
                    // Flatpickr themes work by CSS file loading, not by class
                    if (window.loadFlatpickrTheme) {
                        window.loadFlatpickrTheme(theme);
                    }
                }

                function initFlatpickr() {
                    if (!window.flatpickr) {
                        setTimeout(initFlatpickr, 100);
                        return;
                    }

                    document.querySelectorAll('[data-flatpickr]:not(.flatpickr-input)').forEach(function(element) {
                        try {
                            // Skip if already initialized
                            if (element._flatpickrInstance || element._flatpickr) {
                                return;
                            }

                            // Skip initialization if input is readonly or disabled
                            if (element.hasAttribute('readonly') || element.hasAttribute('disabled')) {
                                return;
                            }

                            // Load theme before initializing
                            const theme = getTheme(element);
                            if (window.loadFlatpickrTheme) {
                                window.loadFlatpickrTheme(theme);
                            }

                            // Load locale if specified
                            const localeAttr = element.getAttribute('data-locale');
                            if (localeAttr && window.loadFlatpickrLocale) {
                                window.loadFlatpickrLocale(localeAttr);
                            }

                            const optionsJson = element.getAttribute('data-options');
                            const options = optionsJson ? JSON.parse(optionsJson) : {};

                            // Handle locale in options
                            if (localeAttr && options.locale) {
                                // Wait for locale to load if needed
                                const localeId = `flatpickr-locale-${localeAttr}`;
                                const localeScript = document.getElementById(localeId);

                                if (localeScript && !localeScript.dataset.loaded) {
                                    localeScript.addEventListener('load', function() {
                                        localeScript.dataset.loaded = 'true';
                                        initializeFlatpickrInstance(element, options, theme);
                                    });
                                    return; // Will initialize after locale loads
                                }
                            }

                            initializeFlatpickrInstance(element, options, theme);
                        } catch (e) {
                            console.error('Error initializing Flatpickr:', e);
                        }
                    });

                    function initializeFlatpickrInstance(element, options, theme) {
                        const originalOnReady = options.onReady;
                        const originalOnOpen = options.onOpen;

                        options.onReady = function(selectedDates, dateStr, instance) {
                            // Apply theme after calendar is ready
                            setTimeout(function() {
                                applyFlatpickrTheme(instance, element);
                            }, 10);

                            if (originalOnReady) {
                                if (typeof originalOnReady === 'function') {
                                    originalOnReady(selectedDates, dateStr, instance);
                                } else if (Array.isArray(originalOnReady)) {
                                    originalOnReady.forEach(function(cb) {
                                        if (typeof cb === 'function') {
                                            cb(selectedDates, dateStr, instance);
                                        }
                                    });
                                }
                            }
                        };

                        options.onOpen = function(selectedDates, dateStr, instance) {
                            // Apply theme after calendar opens
                            setTimeout(function() {
                                applyFlatpickrTheme(instance, element);
                            }, 10);

                            if (originalOnOpen) {
                                if (typeof originalOnOpen === 'function') {
                                    originalOnOpen(selectedDates, dateStr, instance);
                                } else if (Array.isArray(originalOnOpen)) {
                                    originalOnOpen.forEach(function(cb) {
                                        if (typeof cb === 'function') {
                                            cb(selectedDates, dateStr, instance);
                                        }
                                    });
                                }
                            }
                        };

                        // Set locale if available globally
                        if (options.locale && window.flatpickr && window.flatpickr.l10ns) {
                            const localeName = options.locale;
                            if (window.flatpickr.l10ns[localeName]) {
                                options.locale = window.flatpickr.l10ns[localeName];
                            }
                        }

                        const instance = window.flatpickr(element, options);

                        if (instance) {
                            element._flatpickrInstance = instance;
                            applyFlatpickrTheme(instance, element);
                        }
                    }
                }

                function updateAllFlatpickrThemes() {
                    document.querySelectorAll('[data-flatpickr]').forEach(function(element) {
                        const instance = element._flatpickrInstance || element._flatpickr;
                        if (instance && instance.calendarContainer) {
                            applyFlatpickrTheme(instance, element);
                        }
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        initFlatpickr();

                        const observer = new MutationObserver(function(mutations) {
                            mutations.forEach(function(mutation) {
                                if (mutation.attributeName === 'class') {
                                    updateAllFlatpickrThemes();
                                }
                            });
                        });

                        observer.observe(document.documentElement, {
                            attributes: true,
                            attributeFilter: ['class']
                        });
                    });
                } else {
                    initFlatpickr();

                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'class') {
                                updateAllFlatpickrThemes();
                            }
                        });
                    });

                    observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }
            })();
        </script>
    @endpush
@endonce