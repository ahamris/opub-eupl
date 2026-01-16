@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[var(--color-on-surface)] mb-2">Open Overheid in één oogopslag</h1>
            <p class="text-[var(--color-on-surface-variant)]">Open MA-SZW monitort en visualiseert de voortgang van openbaarmakingen.</p>
        </div>
        
        <!-- Date Range Picker -->
        <div class="w-full md:w-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <input type="text" id="daterange" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring focus:ring-[var(--color-primary)] focus:ring-opacity-50" placeholder="Selecteer periode">
            </div>
            <form id="filter-form" action="{{ route('reports.index') }}" method="GET" class="hidden">
                <input type="hidden" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}">
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="relative overflow-hidden rounded-lg bg-[var(--color-surface)] px-4 pt-5 pb-12 sm:px-6 sm:pt-6 border border-[var(--color-outline-variant)]">
            <dt>
            <dt>
                <div class="absolute rounded-md bg-[var(--color-primary)] p-3">
                    <svg class="h-6 w-6 text-[var(--color-on-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-[var(--color-on-surface-variant)]">Totaal documenten</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-[var(--color-on-surface)]">{{ number_format($totalDocuments, 0, ',', '.') }}</p>
            </dd>
            <div class="absolute inset-x-0 bottom-0 bg-[var(--color-surface-variant)] px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <span class="font-medium text-[var(--color-primary)]">Geselecteerde periode</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-lg bg-[var(--color-surface)] px-4 pt-5 pb-12 sm:px-6 sm:pt-6 border border-[var(--color-outline-variant)]">
            <dt>
            <dt>
                <div class="absolute rounded-md bg-[var(--color-purple)] p-3">
                    <svg class="h-6 w-6 text-[var(--color-on-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-[var(--color-on-surface-variant)]">Actieve organisaties</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-[var(--color-on-surface)]">{{ number_format($activeOrganisationsCount ?? 0, 0, ',', '.') }}</p>
            </dd>
            <div class="absolute inset-x-0 bottom-0 bg-[var(--color-surface-variant)] px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <span class="font-medium text-[var(--color-primary)]">Met publicaties</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-lg bg-[var(--color-surface)] px-4 pt-5 pb-12 sm:px-6 sm:pt-6 border border-[var(--color-outline-variant)]">
            <dt>
            <dt>
                <div class="absolute rounded-md bg-[var(--color-primary-light)] p-3">
                    <svg class="h-6 w-6 text-[var(--color-on-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-[var(--color-on-surface-variant)]">Aantal thema's</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-[var(--color-on-surface)]">{{ number_format($totalThemesCount ?? 0, 0, ',', '.') }}</p>
            </dd>
            <div class="absolute inset-x-0 bottom-0 bg-[var(--color-surface-variant)] px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <span class="font-medium text-[var(--color-primary)]">Unieke thema's</span>
                </div>
            </div>
        </div>
    </dl>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Hall of Fame -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6 flex flex-col h-full">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <h2 class="text-xl font-bold text-[var(--color-on-surface)]">Hall of Fame</h2>
                
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
                            placeholder="Zoek organisatie..." 
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
                                    <a :href="'/rapporten/' + encodeURIComponent(result.organisation)" class="block px-4 py-2 hover:bg-gray-50 transition-colors">
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
            </div>

            <div class="overflow-x-auto flex-grow">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-[var(--color-on-surface-variant)] uppercase tracking-wider border-b border-[var(--color-outline-variant)]">
                            <th class="pb-3 pl-2">#</th>
                            <th class="pb-3">Organisatie</th>
                            <th class="pb-3 text-right">Aantal</th>
                            <th class="pb-3 text-right">Actie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach(array_slice($documentsPerOrganisation, 0, 10) as $index => $item)
                        <tr class="hover:bg-[var(--color-surface-variant)] transition-colors">
                            <td class="py-3 pl-2 text-sm text-[var(--color-on-surface-variant)]">{{ $index + 1 }}</td>
                            <td class="py-3">
                                <a href="{{ route('reports.show', ['organisation' => urlencode($item['organisation'])]) }}" class="text-sm font-medium text-[var(--color-on-surface)] hover:text-[var(--color-primary)]">
                                    {{ $item['organisation'] }}
                                </a>
                            </td>
                            <td class="py-3 text-right text-sm font-bold text-[var(--color-on-surface)]">
                                {{ number_format($item['count'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('reports.show', ['organisation' => urlencode($item['organisation'])]) }}" class="text-xs text-[var(--color-primary)] hover:underline">
                                    Bekijk
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Themes Treemap -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6">
            <h2 class="text-xl font-bold text-[var(--color-on-surface)] mb-6">Thema verdeling</h2>
            <div id="themes-chart" class="w-full h-[400px]"></div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-[var(--color-on-surface)]">Publicaties over tijd</h2>
            
            <!-- Year Selector -->
            <div class="w-32">
                <select onchange="window.location.href=this.value" class="block w-full rounded-md border-[var(--color-outline-variant)] shadow-sm focus:border-[var(--color-primary)] focus:ring focus:ring-[var(--color-primary)] focus:ring-opacity-50 text-sm bg-[var(--color-surface)] text-[var(--color-on-surface)]">
                    <option value="">Kies jaar</option>
                    @foreach($availableYears as $y)
                    <option value="{{ route('reports.index', ['start_date' => $y.'-01-01', 'end_date' => $y.'-12-31']) }}" {{ $startDate->year == $y && $endDate->year == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="timeline-chart" class="w-full h-[350px]"></div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date Range Picker
        const startDate = "{{ $startDate->format('d-m-Y') }}";
        const endDate = "{{ $endDate->format('d-m-Y') }}";

        $('#daterange').daterangepicker({
            "showDropdowns": true,
            ranges: {
                'Vandaag': [moment(), moment()],
                'Gisteren': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Laatste 7 dagen': [moment().subtract(6, 'days'), moment()],
                'Laatste 30 dagen': [moment().subtract(29, 'days'), moment()],
                'Deze maand': [moment().startOf('month'), moment().endOf('month')],
                'Vorige maand': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Dit jaar': [moment().startOf('year'), moment().endOf('year')],
                'Vorig jaar': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            },
            "locale": {
                "format": "DD-MM-YYYY",
                "separator": " - ",
                "applyLabel": "Toepassen",
                "cancelLabel": "Annuleren",
                "fromLabel": "Van",
                "toLabel": "Tot",
                "customRangeLabel": "Aangepast",
                "weekLabel": "W",
                "daysOfWeek": ["Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za"],
                "monthNames": ["Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December"],
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate": startDate,
            "endDate": endDate,
            "opens": "left",
            "applyButtonClasses": "bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-dark)]",
            "cancelButtonClasses": "bg-slate-100 text-slate-700 hover:bg-slate-200"
        }, function(start, end, label) {
            $('#start_date').val(start.format('YYYY-MM-DD'));
            $('#end_date').val(end.format('YYYY-MM-DD'));
            $('#filter-form').submit();
        });

        // Themes Polar Area Chart
        @if(!empty($documentsPerTheme))
        var themeOriginalCounts = @json(array_column($documentsPerTheme, 'count'));
        var themeLabels = @json(array_column($documentsPerTheme, 'theme'));

        // Calculate log values for display to handle large disparities (e.g. 750 vs 18)
        // We use Math.max(val, 1) to avoid log(0) or negative infinity issues
        var themeLogCounts = themeOriginalCounts.map(function(val) {
            return val > 0 ? Math.log10(Math.max(val, 1)) : 0;
        });

        var polarOptions = {
            series: themeLogCounts,
            labels: themeLabels,
            chart: {
                type: 'polarArea',
                height: 400,
                fontFamily: 'Inter, system-ui, sans-serif',
                toolbar: { show: false }
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            colors: ['#4573d5', '#b500c7', '#64dcff', '#072974', '#ff32ff', '#333333', '#666666', '#e5e5e5'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            legend: {
                position: 'right',
                offsetY: 0
            },
            yaxis: {
                show: false,
            },
            tooltip: {
                y: {
                    formatter: function (val, opts) {
                        // Retrieve the original value using the seriesIndex
                        var originalVal = themeOriginalCounts[opts.seriesIndex];
                        return originalVal.toLocaleString('nl-NL') + " documenten";
                    }
                }
            }
        };

        var polarChart = new ApexCharts(document.querySelector("#themes-chart"), polarOptions);
        polarChart.render();
        @endif

        // Timeline Chart (Area with Datetime X-Axis)
        var timelineOptions = {
            series: [{
                name: 'Documenten',
                data: @json($monthlyTrend)
            }],
            chart: {
                id: 'area-datetime',
                type: 'area',
                height: 350,
                fontFamily: 'Inter, system-ui, sans-serif',
                zoom: {
                    autoScaleYaxis: true
                },
                toolbar: {
                    show: true,
                    tools: {
                        download: false,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            markers: {
                size: 0,
                style: 'hollow',
            },
            xaxis: {
                type: 'datetime',
                tickAmount: 6,
                labels: {
                    style: { colors: '#666666', fontSize: '12px' },
                    datetimeFormatter: {
                        year: 'yyyy',
                        month: 'MMM \'yy',
                        day: 'dd MMM',
                        hour: 'HH:mm'
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#666666', fontSize: '12px' },
                    formatter: function(val) { return val.toLocaleString('nl-NL'); }
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy'
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 100]
                }
            },
            colors: ['#4573d5'],
            grid: {
                borderColor: '#e5e5e5',
                strokeDashArray: 4,
            }
        };

        var timelineChart = new ApexCharts(document.querySelector("#timeline-chart"), timelineOptions);
        timelineChart.render();
    });
</script>
@endpush
@endsection
