@extends('layouts.app')

@section('title', 'Chat met Open Overheid - Vraag het Open.Overheid.nl')

@section('content')
    <!-- Main Chat Interface -->
    <div class="bg-[var(--color-surface)] min-h-screen">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-8" x-data="chatInterface()">
            <!-- Chat Messages Area -->
            <div class="mb-6 space-y-4 max-w-7xl mx-auto" x-ref="messagesContainer">
                <!-- Welcome Message -->
                <template x-if="messages.length === 0">
                    <div class="py-12">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[var(--color-primary-light)]">
                                <svg class="w-12 h-12" viewBox="0 0 838 837" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M515.718 110.605C621.291 149.011 714.28 313.637 646.577 499.743C578.874 685.848 438.407 805.582 332.834 767.176C227.261 728.77 172.327 427.631 240.03 241.525C264.94 173.052 302.98 129.691 346.376 104.145C420.926 60.2597 448.989 86.3295 515.718 110.605Z" fill="url(#chat-linearGradient1)" fill-opacity="0.6" />
                                    <path d="M156.136 453.009C156.169 480.494 140.036 537.977 222.056 604.127C338.957 655.053 526.064 778.991 591.853 627.973C657.641 476.956 715.723 241.089 598.822 190.163C481.921 139.237 221.924 301.992 156.136 453.009Z" fill="url(#chat-linearGradient2)" fill-opacity="0.6" />
                                    <path opacity="0.8" d="M766.324 448.634C743.549 558.643 594.059 674.407 400.133 634.258C206.208 594.109 67.4634 472.382 90.2388 362.373C113.014 252.364 403.08 154.565 597.005 194.714C668.356 209.486 716.75 240.874 748.289 280.13C802.469 347.57 780.72 379.1 766.324 448.634Z" fill="url(#chat-linearGradient3)" fill-opacity="0.5" />
                                    <ellipse cx="419" cy="409" rx="419" ry="409" fill="url(#chat-radialGradient1)" />
                                    <ellipse class="animate-pulse" cx="419" cy="409" rx="419" ry="409" fill="url(#chat-radialGradient2)" />
                                    <ellipse cx="419" cy="409" rx="419" ry="409" fill="url(#chat-radialGradient3)" />

                                    <defs>
                                        <linearGradient id="chat-linearGradient1" x1="420.705" y1="249.747" x2="423.671" y2="663.385" gradientUnits="userSpaceOnUse" >
                                            <stop stop-color="#00D1FF" />
                                            <stop offset="1" stop-color="#C626FF" stop-opacity="0" />
                                        </linearGradient>

                                        <linearGradient id="chat-linearGradient2" x1="487.879" y1="-248.502" y2="140.966" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#00A3FF" stop-opacity="0.14" />
                                            <stop offset="1" stop-color="#FF00B8" />
                                        </linearGradient>

                                        <linearGradient id="chat-linearGradient3" x1="161.766" y1="376.102" x2="594.449" y2="567.328" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#00FFE0"/>
                                            <stop offset="1" stop-color="#C626FF" stop-opacity="0" />
                                        </linearGradient>

                                        <radialGradient id="chat-radialGradient1" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(419 409) rotate(90) scale(358.5 367.131)">
                                            <stop stop-color="white" />
                                            <stop offset="0.193741" stop-color="#E4E4E4" />
                                            <stop offset="1" stop-color="#737373" stop-opacity="0" />
                                        </radialGradient>

                                        <radialGradient id="chat-radialGradient2" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(419 409) rotate(90) scale(293.5 300.566)">
                                            <stop stop-color="white" />
                                            <stop offset="0.314072" stop-color="white" />
                                            <stop offset="1" stop-color="#737373" stop-opacity="0" />
                                        </radialGradient>

                                        <radialGradient id="chat-radialGradient3" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(419 409) rotate(90) scale(534 546.857)">
                                            <stop stop-color="#545454" stop-opacity="0" />
                                            <stop offset="1" stop-color="#5D64FF" stop-opacity="0.35" />
                                        </radialGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-semibold text-[var(--color-on-surface)]">
                                    Stel je vraag
                                </h2>
                                <p class="text-base text-[var(--color-on-surface-variant)] mt-1">
                                    Vraag in gewone taal naar overheidsdocumenten. Bijvoorbeeld: "Wat is het parkeerbeleid in Leiden?"
                                </p>
                            </div>
                        </div>
                        
                        <!-- Suggested Questions -->
                        <div class="space-y-3">
                            <button 
                                @click="askQuestion('Wanneer kan ik mij inschrijven?')"
                                class="w-full text-left px-6 py-4 rounded-md bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/10 transition-all duration-200 text-base text-[var(--color-on-surface)] focus:outline-none"
                            >
                                Wanneer kan ik mij inschrijven?
                            </button>
                            <button 
                                @click="askQuestion('Waar kan ik parkeren?')"
                                class="w-full text-left px-6 py-4 rounded-md bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/10 transition-all duration-200 text-base text-[var(--color-on-surface)] focus:outline-none"
                            >
                                Waar kan ik parkeren?
                            </button>
                            <button 
                                @click="askQuestion('Kan ik vrijwilliger worden?')"
                                class="w-full text-left px-6 py-4 rounded-md bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/10 transition-all duration-200 text-base text-[var(--color-on-surface)] focus:outline-none"
                            >
                                Kan ik vrijwilliger worden?
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Chat Messages -->
                <template x-for="(message, index) in messages" :key="index">
                    <div class="flex flex-col gap-4">
                        <!-- User's Chat -->
                        <div x-show="message.type === 'user'" class="w-full max-w-7xl border border-[var(--color-outline-variant)] bg-[var(--color-surface)] p-6 rounded-md text-left">
                            <div class="flex items-center gap-2 text-[var(--color-on-surface)] mb-4">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] text-xs font-semibold">
                                    <i class="fas fa-user text-xs" aria-hidden="true"></i>
                                </div>
                                <span class="text-sm font-semibold">U</span>
                            </div>
                            <p class="text-pretty sm:pl-10 mt-0 text-sm text-[var(--color-on-surface)]" x-text="message.text"></p>
                        </div>

                        <!-- AI's Response -->
                        <div x-show="message.type === 'ai'" class="w-full max-w-7xl border border-[var(--color-outline-variant)] bg-[var(--color-surface)] p-6 rounded-md text-left">
                            <div class="flex items-center gap-2 text-[var(--color-on-surface)] mb-4">
                                <span class="flex size-8 items-center justify-center rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)]">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" class="size-5">
                                        <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5M3 8.062C3 6.76 4.235 5.765 5.53 5.886a26.6 26.6 0 0 0 4.94 0C11.765 5.765 13 6.76 13 8.062v1.157a.93.93 0 0 1-.765.935c-.845.147-2.34.346-4.235.346s-3.39-.2-4.235-.346A.93.93 0 0 1 3 9.219zm4.542-.827a.25.25 0 0 0-.217.068l-.92.9a25 25 0 0 1-1.871-.183.25.25 0 0 0-.068.495c.55.076 1.232.149 2.02.193a.25.25 0 0 0 .189-.071l.754-.736.847 1.71a.25.25 0 0 0 .404.062l.932-.97a25 25 0 0 0 1.922-.188.25.25 0 0 0-.068-.495c-.538.074-1.207.145-1.98.189a.25.25 0 0 0-.166.076l-.754.785-.842-1.7a.25.25 0 0 0-.182-.135" />
                                        <path d="M8.5 1.866a1 1 0 1 0-1 0V3h-2A4.5 4.5 0 0 0 1 7.5V8a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1v1a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1v-.5A4.5 4.5 0 0 0 10.5 3h-2zM14 7.5V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.5A3.5 3.5 0 0 1 5.5 4h5A3.5 3.5 0 0 1 14 7.5" />
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold">Open.Overheid.nl</span>
                            </div>
                            
                            <!-- Loading State -->
                            <div x-show="message.loading" class="sm:pl-10">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                    </div>
                                    <p class="text-sm text-[var(--color-on-surface-variant)]">
                                        Open.Overheid.nl is een antwoord aan het genereren...
                                    </p>
                                </div>
                            </div>
                            
                            <!-- AI Answer Content -->
                            <div x-show="!message.loading" class="sm:pl-10">
                                <!-- AI Answer Text -->
                                <template x-if="message.answer">
                                    <div class="mb-6">
                                        <div class="mb-4">
                                            <p class="text-sm font-semibold text-[var(--color-on-surface-variant)] mb-2">Antwoord door Open.Overheid.nl</p>
                                            <div 
                                                x-data="{ copiedToClipboard: false, copyToClipboard() { const text = $refs.answerText.textContent; navigator.clipboard.writeText(text).then(() => { this.copiedToClipboard = true; setTimeout(() => { this.copiedToClipboard = false; }, 2000); }).catch(() => { this.copiedToClipboard = false; }); } }"
                                                class="flex flex-col gap-4 border border-[var(--color-outline-variant)] rounded-md bg-[var(--color-surface)] p-6 text-[var(--color-on-surface)]"
                                            >
                                                <pre x-ref="answerText" class="w-full whitespace-normal text-sm leading-relaxed" x-html="formatAnswerWithSources(message.answer)"></pre>
                                                <button 
                                                    class="rounded-full w-fit p-1 text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] focus:outline-none focus-visible:text-[var(--color-on-surface)] focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-[var(--color-primary)] active:bg-[var(--color-surface-variant)] transition-colors" 
                                                    title="Kopieer" 
                                                    aria-label="Kopieer het antwoord naar klembord" 
                                                    x-on:click="copyToClipboard()"
                                                >
                                                    <span class="sr-only" x-text="copiedToClipboard ? 'Gekopieerd' : 'Kopieer het antwoord naar klembord'"></span>
                                                    <svg x-show="!copiedToClipboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M13.887 3.182c.396.037.79.08 1.183.128C16.194 3.45 17 4.414 17 5.517V16.75A2.25 2.25 0 0 1 14.75 19h-9.5A2.25 2.25 0 0 1 3 16.75V5.517c0-1.103.806-2.068 1.93-2.207.393-.048.787-.09 1.183-.128A3.001 3.001 0 0 1 9 1h2c1.373 0 2.531.923 2.887 2.182ZM7.5 4A1.5 1.5 0 0 1 9 2.5h2A1.5 1.5 0 0 1 12.5 4v.5h-5V4Z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <svg x-show="copiedToClipboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 fill-green-600" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z" clip-rule="evenodd"/>
                                                        <path fill-rule="evenodd" d="M2 7a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7Zm6.585 1.08a.75.75 0 0 1 .336 1.005l-1.75 3.5a.75.75 0 0 1-1.16.234l-1.75-1.5a.75.75 0 0 1 .977-1.139l1.02.875 1.321-2.64a.75.75 0 0 1 1.006-.336Z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Disclaimer -->
                                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md" x-data="{ dismissed: false }" x-show="!dismissed">
                                            <div class="flex items-start gap-3">
                                                <i class="fas fa-exclamation-triangle text-yellow-600 shrink-0 mt-0.5" aria-hidden="true"></i>
                                                <div class="flex-1">
                                                    <p class="text-sm text-yellow-800">
                                                        Open.Overheid.nl kan fouten maken. Controleer altijd de bronnen. Het antwoord op je vraag wordt gegenereerd door AI met als doel om jou te ondersteunen bij het vinden van informatie.
                                                    </p>
                                                </div>
                                                <button @click="dismissed = true" class="text-yellow-600 hover:text-yellow-800 focus:outline-none">
                                                    <i class="fas fa-times" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Sources Section -->
                                        <template x-if="message.sources && message.sources.length > 0">
                                            <div class="mb-6">
                                                <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-3">Bronnen</h3>
                                                <div class="space-y-2">
                                                    <template x-for="(source, sourceIndex) in message.sources" :key="sourceIndex">
                                                        <a 
                                                            :href="source.url"
                                                            class="block px-4 py-3 rounded-md border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/5 transition-all duration-200 focus:outline-none"
                                                        >
                                                            <div class="flex items-start gap-3">
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] text-xs font-semibold shrink-0" x-text="source.number"></span>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-medium text-[var(--color-on-surface)]" x-text="source.title"></p>
                                                                    <template x-if="source.organisation">
                                                                        <p class="text-xs text-[var(--color-on-surface-variant)] mt-1" x-text="source.organisation"></p>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                <!-- Fallback message if no answer but results exist -->
                                <template x-if="!message.answer && message.results && message.results.length > 0">
                                    <p class="text-pretty text-sm text-[var(--color-on-surface)] mb-4" x-text="message.text"></p>
                                </template>
                                
                                <!-- No results message -->
                                <template x-if="!message.answer && (!message.results || message.results.length === 0)">
                                    <p class="text-pretty text-sm text-[var(--color-on-surface)] mb-4" x-text="message.text"></p>
                                </template>
                                
                                <!-- Search Results -->
                                <template x-if="message.results && message.results.length > 0">
                                    <div class="mt-6">
                                        <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-4">
                                            Zoekresultaten (<span x-text="message.results.length"></span>)
                                        </h3>
                                        <div class="space-y-4">
                                            <template x-for="(result, resultIndex) in message.results" :key="resultIndex">
                                                <a 
                                                    :href="'/open-overheid/documents/' + result.id"
                                                    class="block p-4 rounded-md border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/5 transition-all duration-200 focus:outline-none"
                                                >
                                                    <div class="flex items-start justify-between gap-4 mb-2">
                                                        <h4 class="text-sm font-semibold text-[var(--color-on-surface)] flex-1" x-text="result.title"></h4>
                                                        <template x-if="result.formatted_category">
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[var(--color-primary-light)] text-[var(--color-primary)] shrink-0 border border-[var(--color-primary)]/20" x-text="result.formatted_category"></span>
                                                        </template>
                                                    </div>
                                                    <p class="text-xs text-[var(--color-on-surface-variant)] mb-3 line-clamp-2" x-text="result.description"></p>
                                                    <div class="flex items-center gap-4 text-xs text-[var(--color-on-surface-variant)]">
                                                        <template x-if="result.organisation">
                                                            <span class="inline-flex items-center gap-1.5">
                                                                <i class="fas fa-building text-[10px]" aria-hidden="true"></i>
                                                                <span x-text="result.organisation"></span>
                                                            </span>
                                                        </template>
                                                        <template x-if="result.publication_date">
                                                            <span class="inline-flex items-center gap-1.5">
                                                                <i class="fas fa-calendar text-[10px]" aria-hidden="true"></i>
                                                                <span x-text="formatDate(result.publication_date)"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </a>
                                            </template>
                                        </div>
                                        <template x-if="message.totalFound > message.results.length">
                                            <div class="mt-4">
                                                <a 
                                                    :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(message.query)"
                                                    class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors focus:outline-none"
                                                >
                                                    Toon alle zoekresultaten
                                                    <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                <!-- Actions -->
                                <div class="mt-4 flex items-center gap-2">
                                    <button 
                                        class="rounded-full p-1 text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] focus:outline-none focus-visible:text-[var(--color-on-surface)] focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-[var(--color-primary)] active:bg-[var(--color-surface-variant)] transition-colors" 
                                        title="Voorlezen" 
                                        aria-label="Voorlezen"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                                            <path d="M10.5 3.75a.75.75 0 0 0-1.264-.546L5.203 7H2.667a.75.75 0 0 0-.7.48A6.985 6.985 0 0 0 1.5 10c0 .887.165 1.737.468 2.52.111.29.39.48.7.48h2.535l4.033 3.796a.75.75 0 0 0 1.264-.546V3.75ZM16.45 5.05a.75.75 0 0 0-1.06 1.061 5.5 5.5 0 0 1 0 7.778.75.75 0 0 0 1.06 1.06 7 7 0 0 0 0-9.899Z"/>
                                            <path d="M14.329 7.172a.75.75 0 0 0-1.061 1.06 2.5 2.5 0 0 1 0 3.536.75.75 0 0 0 1.06 1.06 4 4 0 0 0 0-5.656Z"/>
                                        </svg>
                                    </button>
                                    <button 
                                        x-data="{ copiedToClipboard: false, copyToClipboard() { const text = $refs.aiResponseText.textContent; navigator.clipboard.writeText(text).then(() => { this.copiedToClipboard = true; setTimeout(() => { this.copiedToClipboard = false; }, 2000); }).catch(() => { this.copiedToClipboard = false; }); } }"
                                        class="rounded-full p-1 text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] focus:outline-none focus-visible:text-[var(--color-on-surface)] focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-[var(--color-primary)] active:bg-[var(--color-surface-variant)] transition-colors" 
                                        title="Kopieer" 
                                        aria-label="Kopieer"
                                        x-on:click="copyToClipboard()"
                                    >
                                        <span class="sr-only" x-text="copiedToClipboard ? 'Gekopieerd' : 'Kopieer het antwoord naar klembord'"></span>
                                        <svg x-show="!copiedToClipboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M13.887 3.182c.396.037.79.08 1.183.128C16.194 3.45 17 4.414 17 5.517V16.75A2.25 2.25 0 0 1 14.75 19h-9.5A2.25 2.25 0 0 1 3 16.75V5.517c0-1.103.806-2.068 1.93-2.207.393-.048.787-.09 1.183-.128A3.001 3.001 0 0 1 9 1h2c1.373 0 2.531.923 2.887 2.182ZM7.5 4A1.5 1.5 0 0 1 9 2.5h2A1.5 1.5 0 0 1 12.5 4v.5h-5V4Z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg x-show="copiedToClipboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 fill-green-600" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z" clip-rule="evenodd"/>
                                            <path fill-rule="evenodd" d="M2 7a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7Zm6.585 1.08a.75.75 0 0 1 .336 1.005l-1.75 3.5a.75.75 0 0 1-1.16.234l-1.75-1.5a.75.75 0 0 1 .977-1.139l1.02.875 1.321-2.64a.75.75 0 0 1 1.006-.336Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    <button 
                                        class="rounded-full p-1 text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] focus:outline-none focus-visible:text-[var(--color-on-surface)] focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-[var(--color-primary)] active:bg-[var(--color-surface-variant)] transition-colors" 
                                        title="Vind ik leuk" 
                                        aria-label="Vind ik leuk"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                                            <path d="M1 8.25a1.25 1.25 0 1 1 2.5 0v7.5a1.25 1.25 0 1 1-2.5 0v-7.5ZM11 3V1.7c0-.268.14-.526.395-.607A2 2 0 0 1 14 3c0 .995-.182 1.948-.514 2.826-.204.54.166 1.174.744 1.174h2.52c1.243 0 2.261 1.01 2.146 2.247a23.864 23.864 0 0 1-1.341 5.974C17.153 16.323 16.072 17 14.9 17h-3.192a3 3 0 0 1-1.341-.317l-2.734-1.366A3 3 0 0 0 6.292 15H5V8h.963c.685 0 1.258-.483 1.612-1.068a4.011 4.011 0 0 1 2.166-1.73c.432-.143.853-.386 1.011-.814.16-.432.248-.9.248-1.388Z"/>
                                        </svg>
                                    </button>
                                    <button 
                                        class="rounded-full p-1 text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] focus:outline-none focus-visible:text-[var(--color-on-surface)] focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-[var(--color-primary)] active:bg-[var(--color-surface-variant)] transition-colors" 
                                        title="Meer instellingen" 
                                        aria-label="Meer instellingen"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4" aria-hidden="true">
                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div x-ref="aiResponseText" class="hidden" x-text="message.answer || message.text"></div>
                                <p class="text-xs text-[var(--color-on-surface-variant)] mt-2" x-text="formatTime(message.timestamp)"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input Area -->
            <div class="sticky bottom-0 bg-[var(--color-surface)] py-4 -mx-6 lg:-mx-8 px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <div x-data class="flex w-full flex-col overflow-hidden border border-[var(--color-outline-variant)] bg-[var(--color-surface)] text-[var(--color-on-surface)] rounded-md">
                        <div class="p-2">
                            <p id="promptLabel" class="pb-1 pl-2 text-xs font-semibold text-[var(--color-on-surface-variant)] opacity-60">Prompt</p>
                            <p 
                                class="scroll-on max-h-44 w-full overflow-y-auto px-2 py-1 focus:outline-none focus:ring-0 text-sm text-[var(--color-on-surface)]" 
                                role="textbox" 
                                aria-labelledby="promptLabel" 
                                contenteditable="true"
                                x-ref="promptTextInput"
                                @paste.prevent="document.execCommand('insertText', false, $event.clipboardData.getData('text/plain'))"
                                @keydown.enter.prevent="sendMessage()"
                                @input="inputText = $refs.promptTextInput.innerText"
                                :data-placeholder="'Stel een vraag...'"
                            ></p>
                            <textarea name="promptText" x-ref="promptText" x-model="inputText" hidden></textarea>
                        </div>
                        <div class="flex w-full items-center justify-end gap-4 px-2.5 py-2">
                            <button 
                                type="button"
                                @click="sendMessage()"
                                :disabled="loading || !inputText.trim()"
                                class="flex items-center gap-2 whitespace-nowrap bg-[var(--color-primary)] px-4 py-2 text-center text-sm font-medium tracking-wide text-[var(--color-on-primary)] transition hover:opacity-75 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-primary)] active:opacity-100 disabled:cursor-not-allowed disabled:opacity-50 rounded-md"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-3" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 4a.75.75 0 0 1 .738.616l.252 1.388A1.25 1.25 0 0 0 6.996 7.01l1.388.252a.75.75 0 0 1 0 1.476l-1.388.252A1.25 1.25 0 0 0 5.99 9.996l-.252 1.388a.75.75 0 0 1-1.476 0L4.01 9.996A1.25 1.25 0 0 0 3.004 8.99l-1.388-.252a.75.75 0 0 1 0-1.476l1.388-.252A1.25 1.25 0 0 0 4.01 6.004l.252-1.388A.75.75 0 0 1 5 4ZM12 1a.75.75 0 0 1 .721.544l.195.682c.118.415.443.74.858.858l.682.195a.75.75 0 0 1 0 1.442l-.682.195a1.25 1.25 0 0 0-.858.858l-.195.682a.75.75 0 0 1-1.442 0l-.195-.682a1.25 1.25 0 0 0-.858-.858l-.682-.195a.75.75 0 0 1 0-1.442l.682-.195a1.25 1.25 0 0 0 .858-.858l.195-.682A.75.75 0 0 1 12 1ZM10 11a.75.75 0 0 1 .728.568.968.968 0 0 0 .704.704.75.75 0 0 1 0 1.456.968.968 0 0 0-.704.704.75.75 0 0 1-1.456 0 .968.968 0 0 0-.704-.704.75.75 0 0 1 0-1.456.968.968 0 0 0 .704-.704A.75.75 0 0 1 10 11Z" clip-rule="evenodd"/>
                                </svg>
                                Genereren
                            </button>
                        </div>
                    </div>
                    <p class="text-center mt-4 text-xs text-[var(--color-on-surface-variant)]">
                        Open.Overheid.nl is het nieuwe zoeken
                    </p>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
