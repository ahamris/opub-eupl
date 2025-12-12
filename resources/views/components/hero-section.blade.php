@props([
    'badge' => null,
    'title',
    'description',
    'withBackground' => true,
])

<div class="relative isolate overflow-hidden bg-surface py-24 sm:py-32">
    @if($withBackground)
    <div aria-hidden="true" class="absolute -top-80 left-[max(6rem,33%)] -z-10 transform-gpu blur-3xl sm:left-1/2 md:top-20 lg:ml-20 xl:top-3 xl:ml-56">
        <div style="clip-path: polygon(63.1% 29.6%, 100% 17.2%, 76.7% 3.1%, 48.4% 0.1%, 44.6% 4.8%, 54.5% 25.4%, 59.8% 49.1%, 55.3% 57.9%, 44.5% 57.3%, 27.8% 48%, 35.1% 81.6%, 0% 97.8%, 39.3% 100%, 35.3% 81.5%, 97.2% 52.8%, 63.1% 29.6%)" 
             class="aspect-801/1036 w-200.25 bg-gradient-to-tr from-primary/20 to-primary/10 opacity-30"></div>
    </div>
    @endif
    
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:mx-0">
            @if($badge)
            <p class="text-base/7 font-semibold text-primary">{{ $badge }}</p>
            @endif
            
            <h1 class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-on-surface sm:text-5xl">
                {{ $title }}
            </h1>
            
            @if($description)
            <p class="mt-6 text-xl/8 text-on-surface-variant">
                {{ $description }}
            </p>
            @endif
        </div>
    </div>
</div>

