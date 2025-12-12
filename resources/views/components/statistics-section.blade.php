@props([
    'statistics' => [],
])

@php
    $totalDocuments = $statistics['totalDocuments'] ?? 0;
    $categoryCount = $statistics['categoryCount'] ?? 0;
    $themeCount = $statistics['themeCount'] ?? 0;
    $organisationCount = $statistics['organisationCount'] ?? 0;
    $topCategories = $statistics['topCategories'] ?? [];
    $topThemes = $statistics['topThemes'] ?? [];
@endphp

<div class="relative isolate bg-surface py-16 sm:py-24">
    <div aria-hidden="true" class="absolute -bottom-8 -left-96 -z-10 transform-gpu blur-3xl sm:-bottom-64 sm:-left-40 lg:-bottom-32 lg:left-8 xl:-left-10">
        <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" 
             class="aspect-1266/975 w-316.5 bg-linear-to-tr from-primary to-primary-dark opacity-10"></div>
    </div>
    
    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl">
            <h2 class="text-base font-semibold text-primary">Open Overheid Statistieken</h2>
            <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-on-surface sm:text-5xl">
                Overzicht van beschikbare overheidsdocumenten
            </p>
            <p class="mt-6 text-lg text-on-surface-variant">
                Ontdek hoeveel documenten beschikbaar zijn, verdeeld over verschillende informatiecategorieën en thema's.
            </p>
        </div>
        
        <dl class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-10 text-on-surface sm:mt-20 sm:grid-cols-2 sm:gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-4">
            <div class="flex flex-col gap-y-3 border-l border-outline-variant pl-6">
                <dt class="text-sm font-medium text-on-surface-variant">Totaal documenten</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-on-surface">
                    {{ number_format($totalDocuments, 0, ',', '.') }}
                </dd>
            </div>
            <div class="flex flex-col gap-y-3 border-l border-outline-variant pl-6">
                <dt class="text-sm font-medium text-on-surface-variant">Informatiecategorieën</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-on-surface">
                    {{ number_format($categoryCount, 0, ',', '.') }}
                </dd>
            </div>
            <div class="flex flex-col gap-y-3 border-l border-outline-variant pl-6">
                <dt class="text-sm font-medium text-on-surface-variant">Thema's</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-on-surface">
                    {{ number_format($themeCount, 0, ',', '.') }}
                </dd>
            </div>
            <div class="flex flex-col gap-y-3 border-l border-outline-variant pl-6">
                <dt class="text-sm font-medium text-on-surface-variant">Organisaties</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-on-surface">
                    {{ number_format($organisationCount, 0, ',', '.') }}
                </dd>
            </div>
        </dl>

        <!-- Top Categories and Themes -->
        @if(!empty($topCategories) || !empty($topThemes))
        <div class="mx-auto mt-20 grid max-w-2xl grid-cols-1 gap-8 lg:mx-0 lg:max-w-none lg:grid-cols-2">
            <!-- Top Informatiecategorieën -->
            @if(!empty($topCategories))
            <div class="bg-surface-variant rounded-xl p-6 border border-outline-variant">
                <h3 class="text-lg font-semibold text-on-surface mb-4">Top Informatiecategorieën</h3>
                <div class="space-y-3">
                    @foreach($topCategories as $category => $count)
                        @php
                            $percentage = $totalDocuments > 0 ? round(($count / $totalDocuments) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-on-surface truncate flex-1 mr-2">{{ $category }}</span>
                                <span class="text-sm font-semibold text-on-surface shrink-0">{{ number_format($count, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-outline-variant rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                            <span class="text-xs text-on-surface-variant mt-1 block">{{ $percentage }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Top Thema's -->
            @if(!empty($topThemes))
            <div class="bg-surface-variant rounded-xl p-6 border border-outline-variant">
                <h3 class="text-lg font-semibold text-on-surface mb-4">Top Thema's</h3>
                <div class="space-y-3">
                    @foreach($topThemes as $theme => $count)
                        @php
                            $percentage = $totalDocuments > 0 ? round(($count / $totalDocuments) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-on-surface truncate flex-1 mr-2">{{ $theme }}</span>
                                <span class="text-sm font-semibold text-on-surface shrink-0">{{ number_format($count, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-outline-variant rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                            <span class="text-xs text-on-surface-variant mt-1 block">{{ $percentage }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

