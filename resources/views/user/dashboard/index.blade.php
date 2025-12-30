@extends('user.layouts.dashboard')

@section('dashboard-content')
<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-900">Welkom J. de Vries</h1>
</div>

<!-- Recente berichten in uw Berichtenbox -->
<div class="mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recente berichten in uw Berichtenbox</h2>
    
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="text-center py-8">
            <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-inbox text-gray-400 text-xl"></i>
            </div>
            <p class="text-sm text-gray-500 mb-4">U heeft geen nieuwe berichten.</p>
            <a href="{{ route('user.berichtenbox') }}" class="inline-flex items-center gap-2 text-[var(--color-primary)] font-semibold text-sm hover:underline">
                Ga naar Berichtenbox
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        </div>
    </div>
</div>

<!-- Download de MijnGegevens app -->
<div class="mb-8">
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <!-- Image -->
            <div class="md:w-1/2 bg-gray-100">
                <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop" alt="Mobile App" class="w-full h-48 md:h-full object-cover">
            </div>
            
            <!-- Content -->
            <div class="md:w-1/2 p-6 flex flex-col justify-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Download de MijnGegevens app</h3>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <i class="fas fa-circle text-[var(--color-primary)] text-[6px] mt-2"></i>
                        <span>Download de MijnGegevens app</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <i class="fas fa-circle text-[var(--color-primary)] text-[6px] mt-2"></i>
                        <span>Zet belangrijke data direct in uw agenda</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <i class="fas fa-circle text-[var(--color-primary)] text-[6px] mt-2"></i>
                        <span>Snel en eenvoudig toegang met de DigiD app</span>
                    </li>
                </ul>
                
                <div class="flex gap-3">
                    <a href="#" class="inline-block">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-10">
                    </a>
                    <a href="#" class="inline-block">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-10">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Veelgestelde vragen -->
<div class="mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Veelgestelde vragen</h2>
    
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="divide-y divide-gray-200">
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                    <span class="text-sm text-gray-700">Van welke organisaties kan ik berichten digitaal ontvangen?</span>
                    <i class="fas fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-400"></i>
                </summary>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <p class="text-sm text-gray-600">U kunt berichten ontvangen van verschillende overheidsorganisaties zoals de Belastingdienst, UWV, SVB, en gemeenten die zijn aangesloten bij MijnOverheid.</p>
                </div>
            </details>
            
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                    <span class="text-sm text-gray-700">Wie heeft toegang tot mijn gegevens in MijnOverheid?</span>
                    <i class="fas fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-400"></i>
                </summary>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <p class="text-sm text-gray-600">Alleen u heeft toegang tot uw persoonlijke MijnOverheid-account. Overheidsorganisaties kunnen hun eigen gegevens over u tonen, maar kunnen niet bij uw berichten of andere persoonlijke informatie.</p>
                </div>
            </details>
            
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                    <span class="text-sm text-gray-700">Ik wil mijn account opzeggen. Kan dat?</span>
                    <i class="fas fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-400"></i>
                </summary>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <p class="text-sm text-gray-600">Ja, u kunt uw MijnOverheid-account opzeggen. Ga naar Instellingen en kies voor 'Account opzeggen'. Let op: u ontvangt dan geen digitale post meer van de overheid.</p>
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
@endsection
