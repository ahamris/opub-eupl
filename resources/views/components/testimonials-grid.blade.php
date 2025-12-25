@props([
    'testimonials' => null,
])

@php
    // Get testimonials from database (cached)
    $testimonials = $testimonials ?? \App\Models\Testimonial::getAllActive();
@endphp

<div class="bg-gradient-to-b from-white to-slate-50 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Section divider -->
        <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent mb-12"></div>
        
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-10">
            <div>
                <p class="text-sm font-medium uppercase">Testimonials</p>
                <h2 class="mt-2 font-semibold sm:text-4xl">
                    Wat gebruikers vinden
                </h2>
            </div>
            <p class="max-w-sm text-sm lg:text-right">
                Ontdek hoe anderen het platform gebruiken voor hun onderzoek en werk.
            </p>
        </div>
        
        <div class="mx-auto flow-root max-w-2xl lg:mx-0 lg:max-w-none">
            <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3">
                @foreach($testimonials as $testimonial)
                @php
                    $isObject = is_object($testimonial);
                    $rating = (int) ($isObject ? $testimonial->rating : ($testimonial['rating'] ?? 5));
                    $quote = $isObject ? $testimonial->quote : $testimonial['quote'];
                    $author = $isObject ? $testimonial->author : $testimonial['author'];
                    $role = $isObject ? $testimonial->role : ($testimonial['role'] ?? null);
                    $organization = $isObject ? $testimonial->organization : ($testimonial['organization'] ?? null);
                @endphp
                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                    <figure class="rounded-md bg-white p-8 ring-1 ring-slate-200/60">
                        <!-- Star Rating (Debug: {{ $rating }}) -->
                        <div class="flex items-center gap-0.5 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
                                    class="w-4 h-4 @if($i <= $rating) text-orange-400 @else text-gray-200 @endif">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd"/>
                                </svg>
                            @endfor
                        </div>
                        <blockquote class="text-[var(--color-on-surface)] leading-relaxed">
                            <p>{{ $quote }}</p>
                        </blockquote>
                        <figcaption class="mt-6 flex items-center gap-x-4 pt-6 border-t border-slate-100">
                            <!-- User Icon instead of avatar -->
                            <div class="h-12 w-12 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center ring-2 ring-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-[var(--color-primary)]">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-[var(--color-on-surface)]">
                                    {{ $author }}
                                </div>
                                <div class="text-sm text-[var(--color-on-surface-variant)]">
                                    {{ $role }}{{ $organization ? ' · ' . $organization : '' }}
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                @endforeach
                
                <!-- Show More Card -->
                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                    <figure class="rounded-md bg-white p-8 ring-1 ring-slate-200/60 h-full flex flex-col items-center justify-center min-h-[280px] group hover:ring-[var(--color-primary)]/30 transition-all duration-200 cursor-pointer">
                        <div class="flex flex-col items-center justify-center text-center space-y-4">
                            <div class="w-12 h-12 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center group-hover:bg-[var(--color-primary)]/20 transition-colors duration-200">
                                <svg class="w-6 h-6 text-[var(--color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors duration-200">
                                    Meer tonen
                                </p>
                                <p class="text-sm text-[var(--color-on-surface-variant)] mt-1">
                                    Bekijk alle testimonials
                                </p>
                            </div>
                        </div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>