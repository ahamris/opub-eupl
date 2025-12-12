@props([
    'badge' => null,
    'badgeLink' => null,
    'badgeText' => null,
    'title',
    'description' => null,
    'primaryAction' => null,
    'primaryActionText' => 'Get started',
    'primaryActionLink' => '#',
    'secondaryAction' => null,
    'secondaryActionText' => 'Learn more',
    'secondaryActionLink' => '#',
])

<div class="relative isolate overflow-hidden bg-surface">
    <svg aria-hidden="true" class="absolute inset-0 -z-10 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-outline-variant">
        <defs>
            <pattern id="hero-pattern-{{ uniqid() }}" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                <path d="M.5 200V.5H200" fill="none" />
            </pattern>
        </defs>
        <svg x="50%" y="-1" class="overflow-visible fill-surface-variant">
            <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z" stroke-width="0" />
        </svg>
        <rect width="100%" height="100%" fill="url(#hero-pattern-{{ uniqid() }})" stroke-width="0" />
    </svg>
    <div aria-hidden="true" class="absolute top-10 left-[calc(50%-4rem)] -z-10 transform-gpu blur-3xl sm:left-[calc(50%-18rem)] lg:top-[calc(50%-30rem)] lg:left-48 xl:left-[calc(50%-24rem)]">
        <div style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)" 
             class="aspect-1108/632 w-277 bg-gradient-to-r from-primary/30 to-primary/20 opacity-20"></div>
    </div>
    <div class="mx-auto max-w-7xl px-6 pt-10 pb-24 sm:pb-32 lg:flex lg:px-8 lg:py-40">
        <div class="mx-auto max-w-2xl shrink-0 lg:mx-0 lg:pt-8">
            @if($badge || $badgeText)
            <div class="mt-24 sm:mt-32 lg:mt-16">
                @if($badgeLink)
                <a href="{{ $badgeLink }}" class="inline-flex space-x-6">
                    @if($badge)
                    <span class="rounded-full bg-primary-container px-3 py-1 text-sm/6 font-semibold text-on-primary-container ring-1 ring-primary/20 ring-inset">{{ $badge }}</span>
                    @endif
                    @if($badgeText)
                    <span class="inline-flex items-center space-x-2 text-sm/6 font-medium text-on-surface-variant">
                        <span>{{ $badgeText }}</span>
                        <i class="fas fa-chevron-right text-xs text-on-surface-variant/60" aria-hidden="true"></i>
                    </span>
                    @endif
                </a>
                @else
                <div class="inline-flex space-x-6">
                    @if($badge)
                    <span class="rounded-full bg-primary-container px-3 py-1 text-sm/6 font-semibold text-on-primary-container ring-1 ring-primary/20 ring-inset">{{ $badge }}</span>
                    @endif
                    @if($badgeText)
                    <span class="inline-flex items-center space-x-2 text-sm/6 font-medium text-on-surface-variant">
                        <span>{{ $badgeText }}</span>
                    </span>
                    @endif
                </div>
                @endif
            </div>
            @endif
            
            <h1 class="mt-10 text-5xl font-semibold tracking-tight text-pretty text-on-surface sm:text-7xl">
                {{ $title }}
            </h1>
            
            @if($description)
            <p class="mt-8 text-lg font-medium text-pretty text-on-surface-variant sm:text-xl/8">
                {{ $description }}
            </p>
            @endif
            
            <div class="mt-10 flex items-center gap-x-6">
                @if($primaryAction)
                    {{ $primaryAction }}
                @else
                <a href="{{ $primaryActionLink }}" 
                   class="rounded-md bg-primary px-3.5 py-2.5 text-sm font-semibold text-on-primary shadow-sm hover:bg-primary/90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors duration-200">
                    {{ $primaryActionText }}
                </a>
                @endif
                
                @if($secondaryAction)
                    {{ $secondaryAction }}
                @else
                <a href="{{ $secondaryActionLink }}" 
                   class="text-sm/6 font-semibold text-on-surface hover:text-primary transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                    {{ $secondaryActionText }} 
                    <span aria-hidden="true">→</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

