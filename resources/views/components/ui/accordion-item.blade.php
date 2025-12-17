@once
    @push('styles')
        <style>
            /* Minimal CSS for grid-template-rows transition, webkit details marker, and custom classes */
            [data-accordion] details summary::-webkit-details-marker {
                display: none;
            }

            [data-accordion] details .accordion-content {
                grid-template-rows: 0fr;
                transition: grid-template-rows 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            [data-accordion] details[open] .accordion-content {
                grid-template-rows: 1fr;
            }

            /* Custom classes added by JavaScript */
            [data-accordion] details.accordion-item--before-open {
                transform: translateY(-1.5rem);
            }

            [data-accordion] details.accordion-item--after-open {
                transform: translateY(1.5rem);
            }

            /* Content inner styles */
            [data-accordion] details .accordion-content-inner {
                transition: opacity 0.4s ease 0.1s, transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s;
            }

            [data-accordion] details:not([open]) .accordion-content-inner {
                opacity: 0;
                transform: translateY(10px);
            }

            [data-accordion] details[open] .accordion-content-inner {
                opacity: 1;
                transform: translateY(0);
            }

            /* Icon rotation when open */
            [data-accordion] details[open] summary .accordion-toggle {
                transform: rotate(45deg);
            }
        </style>
    @endpush
@endonce

@if($heading)
    <details
        @if($expanded) open @endif
        class="relative overflow-hidden m-0 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-none transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] [&+details]:-mt-px first:rounded-t-2xl last:rounded-b-2xl [[open]]:bg-zinc-100 dark:[[open]]:bg-zinc-800 [[open]]:rounded-2xl [[open]]:z-10 [[open]]:first:mt-0 [[open]]:last:mb-0 accordion-item--dimmed:opacity-50"
        x-data="{
            isDisabled: @js($disabled),
            init() {
                if (this.isDisabled) {
                    this.$el.querySelector('summary').style.pointerEvents = 'none';
                    this.$el.querySelector('summary').style.opacity = '0.5';
                }
                
                // State güncellemesi için observer
                const observer = new MutationObserver(() => {
                    this.updateAccordionState();
                });
                observer.observe(this.$el, { attributes: true, attributeFilter: ['open'] });
                
                this.updateAccordionState();
            },
            updateAccordionState() {
                const accordion = this.$el.closest('[data-accordion]');
                if (!accordion) return;
                
                const items = Array.from(accordion.querySelectorAll('details'));
                const openItems = items.filter(item => item.hasAttribute('open'));
                
                // Accordion wrapper'dan exclusive bilgisini al (Alpine.js data'dan)
                const accordionData = Alpine.$data(accordion);
                const isExclusiveMode = accordionData?.exclusive || false;
                
                // Eğer exclusive mode aktifse veya sadece bir item açıksa, animasyonları uygula
                const shouldAnimate = isExclusiveMode || openItems.length === 1;
                
                items.forEach((item) => {
                    item.classList.remove(
                        'accordion-item--open',
                        'accordion-item--before-open',
                        'accordion-item--after-open',
                        'accordion-item--dimmed'
                    );
                    
                    // Eğer item açık değilse
                    if (!item.hasAttribute('open')) {
                        if (openItems.length > 0) {
                            item.classList.add('accordion-item--dimmed');
                            
                            // Animasyonları sadece tek bir açık item varsa uygula
                            if (shouldAnimate && openItems.length === 1) {
                                const openItem = openItems[0];
                                const currentIndex = items.indexOf(item);
                                const openIndex = items.indexOf(openItem);
                                
                                if (currentIndex < openIndex) {
                                    item.classList.add('accordion-item--before-open');
                                } else {
                                    item.classList.add('accordion-item--after-open');
                                }
                            }
                        }
                    }
                });
            }
        }"
        @toggle="updateAccordionState()"
        @click="if (isDisabled) $event.preventDefault()"
    >
        <summary class="flex items-center gap-4 p-4 cursor-pointer select-none relative rounded-[inherit] overflow-hidden before:content-[''] before:absolute before:inset-0 before:bg-zinc-200 dark:before:bg-zinc-700 before:opacity-0 before:transition-opacity before:duration-200 before:pointer-events-none hover:before:opacity-100"
            x-data="{
                isReverse() {
                    const accordion = this.$el.closest('[data-accordion]');
                    if (!accordion) return false;
                    const accordionData = Alpine.$data(accordion);
                    return accordionData?.reverse || accordion.dataset.reverse === 'true';
                }
            }"
        >
            <template x-if="isReverse()">
                <div>
                    @if($icon)
                        {{-- Custom icon with rotation --}}
                        <i 
                            class="fa-solid fa-{{ $icon }} accordion-toggle w-5 h-5 shrink-0 relative z-1 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $color ? $colorClasses : 'text-zinc-700 dark:text-zinc-300' }}"
                        ></i>
                    @else
                        {{-- Default plus icon --}}
                        <i class="fa-solid fa-plus accordion-toggle w-5 h-5 shrink-0 relative z-1 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] text-zinc-700 dark:text-zinc-300"></i>
                    @endif
                </div>
            </template>
            
            <span class="flex-1 relative z-1 text-sm font-medium {{ $color ? $colorClasses : 'text-zinc-900 dark:text-white' }}">{{ $heading }}</span>
            
            <template x-if="!isReverse()">
                <div>
                    @if($icon)
                        {{-- Custom icon with rotation --}}
                        <i 
                            class="fa-solid fa-{{ $icon }} accordion-toggle w-5 h-5 shrink-0 relative z-1 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $color ? $colorClasses : 'text-zinc-700 dark:text-zinc-300' }}"
                        ></i>
                    @else
                        {{-- Default plus icon --}}
                        <i class="fa-solid fa-plus accordion-toggle w-5 h-5 shrink-0 relative z-1 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] text-zinc-700 dark:text-zinc-300"></i>
                    @endif
                </div>
            </template>
        </summary>
        
        <div class="accordion-content grid overflow-hidden">
            <div class="accordion-content-inner min-h-0 px-4 pb-4 text-zinc-600 dark:text-zinc-400 leading-relaxed">
                <div class="m-0">{{ $slot }}</div>
            </div>
        </div>
    </details>
@else
    {{ $slot }}
@endif
