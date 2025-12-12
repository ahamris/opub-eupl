@props([
    'statistics' => [],
])

@php
    $totalDocuments = $statistics['totalDocuments'] ?? 0;
    $categoryCount = $statistics['categoryCount'] ?? 0;
    $themeCount = $statistics['themeCount'] ?? 0;
    $organisationCount = $statistics['organisationCount'] ?? 0;
    $dossierCount = $statistics['dossierCount'] ?? 0;
    $topCategories = $statistics['topCategories'] ?? [];
    $topThemes = $statistics['topThemes'] ?? [];
@endphp

<div class="relative isolate bg-surface py-20 sm:py-32">
    <!-- Elegant subtle background accent -->
    <div aria-hidden="true" class="absolute inset-0 -z-10">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-primary-light/5"></div>
    </div>
    
    <!-- Boxed container with 40px padding -->
    <div class="relative mx-auto max-w-7xl px-10 lg:px-10">
        <!-- Header Section with elegant spacing - DICTU Style -->
        <div class="mx-auto max-w-3xl lg:mx-0 lg:max-w-4xl mb-20">
            <h2 class="text-label-large font-semibold text-primary uppercase tracking-wider mb-4">Open Overheid Statistieken</h2>
            <h2 class="text-headline-large font-bold tracking-[-0.01em] text-pretty text-on-surface leading-tight mb-6">
                Ontdek alle beschikbare overheidsdocumenten
            </h2>
            <p class="text-body-large text-on-surface-variant leading-relaxed max-w-2xl">
                Duizenden documenten, georganiseerd per categorie en thema. Klik op een categorie of thema om direct te beginnen met zoeken.
            </p>
        </div>
        
        <!-- Statistics in Tabs - Categories and Themes -->
        @if(!empty($topCategories) || !empty($topThemes))
        <div class="mx-auto mt-12 max-w-4xl" x-data="{ activeTab: 'categories' }">
            <!-- Tab Navigation -->
            <div class="flex gap-2 mb-8 border-b border-outline-variant/40">
                <button 
                    @click="activeTab = 'categories'"
                    :class="activeTab === 'categories' ? 'text-primary border-b-2 border-primary font-semibold' : 'text-on-surface-variant hover:text-primary'"
                    class="px-6 py-4 text-body-large font-medium transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm"
                >
                    Populairste categorieën
                </button>
                <button 
                    @click="activeTab = 'themes'"
                    :class="activeTab === 'themes' ? 'text-primary border-b-2 border-primary font-semibold' : 'text-on-surface-variant hover:text-primary'"
                    class="px-6 py-4 text-body-large font-medium transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm"
                >
                    Populairste thema's
                </button>
            </div>

            <!-- Tab Content: Categories -->
            <div x-show="activeTab === 'categories'" x-cloak class="bg-surface border border-outline-variant/40 rounded-lg p-10 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                @if(!empty($topCategories))
                <div class="space-y-4">
                    @php
                        $wooCategoryService = app(\App\Services\OpenOverheid\WooCategoryService::class);
                    @endphp
                    @foreach($topCategories as $category => $count)
                        @php
                            if (strtolower(trim($category)) === 'onbekend' || empty($category)) {
                                continue;
                            }
                            $percentage = $totalDocuments > 0 ? round(($count / $totalDocuments) * 100, 1) : 0;
                            $formattedCategory = $wooCategoryService->formatCategoryForDisplay($category) ?? $category;
                            $searchUrl = route('zoeken', ['informatiecategorie' => $category]);
                        @endphp
                        <a href="{{ $searchUrl }}" 
                           class="block group hover:bg-primary-light/30 rounded p-4 -m-4 transition-all duration-200 ease-out
                                  focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm
                                  active:scale-[0.98]">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-body-medium font-medium tracking-[-0.01em] text-on-surface truncate flex-1 mr-4 group-hover:text-primary transition-colors duration-200">
                                    {{ $formattedCategory }}
                                </span>
                                <span class="text-label-small font-semibold text-primary shrink-0 bg-primary/10 px-2.5 py-1 rounded tracking-tight">
                                    {{ number_format($count, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="w-full bg-outline-variant/50 rounded-full h-2 overflow-hidden mb-2">
                                <div class="bg-primary h-full rounded-full transition-all duration-700 ease-out group-hover:bg-primary-dark" 
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-label-small text-on-surface-variant/70">{{ $percentage }}% van totaal</span>
                                <i class="fas fa-arrow-right text-[10px] text-primary opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all duration-200" aria-hidden="true"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
                @else
                <p class="text-body-medium text-on-surface-variant">Geen categorieën beschikbaar.</p>
                @endif
            </div>

            <!-- Tab Content: Themes -->
            <div x-show="activeTab === 'themes'" x-cloak class="bg-surface border border-outline-variant/40 rounded-lg p-10 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                @if(!empty($topThemes))
                <div class="space-y-4">
                    @foreach($topThemes as $theme => $count)
                        @php
                            if (strtolower(trim($theme)) === 'onbekend' || empty($theme)) {
                                continue;
                            }
                            $percentage = $totalDocuments > 0 ? round(($count / $totalDocuments) * 100, 1) : 0;
                            $searchUrl = route('zoeken', ['thema' => [$theme]]);
                        @endphp
                        <a href="{{ $searchUrl }}" 
                           class="block group hover:bg-primary-light/30 rounded p-4 -m-4 transition-all duration-200 ease-out
                                  focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm
                                  active:scale-[0.98]">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-body-medium font-medium tracking-[-0.01em] text-on-surface truncate flex-1 mr-4 group-hover:text-primary transition-colors duration-200">
                                    {{ $theme }}
                                </span>
                                <span class="text-label-small font-semibold text-primary shrink-0 bg-primary/10 px-2.5 py-1 rounded tracking-tight">
                                    {{ number_format($count, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="w-full bg-outline-variant/50 rounded-full h-2 overflow-hidden mb-2">
                                <div class="bg-primary h-full rounded-full transition-all duration-700 ease-out group-hover:bg-primary-dark" 
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-label-small text-on-surface-variant/70">{{ $percentage }}% van totaal</span>
                                <i class="fas fa-arrow-right text-[10px] text-primary opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all duration-200" aria-hidden="true"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
                @else
                <p class="text-body-medium text-on-surface-variant">Geen thema's beschikbaar.</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

