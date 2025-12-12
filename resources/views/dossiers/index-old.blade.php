@extends('layouts.app')

@section('title', 'Dossiers - Open Overheid')

@section('breadcrumbs')
    U bent hier: 
    <a href="{{ route('home') }}" class="text-primary hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">Home</a> / 
    <span class="text-on-surface">Dossiers</span>
@endsection

@section('content')
<div class="mx-auto max-w-7xl px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-headline-large font-semibold text-on-surface mb-4">Dossiers</h1>
        <p class="text-body-large text-on-surface-variant">
            Overzicht van alle documenten die deel uitmaken van een dossier. Een dossier is een groep gerelateerde documenten die bij elkaar horen.
        </p>
        <p class="text-body-medium text-on-surface-variant mt-2">
            Totaal: <strong>{{ number_format($totalDossiers, 0, ',', '.') }}</strong> document{{ $totalDossiers !== 1 ? 'en' : '' }} in dossiers
        </p>
    </div>

    <!-- Instant Search Filter -->
    <div class="mb-8 bg-surface rounded-xl shadow-sm border border-outline-variant">
        <div class="p-6">
            <label for="dossier-quick-filter" class="block text-label-large font-medium text-on-surface mb-3">
                Snel filteren
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-on-surface-variant" aria-hidden="true"></i>
                </div>
                <input 
                    type="text" 
                    id="dossier-quick-filter"
                    name="dossier-quick-filter"
                    placeholder="Type om te filteren op organisatie, thema, documentsoort of informatiecategorie..."
                    class="block w-full pl-10 pr-3 py-3 rounded-lg border-2 border-outline bg-surface
                           text-body-medium text-on-surface placeholder-on-surface-variant
                           focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                           transition-colors duration-200"
                    autocomplete="off"
                    onkeyup="filterDossiers(this.value)"
                >
                <div id="dossier-filter-results" class="absolute z-10 mt-1 w-full bg-surface rounded-lg shadow-lg border border-outline-variant hidden max-h-60 overflow-auto">
                    <!-- Results populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    @if($dossiers->count() > 0)
    <div id="dossiers-container" class="space-y-4">
        @foreach($dossiers as $dossier)
        <article class="dossier-item bg-surface rounded-xl p-6 border border-outline-variant hover:border-primary/30 transition-all duration-200"
                 data-title="{{ $dossier->title ?? '' }}"
                 data-description="{{ $dossier->description ?? '' }}"
                 data-organisation="{{ $dossier->organisation ?? '' }}"
                 data-theme="{{ $dossier->theme ?? '' }}"
                 data-document-type="{{ $dossier->document_type ?? '' }}"
                 data-category="{{ $dossier->getFormattedCategoryAttribute() ?? '' }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                            <i class="fas fa-folder-open text-primary text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-title-medium font-medium text-on-surface mb-2">
                                <a href="{{ route('dossiers.show', $dossier->external_id) }}" 
                                   class="hover:text-primary transition-colors duration-200 focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm">
                                    {{ $dossier->title ?? 'Geen titel' }}
                                </a>
                            </h2>
                            <div class="flex flex-wrap gap-4 text-body-small text-on-surface-variant mb-3">
                                @if($dossier->document_type)
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-file-alt text-xs" aria-hidden="true"></i>
                                        <span>{{ $dossier->document_type }}</span>
                                    </span>
                                @endif
                                @if($dossier->publication_date)
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-calendar text-xs" aria-hidden="true"></i>
                                        <span>{{ $dossier->publication_date->format('d-m-Y') }}</span>
                                    </span>
                                @endif
                                @if($dossier->organisation)
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-building text-xs" aria-hidden="true"></i>
                                        <span>{{ $dossier->organisation }}</span>
                                    </span>
                                @endif
                                @if(isset($dossier->dossier_member_count) && $dossier->dossier_member_count > 0)
                                    <span class="inline-flex items-center gap-1.5 text-primary font-medium">
                                        <i class="fas fa-link text-xs" aria-hidden="true"></i>
                                        <span>{{ $dossier->dossier_member_count }} gerelateerd{{ $dossier->dossier_member_count !== 1 ? 'e' : '' }} document{{ $dossier->dossier_member_count !== 1 ? 'en' : '' }}</span>
                                    </span>
                                @endif
                            </div>
                            @if($dossier->description)
                                <p class="text-body-medium text-on-surface-variant line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($dossier->description, 200) }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <a href="{{ route('dossiers.show', $dossier->external_id) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                  bg-primary text-on-primary border border-primary
                                  hover:bg-primary/90 hover:border-primary/80
                                  focus:outline-2 focus:outline-primary focus:outline-offset-2
                                  transition-all duration-200 text-sm font-medium">
                            <i class="fas fa-folder-open" aria-hidden="true"></i>
                            <span>Bekijk dossier</span>
                        </a>
                        <a href="/open-overheid/documents/{{ $dossier->external_id }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                  bg-surface-variant text-on-surface-variant border border-outline-variant
                                  hover:bg-surface-variant/80
                                  focus:outline-2 focus:outline-primary focus:outline-offset-2
                                  transition-all duration-200 text-sm font-medium">
                            <i class="fas fa-file-alt" aria-hidden="true"></i>
                            <span>Bekijk document</span>
                        </a>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $dossiers->links() }}
    </div>
    @else
    <div class="bg-surface-variant rounded-xl p-12 text-center border border-outline-variant">
        <i class="fas fa-folder-open text-4xl text-on-surface-variant/50 mb-4" aria-hidden="true"></i>
        <h2 class="text-title-large font-medium text-on-surface mb-2">Geen dossiers gevonden</h2>
        <p class="text-body-medium text-on-surface-variant">
            Er zijn momenteel geen documenten met dossierrelaties beschikbaar.
        </p>
    </div>
    @endif