function chatInterface() {
    return {
        messages: [],
        inputText: '',
        loading: false,
        
        init() {
            // Scroll to bottom on new messages
            this.$watch('messages', () => {
                this.$nextTick(() => {
                    if (this.$refs.messagesContainer) {
                        this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                    }
                });
            });
        },
        
        askQuestion(question) {
            this.inputText = question;
            if (this.$refs.promptTextInput) {
                this.$refs.promptTextInput.innerText = question;
            }
            this.sendMessage();
        },
        
        async sendMessage() {
            if (!this.inputText.trim() || this.loading) {
                return;
            }
            
            const userMessage = this.inputText.trim();
            this.inputText = '';
            if (this.$refs.promptTextInput) {
                this.$refs.promptTextInput.innerText = '';
            }
            
            // Add user message
            this.messages.push({
                type: 'user',
                text: userMessage,
                timestamp: new Date(),
            });
            
            // Add loading AI message
            const aiMessageIndex = this.messages.length;
            this.messages.push({
                type: 'ai',
                text: '',
                loading: true,
                results: [],
                totalFound: 0,
                query: userMessage,
                timestamp: new Date(),
            });
            
            this.loading = true;
            
            try {
                const response = await fetch('{{ route("api.natural-language-search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        query: userMessage,
                        limit: 6,
                    }),
                });
                
                const data = await response.json();
                
                // Update AI message with results and answer
                this.messages[aiMessageIndex] = {
                    type: 'ai',
                    text: data.found > 0 
                        ? `Ik heb ${data.found} document${data.found !== 1 ? 'en' : ''} gevonden die relevant zijn voor je vraag.`
                        : 'Ik heb geen documenten gevonden die direct relevant zijn voor je vraag. Probeer je vraag anders te formuleren.',
                    loading: false,
                    answer: data.answer || null,
                    sources: data.sources || [],
                    results: data.hits || [],
                    totalFound: data.found || 0,
                    query: userMessage,
                    timestamp: new Date(),
                };
            } catch (error) {
                console.error('Search error:', error);
                this.messages[aiMessageIndex] = {
                    type: 'ai',
                    text: 'Er is een fout opgetreden bij het zoeken. Probeer het later opnieuw.',
                    loading: false,
                    answer: null,
                    sources: [],
                    results: [],
                    totalFound: 0,
                    query: userMessage,
                    timestamp: new Date(),
                };
            } finally {
                this.loading = false;
            }
        },
        
        formatTime(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleTimeString('nl-NL', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('nl-NL', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        },
        
        formatAnswerWithSources(answer) {
            if (!answer) return '';
            // Highlight source references (Document 1, Document 2, etc. or just numbers after "document", "volgens", "in")
            // Match patterns like "Document 1", "document 2", "Volgens document 3", "In document 1", etc.
            return answer
                .replace(/(document|Document|volgens|Volgens|in|In)\s+(\d+)/gi, (match, word, num) => {
                    return `${word} <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] text-xs font-semibold mx-1 align-middle">${num}</span>`;
                })
                // Also match standalone numbers that are likely source references (1, 2, 3) when they appear in context
                .replace(/\b([1-5])\b(?=\s+(staan|vermeldt|volgens|document|bron|bronnen))/gi, (match, num) => {
                    return `<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] text-xs font-semibold mx-1 align-middle">${num}</span>`;
                });
        },
    };
}
</script>
<style>
[contenteditable][data-placeholder]:empty:before {
    content: attr(data-placeholder);
    color: var(--color-on-surface-variant);
    pointer-events: none;
}
</style>
@endpush
@endsection
