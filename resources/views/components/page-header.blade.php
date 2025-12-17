@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'breadcrumbs' => [],
])

<!-- Premium Page Header Section -->
<div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
    <!-- Subtle grid pattern -->
    <div class="absolute inset-0 -z-10">
        <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
            <defs>
                <pattern id="page-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M.5 40V.5H40" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" stroke-width="0" fill="url(#page-header-grid)" />
        </svg>
        <!-- Fade overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
    </div>
    
    <!-- Animated floating squares -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Square 1 - Top right -->
        <div class="absolute top-8 right-[15%] w-16 h-16 rounded-md bg-[var(--color-primary)]/[0.04] animate-float-slow"></div>
        <!-- Square 2 - Top left -->
        <div class="absolute top-16 left-[10%] w-12 h-12 rounded-md bg-[var(--color-primary)]/[0.03] animate-float-slower"></div>
        <!-- Square 3 - Middle right -->
        <div class="absolute top-1/2 right-[8%] w-20 h-20 rounded-md bg-[var(--color-primary)]/[0.05] animate-float-slow" style="animation-delay: -2s;"></div>
        <!-- Square 4 - Bottom left -->
        <div class="absolute bottom-12 left-[20%] w-14 h-14 rounded-md bg-[var(--color-primary)]/[0.04] animate-float-slower" style="animation-delay: -3s;"></div>
        <!-- Square 5 - Top center-right -->
        <div class="absolute top-12 right-[35%] w-10 h-10 rounded-md bg-[var(--color-primary)]/[0.03] animate-float-slow" style="animation-delay: -1s;"></div>
    </div>
    
    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12 sm:py-16 relative z-10">
        <!-- Breadcrumb -->
        @if(!empty($breadcrumbs))
        <div class="mb-6">
            <x-breadcrumbs :items="$breadcrumbs" />
        </div>
        @endif
        
        <div class="mx-auto max-w-2xl lg:mx-0">
            @if($eyebrow)
            <p class="text-sm font-medium uppercase tracking-wider text-[var(--color-primary)]">{{ $eyebrow }}</p>
            @endif
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-[var(--color-on-surface)] sm:text-4xl">
                {{ $title }}
            </h1>
            @if($description)
            <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed sm:text-lg">
                {{ $description }}
            </p>
            @endif
        </div>
    </div>
    
    <!-- Bottom gradient line -->
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
</div>

<style>
@keyframes float-slow {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-12px) rotate(3deg); }
}
@keyframes float-slower {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-8px) rotate(-2deg); }
}
.animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
}
.animate-float-slower {
    animation: float-slower 8s ease-in-out infinite;
}
</style>
