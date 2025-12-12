<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Open Overheid - Vind overheidsdocumenten</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&family=Roboto+Mono&display=swap" rel="stylesheet">
    
    {{-- Font Awesome 6.5.2 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
</head>
<body class="bg-white">
  <!-- Header -->
  <header class="absolute inset-x-0 top-0 z-50">
    <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">
      <div class="flex lg:flex-1">
        <a href="/" class="-m-1.5 p-1.5">
          <span class="sr-only">Open Overheid</span>
          <span class="text-xl font-semibold text-[#01689B]">Open Overheid</span>
        </a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" onclick="openMobileMenu()" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
          <span class="sr-only">Open hoofdmenu</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <div class="hidden lg:flex lg:gap-x-12">
        <a href="/zoek" class="text-sm/6 font-semibold text-gray-900">Zoeken</a>
        <a href="#" class="text-sm/6 font-semibold text-gray-900">Over</a>
        <a href="#" class="text-sm/6 font-semibold text-gray-900">Documenten</a>
        <a href="#" class="text-sm/6 font-semibold text-gray-900">Help</a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="/zoek" class="text-sm/6 font-semibold text-[#01689B]">Zoek documenten <span aria-hidden="true">&rarr;</span></a>
      </div>
    </nav>
    <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden hidden">
      <div tabindex="0" class="fixed inset-0 focus:outline-none">
        <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
          <div class="flex items-center justify-between">
            <a href="/" class="-m-1.5 p-1.5">
              <span class="sr-only">Open Overheid</span>
              <span class="text-xl font-semibold text-[#01689B]">Open Overheid</span>
            </a>
            <button type="button" onclick="closeMobileMenu()" class="-m-2.5 rounded-md p-2.5 text-gray-700">
              <span class="sr-only">Sluit menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
            <div class="mt-6 flow-root">
              <div class="-my-6 divide-y divide-gray-500/10">
                <div class="space-y-2 py-6">
                  <a href="/zoek" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Zoeken</a>
                  <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Over</a>
                  <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Documenten</a>
                  <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Help</a>
                </div>
                <div class="py-6">
                  <a href="/zoek" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-[#01689B] hover:bg-gray-50">Zoek documenten</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </dialog>
  </header>

  <main>
    <!-- Hero section with Search -->
    <div class="relative isolate pt-14">
      <svg aria-hidden="true" class="absolute inset-0 -z-10 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-gray-200">
        <defs>
          <pattern id="83fd4e5a-9d52-42fc-97b6-718e5d7ee527" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
            <path d="M100 200V.5M.5 .5H200" fill="none" />
          </pattern>
        </defs>
        <svg x="50%" y="-1" class="overflow-visible fill-gray-50">
          <path d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M-300.5 600h201v201h-201Z" stroke-width="0" />
        </svg>
        <rect width="100%" height="100%" fill="url(#83fd4e5a-9d52-42fc-97b6-718e5d7ee527)" stroke-width="0" />
      </svg>
      <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:flex lg:items-center lg:gap-x-10 lg:px-8 lg:py-40">
        <div class="mx-auto max-w-2xl lg:mx-0 lg:flex-auto">
          <div class="flex">
            <div class="relative flex items-center gap-x-4 rounded-full bg-white px-4 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
              <span class="font-semibold text-[#01689B]">Nieuw</span>
              <span aria-hidden="true" class="h-4 w-px bg-gray-900/10"></span>
              <a href="#" class="flex items-center gap-x-1">
                <span aria-hidden="true" class="absolute inset-0"></span>
                Bekijk wat er nieuw is
                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="-mr-2 size-5 text-gray-400">
                  <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                </svg>
              </a>
            </div>
          </div>
          <h1 class="mt-10 text-5xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-7xl">Vind overheidsdocumenten</h1>
          <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">Doorzoek {{ number_format($documentCount, 0, ',', '.') }} actief openbaar gemaakte documenten van Nederlandse overheden. Alle documenten zijn vrij toegankelijk volgens de Wet open overheid (Woo).</p>
          
          <!-- Search Form -->
          <form action="/zoeken" method="GET" class="mt-10">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1">
                <label for="zoekwoorden" class="sr-only">Zoekwoorden</label>
                <input 
                  type="text" 
                  id="zoekwoorden" 
                  name="zoeken" 
                  class="w-full rounded-md bg-white px-4 py-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#01689B] sm:text-sm/6 border border-gray-300"
                  placeholder="bijv. klimaatbeleid, onderwijs, rapport 2023"
                  value="{{ request('zoeken') }}"
                >
              </div>
              <button type="submit" class="flex-none rounded-md bg-[#01689B] px-6 py-3 text-sm font-semibold text-white shadow-xs hover:bg-[#014d74] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#01689B]">
                Zoeken
              </button>
            </div>
            <p class="mt-3 text-sm text-gray-500">Zoek in titels, beschrijvingen en documentinhoud</p>
          </form>
        </div>
        <div class="mt-16 sm:mt-24 lg:mt-0 lg:shrink-0 lg:grow">
          <svg role="img" viewBox="0 0 366 729" class="mx-auto w-91.5 max-w-full drop-shadow-xl">
            <title>App screenshot</title>
            <defs>
              <clipPath id="2ade4387-9c63-4fc4-b754-10e687a0d332">
                <rect width="316" height="684" rx="36" />
              </clipPath>
            </defs>
            <path d="M363.315 64.213C363.315 22.99 341.312 1 300.092 1H66.751C25.53 1 3.528 22.99 3.528 64.213v44.68l-.857.143A2 2 0 0 0 1 111.009v24.611a2 2 0 0 0 1.671 1.973l.95.158a2.26 2.26 0 0 1-.093.236v26.173c.212.1.398.296.541.643l-1.398.233A2 2 0 0 0 1 167.009v47.611a2 2 0 0 0 1.671 1.973l1.368.228c-.139.319-.314.533-.511.653v16.637c.221.104.414.313.56.689l-1.417.236A2 2 0 0 0 1 237.009v47.611a2 2 0 0 0 1.671 1.973l1.347.225c-.135.294-.302.493-.49.607v377.681c0 41.213 22 63.208 63.223 63.208h95.074c.947-.504 2.717-.843 4.745-.843l.141.001h.194l.086-.001 33.704.005c1.849.043 3.442.37 4.323.838h95.074c41.222 0 63.223-21.999 63.223-63.212v-394.63c-.259-.275-.48-.796-.63-1.47l-.011-.133 1.655-.276A2 2 0 0 0 366 266.62v-77.611a2 2 0 0 0-1.671-1.973l-1.712-.285c.148-.839.396-1.491.698-1.811V64.213Z" fill="#4B5563" />
            <path d="M16 59c0-23.748 19.252-43 43-43h246c23.748 0 43 19.252 43 43v615c0 23.196-18.804 42-42 42H58c-23.196 0-42-18.804-42-42V59Z" fill="#343E4E" />
            <foreignObject width="316" height="684" clip-path="url(#2ade4387-9c63-4fc4-b754-10e687a0d332)" transform="translate(24 24)">
              <div class="bg-[#01689B] h-full w-full flex items-center justify-center text-white text-2xl font-semibold">
                Open Overheid
              </div>
            </foreignObject>
          </svg>
        </div>
      </div>
    </div>

    <!-- Logo cloud -->
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-12 opacity-40 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 sm:gap-y-14 lg:mx-0 lg:max-w-none lg:grid-cols-5">
        <div class="col-span-2 max-h-12 w-full flex items-center justify-center lg:col-span-1">
          <span class="text-gray-600 font-semibold">Rijksoverheid</span>
        </div>
        <div class="col-span-2 max-h-12 w-full flex items-center justify-center lg:col-span-1">
          <span class="text-gray-600 font-semibold">Gemeenten</span>
        </div>
        <div class="col-span-2 max-h-12 w-full flex items-center justify-center lg:col-span-1">
          <span class="text-gray-600 font-semibold">Provincies</span>
        </div>
        <div class="col-span-2 max-h-12 w-full flex items-center justify-center sm:col-start-2 lg:col-span-1">
          <span class="text-gray-600 font-semibold">Waterschappen</span>
        </div>
        <div class="col-span-2 col-start-2 max-h-12 w-full flex items-center justify-center sm:col-start-auto lg:col-span-1">
          <span class="text-gray-600 font-semibold">Open Data</span>
        </div>
      </div>
    </div>

    <!-- Feature section -->
    <div class="mx-auto mt-32 max-w-7xl sm:mt-56 sm:px-6 lg:px-8">
      <div class="relative isolate overflow-hidden bg-gray-900 px-6 py-20 sm:rounded-3xl sm:px-10 sm:py-24 lg:py-24 xl:px-24">
        <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:items-center lg:gap-y-0">
          <div class="lg:row-start-2 lg:max-w-md">
            <h2 class="text-3xl font-semibold tracking-tight text-balance text-white sm:text-4xl">Verhoog uw productiviteit. Begin vandaag met zoeken.</h2>
            <p class="mt-6 text-lg/8 text-gray-300">Vind snel de documenten die u nodig heeft. Onze geavanceerde zoekfunctie helpt u om precies te vinden wat u zoekt in duizenden overheidsdocumenten.</p>
          </div>
          <img width="2432" height="1442" src="https://tailwindcss.com/plus-assets/img/component-images/dark-project-app-screenshot.png" alt="Product screenshot" class="relative -z-20 max-w-xl min-w-full rounded-xl shadow-xl ring-1 ring-white/10 lg:row-span-4 lg:w-5xl lg:max-w-none" />
          <div class="max-w-xl lg:row-start-3 lg:mt-10 lg:max-w-md lg:border-t lg:border-white/10 lg:pt-10">
            <dl class="max-w-xl space-y-8 text-base/7 text-gray-300 lg:max-w-none">
              <div class="relative">
                <dt class="ml-9 inline-block font-semibold text-white">
                  <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="absolute top-1 left-1 size-5 text-[#01689B]">
                    <path d="M5.5 17a4.5 4.5 0 0 1-1.44-8.765 4.5 4.5 0 0 1 8.302-3.046 3.5 3.5 0 0 1 4.504 4.272A4 4 0 0 1 15 17H5.5Zm3.75-2.75a.75.75 0 0 0 1.5 0V9.66l1.95 2.1a.75.75 0 1 0 1.1-1.02l-3.25-3.5a.75.75 0 0 0-1.1 0l-3.25 3.5a.75.75 0 1 0 1.1 1.02l1.95-2.1v4.59Z" clip-rule="evenodd" fill-rule="evenodd" />
                  </svg>
                  Snel zoeken.
                </dt>
                <dd class="inline">Zoek door duizenden documenten in seconden met onze geavanceerde zoektechnologie.</dd>
              </div>
              <div class="relative">
                <dt class="ml-9 inline-block font-semibold text-white">
                  <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="absolute top-1 left-1 size-5 text-[#01689B]">
                    <path d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" fill-rule="evenodd" />
                  </svg>
                  Veilig en betrouwbaar.
                </dt>
                <dd class="inline">Alle documenten zijn officieel vrijgegeven door Nederlandse overheden volgens de Wet open overheid.</dd>
              </div>
              <div class="relative">
                <dt class="ml-9 inline-block font-semibold text-white">
                  <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="absolute top-1 left-1 size-5 text-[#01689B]">
                    <path d="M4.632 3.533A2 2 0 0 1 6.577 2h6.846a2 2 0 0 1 1.945 1.533l1.976 8.234A3.489 3.489 0 0 0 16 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234Z" />
                    <path d="M4 13a2 2 0 1 0 0 4h12a2 2 0 1 0 0-4H4Zm11.24 2a.75.75 0 0 1 .75-.75H16a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75h-.01a.75.75 0 0 1-.75-.75V15Zm-2.25-.75a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75h-.01Z" clip-rule="evenodd" fill-rule="evenodd" />
                  </svg>
                  Volledig open source.
                </dt>
                <dd class="inline">Deze website is open source en vrij beschikbaar voor iedereen.</dd>
              </div>
            </dl>
          </div>
        </div>
        <div aria-hidden="true" class="pointer-events-none absolute top-1/2 left-12 -z-10 -translate-y-1/2 transform-gpu blur-3xl lg:top-auto lg:-bottom-48 lg:translate-y-0">
          <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="aspect-1155/678 w-288.75 bg-linear-to-tr from-[#01689B] to-[#4FC3F7] opacity-25"></div>
        </div>
      </div>
    </div>

    <!-- Feature section -->
    <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-56 lg:px-8">
      <div class="mx-auto max-w-2xl lg:text-center">
        <h2 class="text-base/7 font-semibold text-[#01689B]">Zoek sneller</h2>
        <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl lg:text-balance">Alles wat u nodig heeft om documenten te vinden</p>
        <p class="mt-6 text-lg/8 text-gray-600">Onze zoekfunctie helpt u om snel en efficiënt de juiste overheidsdocumenten te vinden. Filter op datum, organisatie, thema en meer.</p>
      </div>
      <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
          <div class="flex flex-col">
            <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-gray-900">
              <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 flex-none text-[#01689B]">
                <path d="M5.5 17a4.5 4.5 0 0 1-1.44-8.765 4.5 4.5 0 0 1 8.302-3.046 3.5 3.5 0 0 1 4.504 4.272A4 4 0 0 1 15 17H5.5Zm3.75-2.75a.75.75 0 0 0 1.5 0V9.66l1.95 2.1a.75.75 0 1 0 1.1-1.02l-3.25-3.5a.75.75 0 0 0-1.1 0l-3.25 3.5a.75.75 0 1 0 1.1 1.02l1.95-2.1v4.59Z" clip-rule="evenodd" fill-rule="evenodd" />
              </svg>
              Geavanceerd zoeken
            </dt>
            <dd class="mt-4 flex flex-auto flex-col text-base/7 text-gray-600">
              <p class="flex-auto">Zoek door titels, beschrijvingen en volledige documentinhoud. Gebruik filters om uw zoekresultaten te verfijnen op datum, organisatie of thema.</p>
              <p class="mt-6">
                <a href="/zoek" class="text-sm/6 font-semibold text-[#01689B] hover:text-[#014d74]">Meer informatie <span aria-hidden="true">→</span></a>
              </p>
            </dd>
          </div>
          <div class="flex flex-col">
            <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-gray-900">
              <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 flex-none text-[#01689B]">
                <path d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" fill-rule="evenodd" />
              </svg>
              Betrouwbare bronnen
            </dt>
            <dd class="mt-4 flex flex-auto flex-col text-base/7 text-gray-600">
              <p class="flex-auto">Alle documenten komen rechtstreeks van Nederlandse overheden en zijn vrijgegeven volgens de Wet open overheid (Woo).</p>
              <p class="mt-6">
                <a href="#" class="text-sm/6 font-semibold text-[#01689B] hover:text-[#014d74]">Meer informatie <span aria-hidden="true">→</span></a>
              </p>
            </dd>
          </div>
          <div class="flex flex-col">
            <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-gray-900">
              <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 flex-none text-[#01689B]">
                <path d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" fill-rule="evenodd" />
              </svg>
              Real-time updates
            </dt>
            <dd class="mt-4 flex flex-auto flex-col text-base/7 text-gray-600">
              <p class="flex-auto">Nieuwe documenten worden automatisch toegevoegd zodra ze door overheden worden vrijgegeven.</p>
              <p class="mt-6">
                <a href="#" class="text-sm/6 font-semibold text-[#01689B] hover:text-[#014d74]">Meer informatie <span aria-hidden="true">→</span></a>
              </p>
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Newsletter section -->
    <div class="mx-auto mt-32 max-w-7xl sm:mt-56 sm:px-6 lg:px-8">
      <div class="relative isolate overflow-hidden bg-gray-900 px-6 py-24 shadow-2xl sm:rounded-3xl sm:px-24 xl:py-32">
        <h2 class="mx-auto max-w-3xl text-center text-4xl font-semibold tracking-tight text-white sm:text-5xl">Blijf op de hoogte van nieuwe documenten</h2>
        <p class="mx-auto mt-6 max-w-lg text-center text-lg text-gray-300">Schrijf u in voor onze nieuwsbrief en ontvang updates over nieuwe documenten en functies.</p>
        <form class="mx-auto mt-10 flex max-w-md gap-x-4">
          <label for="email-address" class="sr-only">E-mailadres</label>
          <input id="email-address" type="email" name="email" required placeholder="Voer uw e-mail in" autocomplete="email" class="min-w-0 flex-auto rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#01689B] sm:text-sm/6" />
          <button type="submit" class="flex-none rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-xs hover:bg-gray-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Abonneren</button>
        </form>
        <svg viewBox="0 0 1024 1024" aria-hidden="true" class="absolute top-1/2 left-1/2 -z-10 size-256 -translate-x-1/2">
          <circle r="512" cx="512" cy="512" fill="url(#759c1415-0410-454c-8f7c-9a820de03641)" fill-opacity="0.7" />
          <defs>
            <radialGradient id="759c1415-0410-454c-8f7c-9a820de03641" r="1" cx="0" cy="0" gradientUnits="userSpaceOnUse" gradientTransform="translate(512 512) rotate(90) scale(512)">
              <stop stop-color="#01689B" />
              <stop offset="1" stop-color="#4FC3F7" stop-opacity="0" />
            </radialGradient>
          </defs>
        </svg>
      </div>
    </div>

    <!-- Testimonials section -->
    <div class="relative isolate mt-32 sm:mt-56 sm:pt-32">
      <svg aria-hidden="true" class="absolute inset-0 -z-10 hidden size-full mask-[radial-gradient(64rem_64rem_at_top,white,transparent)] stroke-gray-200 sm:block">
        <defs>
          <pattern id="55d3d46d-692e-45f2-becd-d8bdc9344f45" width="200" height="200" x="50%" y="0" patternUnits="userSpaceOnUse">
            <path d="M.5 200V.5H200" fill="none" />
          </pattern>
        </defs>
        <svg x="50%" y="0" class="overflow-visible fill-gray-50">
          <path d="M-200.5 0h201v201h-201Z M599.5 0h201v201h-201Z M399.5 400h201v201h-201Z M-400.5 600h201v201h-201Z" stroke-width="0" />
        </svg>
        <rect width="100%" height="100%" fill="url(#55d3d46d-692e-45f2-becd-d8bdc9344f45)" stroke-width="0" />
      </svg>
      <div class="relative">
        <div aria-hidden="true" class="absolute inset-x-0 top-1/2 -z-10 -translate-y-1/2 transform-gpu overflow-hidden opacity-30 blur-3xl">
          <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="ml-[max(50%,38rem)] aspect-1313/771 w-328.25 bg-linear-to-tr from-[#01689B] to-[#4FC3F7]"></div>
        </div>
        <div aria-hidden="true" class="absolute inset-x-0 top-0 -z-10 flex transform-gpu overflow-hidden pt-32 opacity-25 blur-3xl sm:pt-40 xl:justify-end">
          <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="-ml-88 aspect-1313/771 w-328.25 flex-none origin-top-right rotate-30 bg-linear-to-tr from-[#01689B] to-[#4FC3F7] xl:mr-[calc(50%-12rem)] xl:ml-0"></div>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
          <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-base/7 font-semibold text-[#01689B]">Wat gebruikers zeggen</h2>
            <p class="mt-2 text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-5xl">We hebben duizenden gebruikers geholpen</p>
          </div>
          <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 grid-rows-1 gap-8 text-sm/6 text-gray-900 sm:mt-20 sm:grid-cols-2 xl:mx-0 xl:max-w-none xl:grid-flow-col xl:grid-cols-4">
            <figure class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 sm:col-span-2 xl:col-start-2 xl:row-end-1">
              <blockquote class="p-6 text-lg font-semibold tracking-tight text-gray-900 sm:p-12 sm:text-xl/8">
                <p>"Een geweldige manier om overheidsdocumenten te vinden. De zoekfunctie is snel en de filters helpen me precies te vinden wat ik zoek."</p>
              </blockquote>
              <figcaption class="flex flex-wrap items-center gap-x-4 gap-y-4 border-t border-gray-900/10 px-6 py-4 sm:flex-nowrap">
                <div class="size-10 flex-none rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">JD</div>
                <div class="flex-auto">
                  <div class="font-semibold text-gray-900">Jan de Vries</div>
                  <div class="text-gray-600">Onderzoeker</div>
                </div>
              </figcaption>
            </figure>
            <div class="space-y-8 xl:contents xl:space-y-0">
              <div class="space-y-8 xl:row-span-2">
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"Eindelijk een overzichtelijke manier om openbare documenten te doorzoeken. Zeer gebruiksvriendelijk!"</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">MV</div>
                    <div>
                      <div class="font-semibold text-gray-900">Maria van der Berg</div>
                      <div class="text-gray-600">Journalist</div>
                    </div>
                  </figcaption>
                </figure>
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"Als ambtenaar vind ik dit platform zeer waardevol voor het delen van informatie met burgers."</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">PK</div>
                    <div>
                      <div class="font-semibold text-gray-900">Peter Klaassen</div>
                      <div class="text-gray-600">Ambtenaar</div>
                    </div>
                  </figcaption>
                </figure>
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"Transparantie in de overheid is belangrijk. Dit platform maakt het toegankelijk voor iedereen."</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">LS</div>
                    <div>
                      <div class="font-semibold text-gray-900">Lisa Smit</div>
                      <div class="text-gray-600">Burgerschapsactivist</div>
                    </div>
                  </figcaption>
                </figure>
              </div>
              <div class="space-y-8 xl:row-start-1">
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"De filters zijn perfect. Ik kan snel vinden wat ik nodig heb zonder door duizenden resultaten te scrollen."</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">TW</div>
                    <div>
                      <div class="font-semibold text-gray-900">Tom Wouters</div>
                      <div class="text-gray-600">Student</div>
                    </div>
                  </figcaption>
                </figure>
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"Open source en transparant. Precies wat we nodig hebben voor een open overheid."</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">AH</div>
                    <div>
                      <div class="font-semibold text-gray-900">Anna Hendriks</div>
                      <div class="text-gray-600">Developer</div>
                    </div>
                  </figcaption>
                </figure>
              </div>
            </div>
            <div class="space-y-8 xl:contents xl:space-y-0">
              <div class="space-y-8 xl:row-start-1">
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"Als advocaat gebruik ik dit platform regelmatig om relevante overheidsdocumenten te vinden voor mijn zaken."</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">RB</div>
                    <div>
                      <div class="font-semibold text-gray-900">Robert Bakker</div>
                      <div class="text-gray-600">Advocaat</div>
                    </div>
                  </figcaption>
                </figure>
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"De interface is intuïtief en de zoekresultaten zijn relevant. Een geweldige tool!"</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">SM</div>
                    <div>
                      <div class="font-semibold text-gray-900">Sophie Mulder</div>
                      <div class="text-gray-600">Beleidsmedewerker</div>
                    </div>
                  </figcaption>
                </figure>
              </div>
              <div class="space-y-8 xl:row-span-2">
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"Dit platform maakt transparantie in de overheid toegankelijk voor iedereen. Geweldig initiatief!"</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">EJ</div>
                    <div>
                      <div class="font-semibold text-gray-900">Erik Jansen</div>
                      <div class="text-gray-600">Onderzoeker</div>
                    </div>
                  </figcaption>
                </figure>
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"De snelheid waarmee ik documenten kan vinden is indrukwekkend. Zeer professioneel platform."</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">CV</div>
                    <div>
                      <div class="font-semibold text-gray-900">Caroline Visser</div>
                      <div class="text-gray-600">Consultant</div>
                    </div>
                  </figcaption>
                </figure>
                <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5">
                  <blockquote class="text-gray-900">
                    <p>"Als burger waardeer ik de toegankelijkheid van deze informatie. Dit is hoe transparantie hoort te werken."</p>
                  </blockquote>
                  <figcaption class="mt-6 flex items-center gap-x-4">
                    <div class="size-10 rounded-full bg-[#01689B] flex items-center justify-center text-white font-semibold">MG</div>
                    <div>
                      <div class="font-semibold text-gray-900">Mark Groen</div>
                      <div class="text-gray-600">Burger</div>
                    </div>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="mt-32 sm:mt-56">
    <div class="mx-auto max-w-7xl border-t border-gray-200 px-6 pt-16 pb-8 sm:pt-24 lg:px-8 lg:pt-32">
      <div class="xl:grid xl:grid-cols-3 xl:gap-8">
        <div>
          <span class="text-xl font-semibold text-[#01689B]">Open Overheid</span>
          <p class="mt-4 text-sm text-gray-600">Open source variant van open.overheid.nl</p>
        </div>
        <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
          <div class="md:grid md:grid-cols-2 md:gap-8">
            <div>
              <h3 class="text-sm/6 font-semibold text-gray-900">Oplossingen</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li>
                  <a href="/zoek" class="text-sm/6 text-gray-600 hover:text-gray-900">Zoeken</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Documenten</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Filters</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">API</a>
                </li>
              </ul>
            </div>
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm/6 font-semibold text-gray-900">Ondersteuning</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Documentatie</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Gidsen</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Help</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="md:grid md:grid-cols-2 md:gap-8">
            <div>
              <h3 class="text-sm/6 font-semibold text-gray-900">Over</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Over deze website</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Open source</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Contact</a>
                </li>
              </ul>
            </div>
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm/6 font-semibold text-gray-900">Juridisch</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Servicevoorwaarden</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Privacybeleid</a>
                </li>
                <li>
                  <a href="#" class="text-sm/6 text-gray-600 hover:text-gray-900">Licentie</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-16 border-t border-gray-900/10 pt-8 sm:mt-20 lg:mt-24 lg:flex lg:items-center lg:justify-between">
        <div>
          <h3 class="text-sm/6 font-semibold text-gray-900">Abonneer op onze nieuwsbrief</h3>
          <p class="mt-2 text-sm/6 text-gray-600">Het laatste nieuws, artikelen en updates, wekelijks in uw inbox.</p>
        </div>
        <form class="mt-6 sm:flex sm:max-w-md lg:mt-0">
          <label for="email-address-footer" class="sr-only">E-mailadres</label>
          <input id="email-address-footer" type="email" name="email-address" required placeholder="Voer uw e-mail in" autocomplete="email" class="w-full min-w-0 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus-visible:outline-[#01689B] sm:w-56 sm:text-sm/6" />
          <div class="mt-4 sm:mt-0 sm:ml-4 sm:shrink-0">
            <button type="submit" class="flex w-full items-center justify-center rounded-md bg-[#01689B] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-[#014d74] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#01689B]">Abonneren</button>
          </div>
        </form>
      </div>
      <div class="mt-8 border-t border-gray-900/10 pt-8 md:flex md:items-center md:justify-between">
        <p class="mt-8 text-sm/6 text-gray-600 md:order-1 md:mt-0">&copy; {{ date('Y') }} Open Overheid. Open source software.</p>
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

    // Close menu when clicking outside
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
</div>