</div>

@push('scripts')
<script>
    const filterOptions = {
        organisatie: @json($filterOptions['organisatie'] ?? []),
        thema: @json($filterOptions['thema'] ?? []),
        documentsoort: @json($filterOptions['documentsoort'] ?? []),
        informatiecategorie: @json($filterOptions['informatiecategorie'] ?? []),
    };

    function filterDossiers(query) {
        const resultsDiv = document.getElementById('dossier-filter-results');
        const container = document.getElementById('dossiers-container');
        
        if (!query || query.length < 2) {
            if (resultsDiv) resultsDiv.classList.add('hidden');
            // Show all dossiers when query is empty
            if (container) {
                container.querySelectorAll('.dossier-item').forEach(item => {
                    item.style.display = '';
                });
            }
            return;
        }

        const lowerQuery = query.toLowerCase();
        const matches = [];

        // Search in filter options
        filterOptions.organisatie.forEach(org => {
            if (org.toLowerCase().includes(lowerQuery)) {
                matches.push({
                    type: 'organisatie',
                    label: org,
                    value: org,
                    secondary: 'Organisatie'
                });
            }
        });

        filterOptions.thema.forEach(theme => {
            if (theme.toLowerCase().includes(lowerQuery)) {
                matches.push({
                    type: 'thema',
                    label: theme,
                    value: theme,
                    secondary: 'Thema'
                });
            }
        });

        filterOptions.documentsoort.forEach(type => {
            if (type.toLowerCase().includes(lowerQuery)) {
                matches.push({
                    type: 'documentsoort',
                    label: type,
                    value: type,
                    secondary: 'Documentsoort'
                });
            }
        });

        filterOptions.informatiecategorie.forEach(category => {
            if (category.toLowerCase().includes(lowerQuery)) {
                matches.push({
                    type: 'informatiecategorie',
                    label: category,
                    value: category,
                    secondary: 'Informatiecategorie'
                });
            }
        });

        // Show filter suggestions
        if (resultsDiv) {
            if (matches.length === 0) {
                resultsDiv.innerHTML = '<div class="px-4 py-3 text-body-medium text-on-surface-variant">Geen resultaten gevonden</div>';
                resultsDiv.classList.remove('hidden');
            } else {
                const limitedMatches = matches.slice(0, 8);
                resultsDiv.innerHTML = limitedMatches.map(match => {
                    const escapedValue = match.value.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
                    const escapedLabel = match.label.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
                    return `
                    <button onclick="applyDossierFilter('${match.type}', '${escapedValue}')" 
                            class="w-full text-left block px-4 py-3 hover:bg-surface-variant transition-colors duration-150 border-b border-outline-variant last:border-b-0 focus:outline-2 focus:outline-primary focus:outline-offset-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-body-medium font-medium text-on-surface">${escapedLabel}</div>
                                <div class="text-label-medium text-on-surface-variant">${match.secondary}</div>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-on-surface-variant" aria-hidden="true"></i>
                        </div>
                    </button>
                `;
                }).join('');
                resultsDiv.classList.remove('hidden');
            }
        }

        // Filter dossiers in real-time
        if (container) {
            let visibleCount = 0;
            container.querySelectorAll('.dossier-item').forEach(item => {
                const title = (item.dataset.title || '').toLowerCase();
                const description = (item.dataset.description || '').toLowerCase();
                const organisation = (item.dataset.organisation || '').toLowerCase();
                const theme = (item.dataset.theme || '').toLowerCase();
                const documentType = (item.dataset.documentType || '').toLowerCase();
                const category = (item.dataset.category || '').toLowerCase();

                const matches = title.includes(lowerQuery) ||
                              description.includes(lowerQuery) ||
                              organisation.includes(lowerQuery) ||
                              theme.includes(lowerQuery) ||
                              documentType.includes(lowerQuery) ||
                              category.includes(lowerQuery);

                if (matches) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show message if no results
            let noResultsMsg = container.querySelector('.no-results-message');
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-results-message bg-surface-variant rounded-xl p-12 text-center border border-outline-variant';
                    noResultsMsg.innerHTML = `
                        <i class="fas fa-search text-4xl text-on-surface-variant/50 mb-4" aria-hidden="true"></i>
                        <h2 class="text-title-large font-medium text-on-surface mb-2">Geen dossiers gevonden</h2>
                        <p class="text-body-medium text-on-surface-variant">
                            Probeer andere zoektermen of gebruik de filteropties hierboven.
                        </p>
                    `;
                    container.appendChild(noResultsMsg);
                }
                noResultsMsg.style.display = 'block';
            } else {
                if (noResultsMsg) {
                    noResultsMsg.style.display = 'none';
                }
            }
        }
    }

    function applyDossierFilter(type, value) {
        // Set the filter input value
        const input = document.getElementById('dossier-quick-filter');
        if (input) {
            input.value = value;
            filterDossiers(value);
        }
        
        // Hide results
        const resultsDiv = document.getElementById('dossier-filter-results');
        if (resultsDiv) {
            resultsDiv.classList.add('hidden');
        }
    }

    // Close filter results when clicking outside
    document.addEventListener('click', function(event) {
        const quickFilter = document.getElementById('dossier-quick-filter');
        const resultsDiv = document.getElementById('dossier-filter-results');
        if (quickFilter && resultsDiv && !quickFilter.contains(event.target) && !resultsDiv.contains(event.target)) {
            resultsDiv.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
