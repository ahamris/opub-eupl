@extends('layouts.app')

@section('title', 'Verwijzingen - Open Overheid')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Verwijzingen', 'href' => null, 'current' => true],
    ];
@endphp

@section('content')
    <div class="relative isolate overflow-hidden bg-surface py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h1 class="text-4xl font-semibold tracking-tight text-on-surface sm:text-5xl">
                    Verwijzingen
                </h1>
                <p class="mt-6 text-xl/8 text-on-surface-variant">
                    Links naar andere relevante portalen en websites voor overheidsinformatie.
                </p>
            </div>

            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col">
                        <dt class="text-base font-semibold leading-7 text-on-surface">
                            <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-container">
                                <i class="fas fa-gavel text-xl text-on-primary-container" aria-hidden="true"></i>
                            </div>
                            Wet- en regelgeving
                        </dt>
                        <dd class="mt-1 flex flex-auto flex-col text-base leading-7 text-on-surface-variant">
                            <p class="flex-auto">
                                Officiële publicaties van wet- en regelgeving van de Nederlandse overheid.
                            </p>
                            <p class="mt-6">
                                <a href="https://wetten.overheid.nl" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold leading-6 text-primary hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm inline-flex items-center gap-1.5">
                                    wetten.nl
                                    <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                </a>
                            </p>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-base font-semibold leading-7 text-on-surface">
                            <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-container">
                                <i class="fas fa-search text-xl text-on-primary-container" aria-hidden="true"></i>
                            </div>
                            Woo-index
                        </dt>
                        <dd class="mt-1 flex flex-auto flex-col text-base leading-7 text-on-surface-variant">
                            <p class="flex-auto">
                                Vind contactgegevens van bestuursorganen voor het indienen van Woo-verzoeken.
                            </p>
                            <p class="mt-6">
                                <a href="https://www.woo-index.nl" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold leading-6 text-primary hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm inline-flex items-center gap-1.5">
                                    woo-index.nl
                                    <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                </a>
                            </p>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-base font-semibold leading-7 text-on-surface">
                            <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-container">
                                <i class="fas fa-building text-xl text-on-primary-container" aria-hidden="true"></i>
                            </div>
                            Overheid.nl
                        </dt>
                        <dd class="mt-1 flex flex-auto flex-col text-base leading-7 text-on-surface-variant">
                            <p class="flex-auto">
                                Centrale toegangspoort tot alle informatie van de Nederlandse overheid.
                            </p>
                            <p class="mt-6">
                                <a href="https://www.overheid.nl" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold leading-6 text-primary hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2 rounded-sm inline-flex items-center gap-1.5">
                                    overheid.nl
                                    <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                </a>
                            </p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection

