@props([
    'leaderboardAllTime' => [],
    'leaderboardThisMonth' => [],
    'leaderboardThisYear' => [],
])

<div class="relative isolate bg-surface py-20 sm:py-32">
    <div class="relative mx-auto max-w-7xl px-10 lg:px-10">
        <!-- Header Section -->
        <div class="mx-auto max-w-3xl lg:mx-0 lg:max-w-4xl mb-12">
            <h2 class="text-label-large font-semibold text-primary uppercase tracking-wider mb-4">Transparantie Leaderboard</h2>
            <h2 class="text-headline-large font-bold tracking-[-0.01em] text-pretty text-on-surface leading-tight mb-6">
                Bestuursorganen die het meest openbaar maken
            </h2>
            <p class="text-body-large text-on-surface-variant leading-relaxed max-w-2xl">
                Ontdek welke organisaties het meest actief zijn in het openbaar maken van documenten
            </p>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8" x-data="{ activePeriod: 'alltime' }">
            <div class="flex gap-2 border-b border-outline-variant/40">
                <button 
                    @click="activePeriod = 'alltime'"
                    :class="activePeriod === 'alltime' ? 'text-primary border-b-2 border-primary font-semibold' : 'text-on-surface-variant hover:text-primary'"
                    class="px-6 py-4 text-body-large font-medium transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm"
                >
                    Altijd
                </button>
                <button 
                    @click="activePeriod = 'thisyear'"
                    :class="activePeriod === 'thisyear' ? 'text-primary border-b-2 border-primary font-semibold' : 'text-on-surface-variant hover:text-primary'"
                    class="px-6 py-4 text-body-large font-medium transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm"
                >
                    Dit jaar
                </button>
                <button 
                    @click="activePeriod = 'thismonth'"
                    :class="activePeriod === 'thismonth' ? 'text-primary border-b-2 border-primary font-semibold' : 'text-on-surface-variant hover:text-primary'"
                    class="px-6 py-4 text-body-large font-medium transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm"
                >
                    Deze maand
                </button>
            </div>

            <!-- All Time Leaderboard -->
            <div x-show="activePeriod === 'alltime'" x-cloak class="mt-8">
                <div class="bg-surface border border-outline-variant/40 rounded-lg p-10 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    @if(!empty($leaderboardAllTime))
                    <div class="space-y-3">
                        @foreach($leaderboardAllTime as $index => $item)
                            @php
                                $searchUrl = route('zoeken', ['organisatie' => $item['organisation']]);
                                $rank = $index + 1;
                                $medalClass = match($rank) {
                                    1 => 'bg-yellow-400 text-yellow-900',
                                    2 => 'bg-gray-300 text-gray-700',
                                    3 => 'bg-amber-600 text-amber-50',
                                    default => 'bg-primary/10 text-primary'
                                };
                            @endphp
                            <a href="{{ $searchUrl }}" 
                               class="flex items-center gap-4 p-4 rounded hover:bg-primary-light/20 transition-all duration-200 group
                                      focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $medalClass }} flex items-center justify-center font-bold text-title-small">
                                    {{ $rank }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-body-medium font-semibold text-on-surface truncate group-hover:text-primary transition-colors duration-200">
                                        {{ $item['organisation'] }}
                                    </h3>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <span class="text-headline-small font-bold text-primary">
                                        {{ number_format($item['count'], 0, ',', '.') }}
                                    </span>
                                    <p class="text-label-small text-on-surface-variant/70">documenten</p>
                                </div>
                                <i class="fas fa-chevron-right text-on-surface-variant/40 shrink-0 text-sm opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-body-medium text-on-surface-variant">Geen data beschikbaar.</p>
                    @endif
                </div>
            </div>

            <!-- This Year Leaderboard -->
            <div x-show="activePeriod === 'thisyear'" x-cloak class="mt-8">
                <div class="bg-surface border border-outline-variant/40 rounded-lg p-10 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    @if(!empty($leaderboardThisYear))
                    <div class="space-y-3">
                        @foreach($leaderboardThisYear as $index => $item)
                            @php
                                $searchUrl = route('zoeken', ['organisatie' => $item['organisation']]);
                                $rank = $index + 1;
                                $medalClass = match($rank) {
                                    1 => 'bg-yellow-400 text-yellow-900',
                                    2 => 'bg-gray-300 text-gray-700',
                                    3 => 'bg-amber-600 text-amber-50',
                                    default => 'bg-primary/10 text-primary'
                                };
                            @endphp
                            <a href="{{ $searchUrl }}" 
                               class="flex items-center gap-4 p-4 rounded hover:bg-primary-light/20 transition-all duration-200 group
                                      focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $medalClass }} flex items-center justify-center font-bold text-title-small">
                                    {{ $rank }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-body-medium font-semibold text-on-surface truncate group-hover:text-primary transition-colors duration-200">
                                        {{ $item['organisation'] }}
                                    </h3>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <span class="text-headline-small font-bold text-primary">
                                        {{ number_format($item['count'], 0, ',', '.') }}
                                    </span>
                                    <p class="text-label-small text-on-surface-variant/70">documenten</p>
                                </div>
                                <i class="fas fa-chevron-right text-on-surface-variant/40 shrink-0 text-sm opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-body-medium text-on-surface-variant">Geen data beschikbaar voor dit jaar.</p>
                    @endif
                </div>
            </div>

            <!-- This Month Leaderboard -->
            <div x-show="activePeriod === 'thismonth'" x-cloak class="mt-8">
                <div class="bg-surface border border-outline-variant/40 rounded-lg p-10 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    @if(!empty($leaderboardThisMonth))
                    <div class="space-y-3">
                        @foreach($leaderboardThisMonth as $index => $item)
                            @php
                                $searchUrl = route('zoeken', ['organisatie' => $item['organisation']]);
                                $rank = $index + 1;
                                $medalClass = match($rank) {
                                    1 => 'bg-yellow-400 text-yellow-900',
                                    2 => 'bg-gray-300 text-gray-700',
                                    3 => 'bg-amber-600 text-amber-50',
                                    default => 'bg-primary/10 text-primary'
                                };
                            @endphp
                            <a href="{{ $searchUrl }}" 
                               class="flex items-center gap-4 p-4 rounded hover:bg-primary-light/20 transition-all duration-200 group
                                      focus:outline-2 focus:outline-primary focus:outline-offset-2 focus:rounded-sm">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $medalClass }} flex items-center justify-center font-bold text-title-small">
                                    {{ $rank }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-body-medium font-semibold text-on-surface truncate group-hover:text-primary transition-colors duration-200">
                                        {{ $item['organisation'] }}
                                    </h3>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <span class="text-headline-small font-bold text-primary">
                                        {{ number_format($item['count'], 0, ',', '.') }}
                                    </span>
                                    <p class="text-label-small text-on-surface-variant/70">documenten</p>
                                </div>
                                <i class="fas fa-chevron-right text-on-surface-variant/40 shrink-0 text-sm opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-body-medium text-on-surface-variant">Geen data beschikbaar voor deze maand.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
