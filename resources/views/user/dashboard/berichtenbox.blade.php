@extends('user.layouts.dashboard')

@section('dashboard-content')
<!-- Breadcrumb -->
<div class="text-sm text-gray-500 mb-2">
    <a href="{{ route('user.dashboard') }}" class="hover:text-[var(--color-primary)]">Home</a>
    <span class="mx-1">></span>
    <a href="{{ route('user.berichtenbox') }}" class="hover:text-[var(--color-primary)]">Berichtenbox</a>
    <span class="mx-1">></span>
    <span>{{ $tab === 'inbox' ? 'Inbox' : ($tab === 'archief' ? 'Archief' : 'Prullenbak') }}</span>
</div>
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Mijn Berichtenbox</h1>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content -->
    <div class="flex-1">
        <!-- Tabs -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <a href="{{ route('user.berichtenbox') }}" 
                       class="px-6 py-3 text-sm font-medium border-b-2 {{ $tab === 'inbox' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Inbox
                    </a>
                    <a href="{{ route('user.berichtenbox.archief') }}" 
                       class="px-6 py-3 text-sm font-medium border-b-2 {{ $tab === 'archief' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Archief
                    </a>
                    <a href="{{ route('user.berichtenbox.prullenbak') }}" 
                       class="px-6 py-3 text-sm font-medium border-b-2 {{ $tab === 'prullenbak' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Prullenbak
                    </a>
                </nav>
            </div>

            <!-- Messages Table -->
            @if($berichten->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-10 px-4 py-3">
                                <input type="checkbox" class="rounded border-gray-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Afzender</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Onderwerp</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-1 cursor-pointer hover:text-gray-700">
                                    Ontvangen
                                    <i class="fas fa-sort text-gray-400"></i>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($berichten as $bericht)
                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer {{ !$bericht['is_read'] ? 'bg-blue-50/50' : '' }}"
                            onclick="window.location='{{ route('user.bericht.show', $bericht['id']) }}'">
                            <td class="px-4 py-3" onclick="event.stopPropagation()">
                                <input type="checkbox" class="rounded border-gray-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm {{ !$bericht['is_read'] ? 'font-semibold text-gray-900' : 'text-gray-700' }}">
                                    {{ $bericht['afzender'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm {{ !$bericht['is_read'] ? 'font-semibold text-gray-900' : 'text-gray-600' }}">
                                    {{ $bericht['onderwerp'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $bericht['ontvangen']->format('d/m/Y') }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Bulk Actions -->
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                        <input type="checkbox" class="rounded border-gray-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        (de)selecteer alle berichten
                    </label>
                </div>
                <div class="flex items-center gap-3">
                    @if($tab === 'inbox')
                    <button class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1">
                        <i class="fas fa-archive"></i> Verplaats naar archief
                    </button>
                    @endif
                    <button class="text-sm text-gray-600 hover:text-red-600 flex items-center gap-1">
                        <i class="fas fa-trash"></i> Verplaats naar prullenbak
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-center">
                <nav class="flex items-center gap-1">
                    <span class="w-8 h-8 flex items-center justify-center rounded-full bg-[var(--color-primary)] text-white text-sm font-medium">1</span>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-sm text-gray-600">2</a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-sm text-gray-600">3</a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-sm text-gray-600">4</a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-sm text-gray-600">5</a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-sm text-gray-600">6</a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-sm text-gray-600">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </nav>
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-12 h-12 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Geen berichten</h3>
                <p class="text-sm text-gray-500">Er zijn geen berichten in uw {{ $tab === 'inbox' ? 'inbox' : ($tab === 'archief' ? 'archief' : 'prullenbak') }}.</p>
            </div>
            @endif
        </div>

        <!-- Info Banner -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-[var(--color-primary)] mt-0.5"></i>
                <p class="text-sm text-gray-700">
                    Meer <a href="#" class="text-[var(--color-primary)] underline hover:no-underline">informatie over de Berichtenbox</a>, uw persoonlijke brievenbus van de overheid.
                </p>
            </div>
        </div>

        <!-- Uw instellingen aanpassen -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Uw instellingen aanpassen</h2>
            <p class="text-sm text-gray-600 mb-3">
                Wilt u het e-mailadres waarop u meldingen ontvangt controleren? Of wilt u aanpassen welke organisaties u digitaal berichten mogen sturen? Dit staat onder instellingen.
            </p>
            <a href="#" class="inline-flex items-center gap-2 text-[var(--color-primary)] font-semibold text-sm hover:underline">
                Ga naar instellingen
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        </div>

        <!-- Veelgestelde vragen -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Veelgestelde vragen</h2>
            
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="divide-y divide-gray-200">
                    <details class="group">
                        <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                            <span class="text-sm text-gray-700">Ik heb een vraag over de inhoud van een bericht.</span>
                            <i class="fas fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-400"></i>
                        </summary>
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                            <p class="text-sm text-gray-600">Neem contact op met de organisatie die het bericht heeft gestuurd. U vindt de contactgegevens meestal onderaan het bericht.</p>
                        </div>
                    </details>
                    
                    <details class="group">
                        <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                            <span class="text-sm text-gray-700">Ik heb een e-mail ontvangen van een nieuw bericht, maar er staat geen nieuw bericht in mijn Berichtenbox.</span>
                            <i class="fas fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-400"></i>
                        </summary>
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                            <p class="text-sm text-gray-600">Controleer of het bericht misschien in uw archief of prullenbak staat. Het kan ook zijn dat het bericht even nodig heeft om te verschijnen.</p>
                        </div>
                    </details>
                    
                    <details class="group">
                        <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                            <span class="text-sm text-gray-700">Welke berichten ontvang ik via de Berichtenbox en welke op papier?</span>
                            <i class="fas fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-400"></i>
                        </summary>
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                            <p class="text-sm text-gray-600">Dit hangt af van de organisatie en uw instellingen. Sommige organisaties sturen alle berichten digitaal, andere alleen bepaalde types.</p>
                        </div>
                    </details>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-200">
                    <a href="#" class="inline-flex items-center gap-2 text-[var(--color-primary)] font-semibold text-sm hover:underline">
                        Bekijk alle veelgestelde vragen
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
