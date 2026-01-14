@extends('layouts.app')

@section('title', 'Open Overheid in cijfers - Rapportage')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />
@endpush

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'In cijfers', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <!-- Premium Page Header Section with Filter -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="reports-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#reports-header-grid)" />
            </svg>
            <!-- Fade overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
        </div>
        
        <!-- Animated floating squares -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-8 right-[15%] w-16 h-16 rounded-md bg-[var(--color-purple)]/[0.04] animate-[float-slow_6s_ease-in-out_infinite]"></div>
            <div class="absolute top-16 left-[10%] w-12 h-12 rounded-md bg-[var(--color-primary)]/[0.03] animate-[float-slower_8s_ease-in-out_infinite]"></div>
            <div class="absolute top-1/2 right-[8%] w-20 h-20 rounded-md bg-[var(--color-purple)]/[0.05] animate-[float-slow_6s_ease-in-out_infinite_-2s]"></div>
            <div class="absolute bottom-12 left-[20%] w-14 h-14 rounded-md bg-[var(--color-primary)]/[0.04] animate-[float-slower_8s_ease-in-out_infinite_-3s]"></div>
            <div class="absolute top-12 right-[35%] w-10 h-10 rounded-md bg-[var(--color-purple)]/[0.03] animate-[float-slow_6s_ease-in-out_infinite_-1s]"></div>
        </div>
        
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12 sm:py-16 relative z-10">
            <!-- Breadcrumb -->
            @if(!empty($breadcrumbs))
            <div class="mb-6">
                <x-breadcrumbs :items="$breadcrumbs" />
            </div>
            @endif
            
            <div class="mx-auto max-w-2xl lg:mx-0">
                <p class="text-sm font-medium uppercase">Statistieken & Rapportage</p>
                <h1 class="mt-2 font-semibold">Open Overheid in cijfers</h1>
                <p class="mt-4 text-base text-[var(--color-on-surface-variant)] leading-relaxed">
                    Op deze pagina zie je statistieken over actief openbaar gemaakte overheidsdocumenten.
                </p>
            </div>
            
            <!-- Filter Form -->
            <div class="mx-auto mt-8 w-full">
                <form method="GET" action="{{ route('reports.index') }}" class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 items-end w-full">
                        <div class="w-full col-span-1 md:col-span-2">
                            <label for="daterange" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Periode</label>
                            <div class="relative">
                                <input type="text" id="daterange" name="daterange" class="w-full px-3 py-2 rounded-md border border-slate-200 bg-white text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)] pl-10 cursor-pointer" autocomplete="off">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="hidden" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                            <input type="hidden" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                        {{-- Organisatie Searchable Combobox --}}
                        <div class="w-full" x-data="{
                            allOptions: [
                                { label: 'Alle organisaties', value: '' },
                                @foreach($allOrganisations ?? [] as $org)
                                { label: '{{ addslashes($org) }}', value: '{{ addslashes($org) }}' },
                                @endforeach
                            ],
                            options: [],
                            isOpen: false,
                            openedWithKeyboard: false,
                            selectedOption: null,
                            setSelectedOption(option) {
                                this.selectedOption = option;
                                this.isOpen = false;
                                this.openedWithKeyboard = false;
                                this.$refs.hiddenTextField.value = option.value;
                            },
                            getFilteredOptions(query) {
                                this.options = this.allOptions.filter((option) =>
                                    option.label.toLowerCase().includes(query.toLowerCase()),
                                );
                                if (this.options.length === 0) {
                                    this.$refs.noResultsMessage.classList.remove('hidden');
                                } else {
                                    this.$refs.noResultsMessage.classList.add('hidden');
                                }
                            },
                            handleKeydownOnOptions(event) {
                                if ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode === 8) {
                                    this.$refs.searchField.focus();
                                }
                            },
                            init() {
                                this.options = this.allOptions;
                                const preselected = '{{ $selectedOrganisation ?? '' }}';
                                if (preselected) {
                                    this.selectedOption = this.allOptions.find(o => o.value === preselected) || null;
                                }
                            }
                        }" x-on:keydown="handleKeydownOnOptions($event)" x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false" x-init="init()">
                            <label for="organisatie" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Organisatie</label>
                            <div class="relative">
                                {{-- Trigger button --}}
                                <button type="button" class="inline-flex w-full items-center justify-between gap-2 border border-slate-200 rounded-md bg-white px-3 py-2 text-sm font-medium tracking-wide text-[var(--color-on-surface)] transition hover:opacity-75 focus:outline-none" role="combobox" aria-controls="organisatieList" aria-haspopup="listbox" x-on:click="isOpen = ! isOpen" x-on:keydown.down.prevent="openedWithKeyboard = true" x-on:keydown.enter.prevent="openedWithKeyboard = true" x-on:keydown.space.prevent="openedWithKeyboard = true" x-bind:aria-expanded="isOpen || openedWithKeyboard" x-bind:aria-label="selectedOption ? selectedOption.label : 'Alle organisaties'">
                                    <span class="text-sm font-normal truncate" x-text="selectedOption ? selectedOption.label : 'Alle organisaties'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                {{-- Hidden Input --}}
                                <input id="organisatie" name="organisatie" x-ref="hiddenTextField" hidden="" :value="selectedOption ? selectedOption.value : ''"/>
                                
                                <div x-show="isOpen || openedWithKeyboard" id="organisatieList" class="absolute left-0 top-11 z-20 w-full overflow-hidden rounded-md border border-slate-200 bg-white shadow-lg" role="listbox" aria-label="organisatie list" x-on:click.outside="isOpen = false, openedWithKeyboard = false" x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition x-trap="openedWithKeyboard">
                                    {{-- Search --}}
                                    <div class="relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5" class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-[var(--color-on-surface-variant)]/50" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                        </svg>
                                        <input type="text" class="w-full border-b border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-slate-200 focus:ring-0 disabled:cursor-not-allowed disabled:opacity-75" name="searchFieldOrg" aria-label="Zoeken" x-on:input="getFilteredOptions($el.value)" x-ref="searchField" placeholder="Zoeken..." />
                                    </div>

                                    {{-- Options --}}
                                    <ul class="flex max-h-44 flex-col overflow-y-auto">
                                        <li class="hidden px-3 py-2 text-sm text-[var(--color-on-surface-variant)]" x-ref="noResultsMessage">
                                            <span>Geen resultaten gevonden</span>
                                        </li>
                                        <template x-for="(item, index) in options" x-bind:key="item.value">
                                            <li class="combobox-option inline-flex justify-between gap-4 bg-white px-3 py-2 text-sm text-[var(--color-on-surface)] hover:bg-slate-50 hover:text-[var(--color-primary)] focus-visible:bg-slate-50 focus-visible:text-[var(--color-primary)] focus-visible:outline-none cursor-pointer" role="option" x-on:click="setSelectedOption(item)" x-on:keydown.enter="setSelectedOption(item)" x-bind:id="'org-option-' + index" tabindex="0">
                                                <span x-bind:class="selectedOption && selectedOption.value == item.value ? 'font-semibold' : null" x-text="item.label" class="truncate"></span>
                                                <span class="sr-only" x-text="selectedOption && selectedOption.value == item.value ? 'selected' : null"></span>
                                                <svg x-cloak x-show="selectedOption && selectedOption.value == item.value" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" class="size-4 text-[var(--color-primary)] shrink-0" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                                </svg>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Categorie Searchable Combobox --}}
                        <div class="w-full" x-data="{
                            allOptions: [
                                { label: 'Alle categorieën', value: '' },
                                @foreach($allCategories ?? [] as $cat)
                                { label: '{{ addslashes($cat['label']) }}', value: '{{ addslashes($cat['value']) }}' },
                                @endforeach
                            ],
                            options: [],
                            isOpen: false,
                            openedWithKeyboard: false,
                            selectedOption: null,
                            setSelectedOption(option) {
                                this.selectedOption = option;
                                this.isOpen = false;
                                this.openedWithKeyboard = false;
                                this.$refs.hiddenTextField.value = option.value;
                            },
                            getFilteredOptions(query) {
                                this.options = this.allOptions.filter((option) =>
                                    option.label.toLowerCase().includes(query.toLowerCase()),
                                );
                                if (this.options.length === 0) {
                                    this.$refs.noResultsMessage.classList.remove('hidden');
                                } else {
                                    this.$refs.noResultsMessage.classList.add('hidden');
                                }
                            },
                            handleKeydownOnOptions(event) {
                                if ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode === 8) {
                                    this.$refs.searchField.focus();
                                }
                            },
                            init() {
                                this.options = this.allOptions;
                                const preselected = '{{ $selectedCategory ?? '' }}';
                                if (preselected) {
                                    this.selectedOption = this.allOptions.find(o => o.value === preselected) || null;
                                }
                            }
                        }" x-on:keydown="handleKeydownOnOptions($event)" x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false" x-init="init()">
                            <label for="informatiecategorie" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Categorie</label>
                            <div class="relative">
                                {{-- Trigger button --}}
                                <button type="button" class="inline-flex w-full items-center justify-between gap-2 border border-slate-200 rounded-md bg-white px-3 py-2 text-sm font-medium tracking-wide text-[var(--color-on-surface)] transition hover:opacity-75 focus:outline-none" role="combobox" aria-controls="categorieList" aria-haspopup="listbox" x-on:click="isOpen = ! isOpen" x-on:keydown.down.prevent="openedWithKeyboard = true" x-on:keydown.enter.prevent="openedWithKeyboard = true" x-on:keydown.space.prevent="openedWithKeyboard = true" x-bind:aria-expanded="isOpen || openedWithKeyboard" x-bind:aria-label="selectedOption ? selectedOption.label : 'Alle categorieën'">
                                    <span class="text-sm font-normal truncate" x-text="selectedOption ? selectedOption.label : 'Alle categorieën'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                {{-- Hidden Input --}}
                                <input id="informatiecategorie" name="informatiecategorie" x-ref="hiddenTextField" hidden="" :value="selectedOption ? selectedOption.value : ''"/>
                                
                                <div x-show="isOpen || openedWithKeyboard" id="categorieList" class="absolute left-0 top-11 z-20 w-full overflow-hidden rounded-md border border-slate-200 bg-white shadow-lg" role="listbox" aria-label="categorie list" x-on:click.outside="isOpen = false, openedWithKeyboard = false" x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition x-trap="openedWithKeyboard">
                                    {{-- Search --}}
                                    <div class="relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5" class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-[var(--color-on-surface-variant)]/50" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                        </svg>
                                        <input type="text" class="w-full border-b border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-slate-200 focus:ring-0 disabled:cursor-not-allowed disabled:opacity-75" name="searchFieldCat" aria-label="Zoeken" x-on:input="getFilteredOptions($el.value)" x-ref="searchField" placeholder="Zoeken..." />
                                    </div>

                                    {{-- Options --}}
                                    <ul class="flex max-h-44 flex-col overflow-y-auto">
                                        <li class="hidden px-3 py-2 text-sm text-[var(--color-on-surface-variant)]" x-ref="noResultsMessage">
                                            <span>Geen resultaten gevonden</span>
                                        </li>
                                        <template x-for="(item, index) in options" x-bind:key="item.value">
                                            <li class="combobox-option inline-flex justify-between gap-4 bg-white px-3 py-2 text-sm text-[var(--color-on-surface)] hover:bg-slate-50 hover:text-[var(--color-primary)] focus-visible:bg-slate-50 focus-visible:text-[var(--color-primary)] focus-visible:outline-none cursor-pointer" role="option" x-on:click="setSelectedOption(item)" x-on:keydown.enter="setSelectedOption(item)" x-bind:id="'cat-option-' + index" tabindex="0">
                                                <span x-bind:class="selectedOption && selectedOption.value == item.value ? 'font-semibold' : null" x-text="item.label" class="truncate"></span>
                                                <span class="sr-only" x-text="selectedOption && selectedOption.value == item.value ? 'selected' : null"></span>
                                                <svg x-cloak x-show="selectedOption && selectedOption.value == item.value" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" class="size-4 text-[var(--color-primary)] shrink-0" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                                </svg>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Thema Searchable Combobox --}}
                        <div class="w-full" x-data="{
                            allOptions: [
                                { label: 'Alle thema\'s', value: '' },
                                @foreach($allThemes ?? [] as $theme)
                                { label: '{{ addslashes($theme) }}', value: '{{ addslashes($theme) }}' },
                                @endforeach
                            ],
                            options: [],
                            isOpen: false,
                            openedWithKeyboard: false,
                            selectedOption: null,
                            setSelectedOption(option) {
                                this.selectedOption = option;
                                this.isOpen = false;
                                this.openedWithKeyboard = false;
                                this.$refs.hiddenTextField.value = option.value;
                            },
                            getFilteredOptions(query) {
                                this.options = this.allOptions.filter((option) =>
                                    option.label.toLowerCase().includes(query.toLowerCase()),
                                );
                                if (this.options.length === 0) {
                                    this.$refs.noResultsMessage.classList.remove('hidden');
                                } else {
                                    this.$refs.noResultsMessage.classList.add('hidden');
                                }
                            },
                            handleKeydownOnOptions(event) {
                                if ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode === 8) {
                                    this.$refs.searchField.focus();
                                }
                            },
                            init() {
                                this.options = this.allOptions;
                                const preselected = '{{ $selectedTheme ?? '' }}';
                                if (preselected) {
                                    this.selectedOption = this.allOptions.find(o => o.value === preselected) || null;
                                }
                            }
                        }" x-on:keydown="handleKeydownOnOptions($event)" x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false" x-init="init()">
                            <label for="thema" class="block text-sm font-medium text-[var(--color-on-surface-variant)] mb-2">Thema</label>
                            <div class="relative">
                                {{-- Trigger button --}}
                                <button type="button" class="inline-flex w-full items-center justify-between gap-2 border border-slate-200 rounded-md bg-white px-3 py-2 text-sm font-medium tracking-wide text-[var(--color-on-surface)] transition hover:opacity-75 focus:outline-none" role="combobox" aria-controls="themaList" aria-haspopup="listbox" x-on:click="isOpen = ! isOpen" x-on:keydown.down.prevent="openedWithKeyboard = true" x-on:keydown.enter.prevent="openedWithKeyboard = true" x-on:keydown.space.prevent="openedWithKeyboard = true" x-bind:aria-expanded="isOpen || openedWithKeyboard" x-bind:aria-label="selectedOption ? selectedOption.label : 'Alle thema\'s'">
                                    <span class="text-sm font-normal truncate" x-text="selectedOption ? selectedOption.label : 'Alle thema\'s'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                {{-- Hidden Input --}}
                                <input id="thema" name="thema" x-ref="hiddenTextField" hidden="" :value="selectedOption ? selectedOption.value : ''"/>
                                
                                <div x-show="isOpen || openedWithKeyboard" id="themaList" class="absolute left-0 top-11 z-20 w-full overflow-hidden rounded-md border border-slate-200 bg-white shadow-lg" role="listbox" aria-label="thema list" x-on:click.outside="isOpen = false, openedWithKeyboard = false" x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition x-trap="openedWithKeyboard">
                                    {{-- Search --}}
                                    <div class="relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5" class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-[var(--color-on-surface-variant)]/50" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                        </svg>
                                        <input type="text" class="w-full border-b border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-[var(--color-on-surface)] focus:outline-none focus:border-slate-200 focus:ring-0 disabled:cursor-not-allowed disabled:opacity-75" name="searchFieldThema" aria-label="Zoeken" x-on:input="getFilteredOptions($el.value)" x-ref="searchField" placeholder="Zoeken..." />
                                    </div>

                                    {{-- Options --}}
                                    <ul class="flex max-h-44 flex-col overflow-y-auto">
                                        <li class="hidden px-3 py-2 text-sm text-[var(--color-on-surface-variant)]" x-ref="noResultsMessage">
                                            <span>Geen resultaten gevonden</span>
                                        </li>
                                        <template x-for="(item, index) in options" x-bind:key="item.value">
                                            <li class="combobox-option inline-flex justify-between gap-4 bg-white px-3 py-2 text-sm text-[var(--color-on-surface)] hover:bg-slate-50 hover:text-[var(--color-primary)] focus-visible:bg-slate-50 focus-visible:text-[var(--color-primary)] focus-visible:outline-none cursor-pointer" role="option" x-on:click="setSelectedOption(item)" x-on:keydown.enter="setSelectedOption(item)" x-bind:id="'thema-option-' + index" tabindex="0">
                                                <span x-bind:class="selectedOption && selectedOption.value == item.value ? 'font-semibold' : null" x-text="item.label" class="truncate"></span>
                                                <span class="sr-only" x-text="selectedOption && selectedOption.value == item.value ? 'selected' : null"></span>
                                                <svg x-cloak x-show="selectedOption && selectedOption.value == item.value" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" class="size-4 text-[var(--color-primary)] shrink-0" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                                </svg>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="w-full">
                            <button type="submit" class="w-full rounded-md bg-[var(--color-primary-dark)] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)] transition-colors duration-200">
                                Toepassen
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Bottom gradient line -->
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    </div>
    
    <style>
        @keyframes float-slow { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-12px) rotate(3deg); } }
        @keyframes float-slower { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-8px) rotate(-2deg); } }
    </style>

    <!-- Main Statistics Section -->
    <div class="bg-[var(--color-surface)] py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-primary)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-file-alt text-[var(--color-primary)] text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">Totaal documenten</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-primary)] mb-1">
                        {{ number_format($totalDocuments, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/70">
                        In geselecteerde periode
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-primary-dark)]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-check-circle text-[var(--color-primary-dark)] text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]">Afgehandeld</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-primary-dark)] mb-1">
                        {{ number_format($documentsWithDecision, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/70">
                        Met publicatiedatum
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6 opacity-60 cursor-not-allowed relative" title="Deze functie komt binnenkort beschikbaar">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-outline-variant)]/30 flex items-center justify-center shrink-0">
                            <i class="fas fa-clock text-[var(--color-on-surface-variant)]/50 text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]/60">In behandeling</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-on-surface-variant)]/50 mb-1">
                        —
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/50">
                        Binnenkort beschikbaar
                    </p>
                </div>

                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6 opacity-60 cursor-not-allowed relative" title="Deze functie komt binnenkort beschikbaar">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-md bg-[var(--color-outline-variant)]/30 flex items-center justify-center shrink-0">
                            <i class="fas fa-chart-line text-[var(--color-on-surface-variant)]/50 text-base" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-semibold text-[var(--color-on-surface)]/60">Gemiddelde tijd</h3>
                    </div>
                    <p class="text-3xl font-bold text-[var(--color-on-surface-variant)]/50 mb-1">
                        —
                    </p>
                    <p class="text-sm text-[var(--color-on-surface-variant)]/50">
                        Binnenkort beschikbaar
                    </p>
                </div>
            </div>

            <!-- Quarterly Organisation Chart with ApexCharts -->
            @if(!empty($quarterlyOrgData['series']))
            <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6 mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-[var(--color-on-surface)]">Documenten per organisatie per kwartaal</h2>
                    <span class="text-sm text-[var(--color-on-surface-variant)]">{{ $year }}</span>
                </div>
                <div id="quarterly-org-chart" class="w-full" style="min-height: 350px;"></div>
            </div>
            @endif

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
                <!-- Documents per Organisation -->
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-[var(--color-on-surface)]">Documenten per organisatie</h2>
                        <button class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium focus:outline-none transition-colors duration-200">
                            Toon datatabel
                        </button>
                    </div>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @php
                            $maxOrgCount = !empty($documentsPerOrganisation) ? max(array_column($documentsPerOrganisation, 'count')) : 0;
                        @endphp
                        @forelse(array_slice($documentsPerOrganisation, 0, 10) as $item)
                        <div class="flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                @php
                                    $orgUrl = route('zoeken') . '?organisatie[]=' . urlencode($item['organisation']);
                                @endphp
                                <a href="{{ $orgUrl }}" class="text-sm font-medium text-[var(--color-on-surface)] mb-1.5 truncate hover:text-[var(--color-primary)] transition-colors duration-200 block">
                                    {{ $item['organisation'] }}
                                </a>
                                <div class="w-full bg-[var(--color-outline-variant)]/30 rounded-full h-2">
                                    <div class="bg-[var(--color-primary)] h-2 rounded-full transition-all duration-700" style="width: {{ $maxOrgCount > 0 ? min(($item['count'] / $maxOrgCount) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-lg font-bold text-[var(--color-primary)]">{{ number_format($item['count'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-[var(--color-on-surface-variant)]/70">Geen gegevens beschikbaar voor deze periode.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Documents per Category (Donut Chart) -->
                <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-[var(--color-on-surface)]">Documenten per categorie</h2>
                    </div>
                    <div id="category-donut-chart" class="w-full flex justify-center" style="min-height: 350px;"></div>
                </div>
            </div>

            <!-- Monthly Trend -->
            @if(!empty($monthlyTrend))
            <div class="bg-[var(--color-surface)] border border-[var(--color-outline-variant)] rounded-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-[var(--color-on-surface)]">Maandelijkse trend</h2>
                    <button class="text-sm text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] font-medium focus:outline-none transition-colors duration-200">
                        Toon datatabel
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-12 gap-4">
                    @foreach($monthlyTrend as $index => $month)
                    <div class="text-center">
                        <p class="text-xs text-[var(--color-on-surface-variant)]/70 mb-2">{{ $month['monthName'] }}</p>
                        <div class="{{ $index % 2 === 0 ? 'bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20' : 'bg-[var(--color-primary-dark)]/10 border border-[var(--color-primary-dark)]/20' }} rounded-md p-3">
                            <p class="text-lg font-bold {{ $index % 2 === 0 ? 'text-[var(--color-primary)]' : 'text-[var(--color-primary-dark)]' }}">{{ number_format($month['count'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get CSS variable colors
            const computedStyle = getComputedStyle(document.documentElement);
            const primaryColor = computedStyle.getPropertyValue('--color-primary').trim() || '#2563eb';
            const primaryDarkColor = computedStyle.getPropertyValue('--color-primary-dark').trim() || '#1e40af';
            
            // Color palette
            const colors = [
                primaryColor,
                primaryDarkColor,
                '#10b981', // emerald
                '#f59e0b', // amber
                '#8b5cf6', // violet
                '#ec4899', // pink
                '#06b6d4', // cyan
                '#f97316', // orange
            ];

            // Quarterly Chart
            @if(!empty($quarterlyOrgData['series']))
            var quarterlyOptions = {
                series: @json($quarterlyOrgData['series']),
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Inter, system-ui, sans-serif',
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 4,
                        borderRadiusApplication: 'end',
                    },
                },
                dataLabels: { enabled: false },
                colors: colors,
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: @json($quarterlyOrgData['categories']),
                    labels: { style: { colors: '#64748b', fontSize: '12px' } }
                },
                yaxis: {
                    title: { text: 'Aantal documenten', style: { color: '#64748b', fontSize: '12px', fontWeight: 500 } },
                    labels: {
                        style: { colors: '#64748b', fontSize: '12px' },
                        formatter: function(val) { return val.toLocaleString('nl-NL'); }
                    }
                },
                fill: { opacity: 1 },
                tooltip: {
                    y: { formatter: function(val) { return val.toLocaleString('nl-NL') + ' documenten'; } }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '12px',
                    fontWeight: 500,
                    markers: { width: 12, height: 12, radius: 3 },
                    itemMargin: { horizontal: 12, vertical: 8 }
                },
                grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
                responsive: [{
                    breakpoint: 640,
                    options: {
                        chart: { height: 300 },
                        legend: { position: 'bottom', fontSize: '10px' }
                    }
                }]
            };

            var quarterlyChart = new ApexCharts(document.querySelector("#quarterly-org-chart"), quarterlyOptions);
            quarterlyChart.render();
            @endif

            // Category Donut Chart
            @if(!empty($documentsPerCategory))
                @php
                    $catLabels = array_column($documentsPerCategory, 'label');
                    $catCounts = array_column($documentsPerCategory, 'count');
                @endphp
                
                var donutOptions = {
                    series: @json($catCounts),
                    labels: @json($catLabels),
                    chart: {
                        type: 'donut',
                        width: '100%',
                        height: 350,
                        fontFamily: 'Inter, system-ui, sans-serif',
                    },
                    dataLabels: { enabled: false },
                    colors: colors,
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '14px',
                                        fontFamily: 'Inter, system-ui, sans-serif',
                                        color: '#64748b',
                                        offsetY: -10
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '16px',
                                        fontFamily: 'Inter, system-ui, sans-serif',
                                        fontWeight: 600,
                                        color: '#0f172a',
                                        offsetY: 16,
                                        formatter: function (val) {
                                            return val.toLocaleString('nl-NL')
                                        }
                                    },
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        label: 'Totaal',
                                        fontSize: '14px',
                                        fontFamily: 'Inter, system-ui, sans-serif',
                                        fontWeight: 600,
                                        color: '#64748b',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0).toLocaleString('nl-NL')
                                        }
                                    }
                                }
                            }
                        }
                    },
                    legend: { show: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: { height: 300 },
                        }
                    }]
                };

                var donutChart = new ApexCharts(document.querySelector("#category-donut-chart"), donutOptions);
                donutChart.render();
            @endif
        });
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                "opens": "right",
                "drops": "auto",
                "applyButtonClasses": "bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-dark)]",
                "cancelButtonClasses": "bg-slate-100 text-slate-700 hover:bg-slate-200"
            }, function(start, end, label) {
                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));
            });
        });
    </script>
    @endpush
    @endsection
