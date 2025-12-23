@php
    $footerMenuItems = \App\Models\FooterMenuItem::getMenuTree();
@endphp

<!-- Premium Footer with Primary Dark Theme -->
<footer class="relative bg-[var(--color-primary-dark)] text-white mt-auto" aria-labelledby="footer-heading">
    <!-- Subtle top border gradient -->
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
    
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="mx-auto max-w-7xl px-6 pb-10 pt-14 lg:px-8 lg:pt-16">
        <div class="xl:grid xl:grid-cols-3 xl:gap-12">
            <!-- Links Section -->
            @if($footerMenuItems->isNotEmpty())
                <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        @foreach($footerMenuItems->take(2) as $menuGroup)
                            <div class="{{ $loop->index > 0 ? 'mt-8 md:mt-0' : '' }}">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-white/60 mb-4">
                                    {{ $menuGroup->label }}
                                </h3>
                                @if($menuGroup->activeChildren->isNotEmpty())
                                    <ul role="list" class="space-y-3">
                                        @foreach($menuGroup->activeChildren as $child)
                                            <li>
                                                @php
                                                    $url = $child->resolved_url;
                                                    $isExternal = !empty($child->url) && empty($child->route_name);
                                                    $target = $child->target ?: ($isExternal ? '_blank' : null);
                                                    $rel = $isExternal ? 'noopener noreferrer' : null;
                                                @endphp
                                                <a 
                                                    href="{{ $url ?: '#' }}" 
                                                    @if($target) target="{{ $target }}" @endif
                                                    @if($rel) rel="{{ $rel }}" @endif
                                                    class="text-sm leading-6 text-white/80 hover:text-white transition-colors duration-200 focus:outline-none {{ $isExternal ? 'inline-flex items-center gap-1.5' : '' }}"
                                                >
                                                    {{ $child->label }}
                                                    @if($isExternal)
                                                        <i class="fas fa-external-link-alt text-[10px] text-white/40" aria-hidden="true"></i>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if($footerMenuItems->count() > 2)
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            @foreach($footerMenuItems->skip(2) as $menuGroup)
                                <div>
                                    <h3 class="text-xs font-semibold uppercase tracking-wider text-white/60 mb-4">
                                        {{ $menuGroup->label }}
                                    </h3>
                                    @if($menuGroup->activeChildren->isNotEmpty())
                                        <ul role="list" class="space-y-3">
                                            @foreach($menuGroup->activeChildren as $child)
                                                <li>
                                                    @php
                                                        $url = $child->resolved_url;
                                                        $isExternal = !empty($child->url) && empty($child->route_name);
                                                        $target = $child->target ?: ($isExternal ? '_blank' : null);
                                                        $rel = $isExternal ? 'noopener noreferrer' : null;
                                                    @endphp
                                                    <a 
                                                        href="{{ $url ?: '#' }}" 
                                                        @if($target) target="{{ $target }}" @endif
                                                        @if($rel) rel="{{ $rel }}" @endif
                                                        class="text-sm leading-6 text-white/80 hover:text-white transition-colors duration-200 focus:outline-none {{ $isExternal ? 'inline-flex items-center gap-1.5' : '' }}"
                                                    >
                                                        {{ $child->label }}
                                                        @if($isExternal)
                                                            <i class="fas fa-external-link-alt text-[10px] text-white/40" aria-hidden="true"></i>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Mission Statement Column -->
            <div class="mt-10 xl:mt-0">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-white/60 mb-4">Onze missie</h3>
                <p class="text-sm leading-relaxed text-white/70">
                    Open.overheid.nl bundelt actief openbaar gemaakte overheidsdocumenten op één centrale plek, 
                    zodat burgers en professionals deze eenvoudig kunnen vinden en raadplegen.
                </p>
                <p class="mt-4 text-sm leading-relaxed text-white/50">
                    Wij werken op basis van de Wet open overheid (Woo) om transparantie en toegankelijkheid te bevorderen.
                </p>
            </div>
        </div>
        
        <!-- Bottom bar -->
        <div class="mt-10 pt-6 border-t border-white/10">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-white/50">
                    &copy; {{ date('Y') }} Open Overheid. Alle rechten voorbehouden.
                </p>
                <div class="flex items-center gap-6">
                    <span class="text-xs text-white/40">Digitaliseringspartner voor slimme ICT-oplossingen</span>
                </div>
            </div>
        </div>
    </div>
</footer>
