{{-- Dark Panel CTA with App Screenshot --}}
@props([
    'title' => 'Begin vandaag nog met transparante publicaties',
    'description' => 'Ontdek hoe OpenPublicaties uw organisatie kan helpen bij het voldoen aan de Wet open overheid. Eenvoudig, betrouwbaar en volledig open source.',
    'primaryButtonText' => 'Start nu',
    'primaryButtonUrl' => '#',
    'secondaryButtonText' => 'Meer informatie',
    'secondaryButtonUrl' => '#',
    'screenshotUrl' => null,
    'screenshotAlt' => 'App screenshot',
    'variant' => 'purple', // 'purple' or 'primary'
])

@php
    $gradientClass = $variant === 'primary' 
        ? 'bg-conic-135 from-[var(--color-primary-dark)] to-black' 
        : 'bg-conic-135 from-[var(--color-purple)] to-black';
@endphp

<div class="bg-white dark:bg-gray-900">
    <div class="mx-auto max-w-7xl py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="relative isolate overflow-hidden {{ $gradientClass }} px-6 pt-16 shadow-2xl sm:rounded-md sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">
            {{-- Background gradient decoration --}}
            <svg viewBox="0 0 1024 1024" aria-hidden="true" class="absolute top-1/2 left-1/2 -z-10 size-[64rem] -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] sm:left-full sm:-ml-80 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2 lg:translate-y-0">
                <circle r="512" cx="512" cy="512" fill="url(#cta-gradient-{{ uniqid() }})" fill-opacity="0.7" />
                <defs>
                    <radialGradient id="cta-gradient-{{ uniqid() }}">
                        <stop stop-color="var(--color-primary)" />
                        <stop offset="1" stop-color="var(--color-accent)" />
                    </radialGradient>
                </defs>
            </svg>
            
            {{-- Content --}}
            <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
                <h2 class="text-3xl font-semibold tracking-tight text-balance text-white sm:text-4xl">
                    {{ $title }}
                </h2>
                <p class="mt-6 text-lg/8 text-pretty text-white/80">
                    {{ $description }}
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                    <a href="{{ $primaryButtonUrl }}" class="rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-[var(--color-primary-dark)] shadow-sm hover:bg-gray-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-colors">
                        {{ $primaryButtonText }}
                    </a>
                    <a href="{{ $secondaryButtonUrl }}" class="text-sm/6 font-semibold text-white hover:text-white/80 transition-colors">
                        {{ $secondaryButtonText }}
                        <span aria-hidden="true" class="ml-1">→</span>
                    </a>
                </div>
            </div>
            
            {{-- Screenshot --}}
            @if($screenshotUrl)
            <div class="relative mt-16 h-80 lg:mt-8">
                <img 
                    width="1824" 
                    height="1080" 
                    src="{{ $screenshotUrl }}" 
                    alt="{{ $screenshotAlt }}" 
                    class="absolute top-0 left-0 w-[57rem] max-w-none rounded-md bg-white/5 ring-1 ring-white/10"
                >
            </div>
            @else
            <div class="relative mt-16 h-80 lg:mt-8">
                {{-- Placeholder with icon --}}
                <div class="absolute top-0 left-0 w-[57rem] max-w-none h-[360px] rounded-md bg-white/5 ring-1 ring-white/10 flex items-center justify-center">
                    <div class="text-center p-8">
                        <i class="fas fa-search text-6xl text-white/20 mb-4"></i>
                        <p class="text-white/40 text-sm">Ontdek alle mogelijkheden</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
