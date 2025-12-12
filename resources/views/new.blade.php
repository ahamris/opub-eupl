<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Open Woo voorziening - Centraal platform voor open overheidsdata</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&family=Roboto+Mono&display=swap" rel="stylesheet">
    
    {{-- Font Awesome 6.5.2 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
</head>
<body class="bg-white antialiased">
  <!-- Header -->
  <header class="fixed inset-x-0 top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/50">
    <nav aria-label="Global" class="flex items-center justify-between p-4 lg:px-8 max-w-7xl mx-auto">
      <div class="flex lg:flex-1">
        <a href="/" class="-m-1.5 p-1.5 flex items-center gap-2">
          <span class="sr-only">Open Woo voorziening</span>
          <span class="text-xl font-bold text-[#01689B] tracking-tight">Open Woo voorziening</span>
        </a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" onclick="openMobileMenu()" class="-m-2.5 inline-flex items-center justify-center rounded-lg p-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
          <span class="sr-only">Open hoofdmenu</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6">
            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <div class="hidden lg:flex lg:gap-x-8">
        <a href="/zoek" class="text-sm font-medium text-gray-700 hover:text-[#01689B] transition-colors">Zoeken</a>
        <a href="/over" class="text-sm font-medium text-gray-700 hover:text-[#01689B] transition-colors">Over</a>
        <a href="#" class="text-sm font-medium text-gray-700 hover:text-[#01689B] transition-colors">Kennisbank</a>
        <a href="#" class="text-sm font-medium text-gray-700 hover:text-[#01689B] transition-colors">Bouw mee!</a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="/zoek" class="text-sm font-semibold text-[#01689B] hover:text-[#014d74] transition-colors inline-flex items-center gap-1">
          Zoek documenten <span aria-hidden="true" class="text-xs">→</span>
        </a>
      </div>
    </nav>
    <dialog id="mobile-menu" class="backdrop:bg-gray-900/50 lg:hidden hidden">
      <div tabindex="0" class="fixed inset-0 focus:outline-none">
        <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm shadow-xl">
          <div class="flex items-center justify-between mb-6">
            <a href="/" class="text-xl font-bold text-[#01689B]">Open Woo voorziening</a>
            <button type="button" onclick="closeMobileMenu()" class="rounded-lg p-2 text-gray-700 hover:bg-gray-100">
              <span class="sr-only">Sluit menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
          <div class="space-y-1">
            <a href="/zoek" class="block rounded-lg px-4 py-3 text-base font-medium text-gray-900 hover:bg-gray-50">Zoeken</a>
            <a href="/over" class="block rounded-lg px-4 py-3 text-base font-medium text-gray-900 hover:bg-gray-50">Over</a>
            <a href="#" class="block rounded-lg px-4 py-3 text-base font-medium text-gray-900 hover:bg-gray-50">Kennisbank</a>
            <a href="#" class="block rounded-lg px-4 py-3 text-base font-medium text-gray-900 hover:bg-gray-50">Bouw mee!</a>
            <a href="/zoek" class="block rounded-lg px-4 py-3 text-base font-semibold text-[#01689B] hover:bg-gray-50 mt-4">Zoek documenten</a>
          </div>
        </div>
      </div>
    </dialog>
  </header>

  <!-- Hero Section -->
  <div class="relative isolate pt-24 pb-20 sm:pt-32 sm:pb-28 lg:pt-40 lg:pb-32">
    <!-- Background decoration -->
    <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
      <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#01689B]/20 to-[#4FC3F7]/20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
    </div>
    
    <div class="mx-auto max-w-4xl px-6 lg:px-8">
      <div class="text-center">
        <h1 class="text-5xl font-bold tracking-tight text-gray-900 sm:text-6xl lg:text-7xl">
          Open Woo voorziening
        </h1>
        <p class="mt-6 text-lg leading-8 text-gray-600 sm:text-xl max-w-2xl mx-auto">
          Het enige opensource open overheids dataplatform dat gebouwd is door burgers en journalisten om op 1 centrale plek alle open overheids data zoek en vindbaar te maken en betekenis uit te halen door middel van onze AI assistent (Cotrex).
        </p>
        
        <!-- Search Form -->
        <form action="/zoeken" method="GET" class="mt-10 max-w-2xl mx-auto">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
              <label for="hero-zoeken" class="sr-only">Zoekwoorden</label>
              <input 
                type="text" 
                id="hero-zoeken" 
                name="zoeken" 
                class="w-full rounded-xl bg-white px-5 py-4 text-base text-gray-900 placeholder:text-gray-400 border-2 border-gray-200 focus:border-[#01689B] focus:outline-none focus:ring-4 focus:ring-[#01689B]/10 transition-all shadow-sm"
                placeholder="Zoek in overheidsdocumenten..."
                value="{{ request('zoeken') }}"
              >
            </div>
            <button type="submit" class="flex-none rounded-xl bg-[#01689B] px-8 py-4 text-base font-semibold text-white shadow-lg hover:bg-[#014d74] focus:outline-none focus:ring-4 focus:ring-[#01689B]/30 transition-all">
              Zoeken
            </button>
          </div>
          <p class="mt-4 text-sm text-gray-500">
            Doorzoek <span class="font-semibold text-gray-700">{{ number_format($documentCount, 0, ',', '.') }}</span> documenten van Nederlandse overheden
          </p>
        </form>
      </div>
    </div>
    
    <div aria-hidden="true" class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
      <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%+3rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#01689B]/20 to-[#4FC3F7]/20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"></div>
    </div>
  </div>

  <!-- Datasets Section -->
  <div class="bg-gray-50 py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl lg:max-w-none">
        <div class="mb-12">
          <p class="text-sm font-semibold text-[#01689B] uppercase tracking-wide">Datasets</p>
          <h2 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
            Toegang tot open overheidsdata
          </h2>
        </div>
        
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-6 lg:grid-rows-2">
          <!-- Large Card - Open Overheid -->
          <div class="flex p-px lg:col-span-4">
            <div class="w-full overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 lg:rounded-tl-2xl">
              <div class="h-64 bg-gradient-to-br from-[#01689B] via-[#0288D1] to-[#4FC3F7] flex items-center justify-center p-8">
                <div class="text-center text-white">
                  <h3 class="text-3xl font-bold mb-3">open.overheid.nl</h3>
                  <p class="text-white/90 text-lg leading-relaxed max-w-md mx-auto">
                    Toegang tot alle actief openbaar gemaakte documenten volgens de Wet open overheid (Woo)
                  </p>
                </div>
              </div>
              <div class="p-8">
                <p class="text-sm font-semibold text-[#01689B] uppercase tracking-wide mb-2">Open Overheid</p>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Documenten van Nederlandse overheden</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                  Zoek en vind documenten van Rijksoverheid, gemeenten, provincies en waterschappen. Alle documenten zijn vrij toegankelijk en doorzoekbaar.
                </p>
                <a href="/zoek" class="inline-flex items-center text-base font-semibold text-[#01689B] hover:text-[#014d74] transition-colors group">
                  Bekijk dataset
                  <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          
          <!-- Small Card - Open Raadsinformatie -->
          <div class="flex p-px lg:col-span-2">
            <div class="w-full overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 lg:rounded-tr-2xl">
              <div class="h-64 bg-gradient-to-br from-[#4FC3F7] via-[#0288D1] to-[#01689B] flex items-center justify-center p-8">
                <div class="text-center text-white">
                  <h3 class="text-2xl font-bold mb-3">zoek.openraadsinformatie.nl</h3>
                  <p class="text-white/90 leading-relaxed">
                    Open data van gemeenteraden en besturen
                  </p>
                </div>
              </div>
              <div class="p-8">
                <p class="text-sm font-semibold text-[#01689B] uppercase tracking-wide mb-2">Open Raadsinformatie</p>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Gemeentelijke besluitvorming</h3>
                <p class="text-gray-600 leading-relaxed mb-6 text-sm">
                  Toegang tot raadsstukken, moties, amendementen en andere gemeentelijke documenten.
                </p>
                <a href="https://zoek.openraadsinformatie.nl" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-base font-semibold text-[#01689B] hover:text-[#014d74] transition-colors group">
                  Bekijk dataset
                  <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Section -->
  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <dl class="grid grid-cols-1 gap-12 text-center lg:grid-cols-3">
        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
          <dt class="text-base font-medium text-gray-600">Documenten</dt>
          <dd class="order-first text-5xl font-bold tracking-tight text-gray-900 sm:text-6xl">
            {{ number_format($documentCount, 0, ',', '.') }}
          </dd>
        </div>
        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
          <dt class="text-base font-medium text-gray-600">Dossiers</dt>
          <dd class="order-first text-5xl font-bold tracking-tight text-gray-900 sm:text-6xl">
            {{ number_format($dossierCount, 0, ',', '.') }}
          </dd>
        </div>
        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
          <dt class="text-base font-medium text-gray-600">Thema's</dt>
          <dd class="order-first text-5xl font-bold tracking-tight text-gray-900 sm:text-6xl">
            {{ number_format($themeCount, 0, ',', '.') }}
          </dd>
        </div>
      </dl>
    </div>
  </div>

  <!-- Knowledgebase Section -->
  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center mb-16">
        <h2 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Kennisbank</h2>
        <p class="mt-4 text-lg leading-8 text-gray-600">
          Leer meer over open data, transparantie en hoe je het platform gebruikt.
        </p>
      </div>
      
      <div class="mx-auto grid max-w-2xl grid-cols-1 gap-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <!-- Blog Card -->
        <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 transition-all hover:shadow-xl hover:ring-gray-900/10">
          <div class="relative h-48 bg-gradient-to-br from-[#01689B] to-[#4FC3F7] flex items-center justify-center">
            <i class="fas fa-book text-white text-5xl" aria-hidden="true"></i>
            <div class="absolute inset-0 rounded-t-2xl ring-1 ring-inset ring-gray-900/10"></div>
          </div>
          <div class="flex flex-1 flex-col p-6">
            <div class="flex items-center gap-x-4 text-xs mb-4">
              <time datetime="2024-01-15" class="text-gray-500">15 jan 2024</time>
              <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1 font-medium text-gray-600">Blog</span>
            </div>
            <h3 class="text-xl font-semibold leading-6 text-gray-900 mb-3 group">
              <a href="#" class="hover:text-[#01689B] transition-colors">
                <span class="absolute inset-0"></span>
                Hoe werkt de Wet open overheid?
              </a>
            </h3>
            <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-600">
              Leer meer over de Woo en hoe overheden documenten actief openbaar moeten maken. Ontdek wat je rechten zijn als burger.
            </p>
            <div class="mt-6 flex items-center gap-x-4">
              <div class="h-10 w-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold text-sm">OW</div>
              <div class="text-sm leading-6">
                <p class="font-semibold text-gray-900">Open Woo Team</p>
                <p class="text-gray-600">Redactie</p>
              </div>
            </div>
          </div>
        </article>

        <!-- Vlog Card -->
        <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 transition-all hover:shadow-xl hover:ring-gray-900/10">
          <div class="relative h-48 bg-gradient-to-br from-[#4FC3F7] to-[#01689B] flex items-center justify-center">
            <i class="fas fa-video text-white text-5xl" aria-hidden="true"></i>
            <div class="absolute inset-0 rounded-t-2xl ring-1 ring-inset ring-gray-900/10"></div>
          </div>
          <div class="flex flex-1 flex-col p-6">
            <div class="flex items-center gap-x-4 text-xs mb-4">
              <time datetime="2024-01-10" class="text-gray-500">10 jan 2024</time>
              <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1 font-medium text-gray-600">Vlog</span>
            </div>
            <h3 class="text-xl font-semibold leading-6 text-gray-900 mb-3 group">
              <a href="#" class="hover:text-[#01689B] transition-colors">
                <span class="absolute inset-0"></span>
                Tutorial: Zoeken met filters
              </a>
            </h3>
            <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-600">
              Bekijk hoe je efficiënt zoekt in duizenden documenten met behulp van onze geavanceerde filteropties.
            </p>
            <div class="mt-6 flex items-center gap-x-4">
              <div class="h-10 w-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold text-sm">CT</div>
              <div class="text-sm leading-6">
                <p class="font-semibold text-gray-900">Cotrex AI</p>
                <p class="text-gray-600">AI Assistent</p>
              </div>
            </div>
          </div>
        </article>

        <!-- Event Card -->
        <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 transition-all hover:shadow-xl hover:ring-gray-900/10">
          <div class="relative h-48 bg-gradient-to-br from-[#01689B] to-[#4FC3F7] flex items-center justify-center">
            <i class="fas fa-calendar-alt text-white text-5xl" aria-hidden="true"></i>
            <div class="absolute inset-0 rounded-t-2xl ring-1 ring-inset ring-gray-900/10"></div>
          </div>
          <div class="flex flex-1 flex-col p-6">
            <div class="flex items-center gap-x-4 text-xs mb-4">
              <time datetime="2024-01-05" class="text-gray-500">5 jan 2024</time>
              <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1 font-medium text-gray-600">Event</span>
            </div>
            <h3 class="text-xl font-semibold leading-6 text-gray-900 mb-3 group">
              <a href="#" class="hover:text-[#01689B] transition-colors">
                <span class="absolute inset-0"></span>
                Open Data Dag 2024
              </a>
            </h3>
            <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-600">
              Kom naar de Open Data Dag en leer hoe je open overheidsdata kunt gebruiken voor onderzoek en journalistiek.
            </p>
            <div class="mt-6 flex items-center gap-x-4">
              <div class="h-10 w-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold text-sm">OD</div>
              <div class="text-sm leading-6">
                <p class="font-semibold text-gray-900">Open Data Community</p>
                <p class="text-gray-600">Organisator</p>
              </div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>

  <!-- Feedback CTA Section -->
  <div class="bg-gray-50 py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <p class="text-sm font-semibold text-[#01689B] uppercase tracking-wide">Help mee verbeteren</p>
        <h2 class="mt-4 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
          Laat je mening achter om het nog beter te maken
        </h2>
        <p class="mt-6 text-lg leading-8 text-gray-600 max-w-xl mx-auto">
          Open Woo voorziening is een community-driven project. Jouw feedback en input helpen ons om het platform te verbeteren en uit te breiden.
        </p>
        <div class="mt-10">
          <a href="#" class="inline-flex items-center rounded-xl bg-[#01689B] px-8 py-4 text-base font-semibold text-white shadow-lg hover:bg-[#014d74] focus:outline-none focus:ring-4 focus:ring-[#01689B]/30 transition-all">
            Geef feedback
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-white border-t border-gray-200">
    <div class="mx-auto max-w-7xl px-6 py-16 sm:py-20 lg:px-8 lg:py-24">
      <div class="xl:grid xl:grid-cols-3 xl:gap-8">
        <div class="space-y-4">
          <span class="text-xl font-bold text-[#01689B]">Open Woo voorziening</span>
          <p class="text-sm leading-6 text-gray-600 max-w-xs">
            Het enige opensource open overheids dataplatform gebouwd door burgers en journalisten.
          </p>
        </div>
        <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
          <div class="md:grid md:grid-cols-2 md:gap-8">
            <div>
              <h3 class="text-sm font-semibold leading-6 text-gray-900">Oplossingen</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li><a href="/zoek" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Zoeken</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Documenten</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Filters</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">API</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Cotrex AI</a></li>
              </ul>
            </div>
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm font-semibold leading-6 text-gray-900">Ondersteuning</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Documentatie</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Gidsen</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Help</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Kennisbank</a></li>
              </ul>
            </div>
          </div>
          <div class="md:grid md:grid-cols-2 md:gap-8">
            <div>
              <h3 class="text-sm font-semibold leading-6 text-gray-900">Over</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li><a href="/over" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Over deze website</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Open source</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Bouw mee!</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Contact</a></li>
              </ul>
            </div>
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm font-semibold leading-6 text-gray-900">Juridisch</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Servicevoorwaarden</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Privacybeleid</a></li>
                <li><a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900 transition-colors">Licentie</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-16 border-t border-gray-900/10 pt-8 sm:mt-20 sm:flex sm:items-center sm:justify-between">
        <p class="text-xs leading-5 text-gray-500">&copy; {{ date('Y') }} Open Woo voorziening. Open source software.</p>
        <div class="mt-8 flex items-center gap-6 sm:mt-0 sm:justify-start">
          <a href="#" class="text-gray-400 hover:text-gray-500 transition-colors">
            <span class="sr-only">GitHub</span>
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    function openMobileMenu() {
      const menu = document.getElementById('mobile-menu');
      if (menu) {
        menu.classList.remove('hidden');
        menu.showModal();
      }
    }

    function closeMobileMenu() {
      const menu = document.getElementById('mobile-menu');
      if (menu) {
        menu.classList.add('hidden');
        menu.close();
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      const menu = document.getElementById('mobile-menu');
      if (menu) {
        menu.addEventListener('click', function(e) {
          if (e.target === menu) {
            closeMobileMenu();
          }
        });
      }
    });
  </script>
</body>
</html>
