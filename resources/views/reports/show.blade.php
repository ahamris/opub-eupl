@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center text-sm font-medium text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-[var(--color-on-surface-variant)]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-[var(--color-on-surface)] md:ml-2">{{ $organisation }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[var(--color-on-surface)] mb-2">{{ $organisation }}</h1>
            <div class="flex items-center gap-4 text-sm text-[var(--color-on-surface-variant)]">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Laatste publicatie: {{ $lastPublication ? \Carbon\Carbon::parse($lastPublication)->format('d-m-Y') : 'Onbekend' }}
                </span>
            </div>
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <!-- Live Search -->
            <div class="relative w-full sm:w-64" x-data="{
                query: '',
                results: [],
                isOpen: false,
                isLoading: false,
                search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.isOpen = false;
                        return;
                    }
                    this.isLoading = true;
                    fetch('{{ route('reports.search') }}?query=' + encodeURIComponent(this.query))
                        .then(response => response.json())
                        .then(data => {
                            this.results = data;
                            this.isOpen = true;
                            this.isLoading = false;
                        });
                }
            }" @click.away="isOpen = false">
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="query" 
                        @input.debounce.300ms="search()" 
                        placeholder="Zoek andere organisatie..." 
                        class="w-full pl-9 pr-4 py-2 text-sm border border-[var(--color-outline-variant)] rounded-md focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] bg-[var(--color-surface)] text-[var(--color-on-surface)]"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-[var(--color-on-surface-variant)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div x-show="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-[var(--color-primary)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Dropdown Results -->
                <div x-show="isOpen && results.length > 0" class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-y-auto" style="display: none;">
                    <ul class="py-1 divide-y divide-gray-100">
                        <template x-for="result in results" :key="result.organisation">
                            <li>
                                <a :href="'/rapporten/' + encodeURIComponent(result.organisation)" class="block px-4 py-2 hover:bg-[var(--color-surface-variant)] transition-colors">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-[var(--color-on-surface)]" x-text="result.organisation"></span>
                                        <span class="text-xs font-semibold text-[var(--color-primary)] bg-[var(--color-surface-variant)] px-2 py-0.5 rounded-full" x-text="'#' + result.rank"></span>
                                    </div>
                                    <div class="text-xs text-[var(--color-on-surface-variant)] mt-0.5" x-text="result.count + ' documenten'"></div>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
                <div x-show="isOpen && results.length === 0 && !isLoading" class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md border border-gray-200 p-4 text-center text-sm text-gray-500" style="display: none;">
                    Geen organisaties gevonden.
                </div>
            </div>

            <a href="{{ route('zoeken') }}?organisatie[]={{ urlencode($organisation) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)] whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Bekijk documenten
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Documents -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6">
            <h3 class="text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Totaal documenten</h3>
            <p class="text-3xl font-bold text-[var(--color-on-surface)]">{{ number_format($totalDocuments, 0, ',', '.') }}</p>
        </div>

        <!-- Last 12 Months -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6">
            <h3 class="text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Laatste 12 maanden</h3>
            <p class="text-3xl font-bold text-[var(--color-on-surface)]">{{ number_format($last12Months, 0, ',', '.') }}</p>
        </div>

        <!-- Total Themes -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6">
            <h3 class="text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Aantal thema's</h3>
            <p class="text-3xl font-bold text-[var(--color-on-surface)]">{{ number_format($totalThemes, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Categories Chart -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6">
            <h2 class="text-xl font-bold text-[var(--color-on-surface)] mb-6">Waar publiceert {{ $organisation }} over?</h2>
            <div id="categories-chart" class="w-full h-[400px]"></div>
        </div>

        <!-- History Chart -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6">
            <h2 class="text-xl font-bold text-[var(--color-on-surface)] mb-6">Publicatie historie (laatste 2 jaar)</h2>
            <div id="history-chart" class="w-full h-[400px]"></div>
        </div>
    </div>

    <!-- Recent Documents -->
    <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-[var(--color-on-surface)]">Meest recente documenten</h2>
            <a href="{{ route('zoeken') }}?organisatie[]={{ urlencode($organisation) }}" class="text-sm text-[var(--color-primary)] hover:underline">Alle documenten bekijken</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs text-[var(--color-on-surface-variant)] uppercase border-b border-[var(--color-outline-variant)]">
                        <th class="py-3 font-medium">Document</th>
                        <th class="py-3 font-medium">Categorie</th>
                        <th class="py-3 font-medium text-right">Datum</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-outline-variant)]">
                    @forelse($recentDocuments as $doc)
                    <tr class="hover:bg-[var(--color-surface-variant)] transition-colors">
                        <td class="py-3 pr-4">
                            <a href="{{ route('document.view', $doc->id) }}" class="text-sm font-medium text-[var(--color-on-surface)] hover:text-[var(--color-primary)] block truncate max-w-md">
                                {{ $doc->title }}
                            </a>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[var(--color-primary-light)] text-[var(--color-primary-dark)]">
                                {{ $doc->category }}
                            </span>
                        </td>
                        <td class="py-3 text-right text-sm text-[var(--color-on-surface-variant)]">
                            {{ $doc->publication_date ? \Carbon\Carbon::parse($doc->publication_date)->format('d-m-Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-sm text-[var(--color-on-surface-variant)]">Geen recente documenten gevonden.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Categories Chart (Stacked Bar)
        @if($categories->isNotEmpty())
        var categoriesOptions = {
            series: [{
                name: 'Documenten',
                data: @json($categories->pluck('count'))
            }],
            chart: {
                type: 'bar',
                height: 400,
                stacked: true,
                fontFamily: 'Inter, system-ui, sans-serif',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: {
                        total: {
                            enabled: true,
                            offsetX: 10,
                            style: {
                                fontSize: '13px',
                                fontWeight: 900
                            }
                        }
                    }
                },
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: @json($categories->pluck('category')),
                labels: {
                    formatter: function (val) {
                        return val.toLocaleString('nl-NL');
                    },
                    style: { colors: '#666666', fontSize: '12px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#666666', fontSize: '12px' },
                    maxWidth: 200
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toLocaleString('nl-NL') + " documenten";
                    }
                }
            },
            fill: {
                opacity: 1
            },
            colors: ['#4573d5'],
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                offsetX: 40
            },
            grid: {
                borderColor: '#e5e5e5',
                strokeDashArray: 4,
            }
        };

        var categoriesChart = new ApexCharts(document.querySelector("#categories-chart"), categoriesOptions);
        categoriesChart.render();
        @endif

        // History Chart (Column)
        @if($history->isNotEmpty())
        var historyOptions = {
            series: [{
                name: 'Documenten',
                data: @json($history->pluck('count'))
            }],
            chart: {
                type: 'bar',
                height: 400,
                fontFamily: 'Inter, system-ui, sans-serif',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                }
            },
            colors: ['#4573d5'],
            dataLabels: { enabled: false },
            xaxis: {
                categories: @json($history->pluck('month')),
                labels: {
                    style: { colors: '#666666', fontSize: '12px' },
                    rotate: -45
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#666666', fontSize: '12px' }
                }
            },
            grid: {
                borderColor: '#e5e5e5',
                strokeDashArray: 4,
            }
        };

        var historyChart = new ApexCharts(document.querySelector("#history-chart"), historyOptions);
        historyChart.render();
        @endif
    });
</script>
@endpush
@endsection
