@extends('layouts.app')

@section('title', 'Chat met Open Overheid - Vraag het Open.Overheid.nl')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[var(--color-primary-light)] via-white to-[var(--color-primary-light)]/30">
    <!-- Header -->
    <div class="bg-[var(--color-surface)] border-b border-[var(--color-outline-variant)]">
        <div class="mx-auto max-w-4xl px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)]">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path d="M10 2a.75.75 0 0 1 .75.75v6.5h6.5a.75.75 0 0 1 0 1.5h-6.5v6.5a.75.75 0 0 1-1.5 0v-6.5H3.25a.75.75 0 0 1 0-1.5h6.5v-6.5A.75.75 0 0 1 10 2Z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-[var(--font-size-headline-medium)] font-bold text-[var(--color-on-surface)]">
                            Vraag het Open.Overheid.nl
                        </h1>
                        <p class="text-[var(--font-size-body-small)] text-[var(--color-on-surface-variant)]">
                            Krijg direct antwoord op je vraag met behulp van A.I.
                        </p>
                    </div>
                </div>
                <a href="{{ route('home') }}" class="text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)] transition-colors">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-6">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Chat Interface -->
    <div class="mx-auto max-w-4xl px-6 py-8" x-data="chatInterface()">
        <!-- Chat Messages Area -->
        <div class="mb-6 space-y-6" x-ref="messagesContainer">
            <!-- Welcome Message -->
            <template x-if="messages.length === 0">
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[var(--color-primary-light)] mb-4">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-8 text-[var(--color-primary)]">
                            <path d="M10 2a.75.75 0 0 1 .75.75v6.5h6.5a.75.75 0 0 1 0 1.5h-6.5v6.5a.75.75 0 0 1-1.5 0v-6.5H3.25a.75.75 0 0 1 0-1.5h6.5v-6.5A.75.75 0 0 1 10 2Z" />
                        </svg>
                    </div>
                    <h2 class="text-[var(--font-size-headline-medium)] font-bold text-[var(--color-on-surface)] mb-2">
                        Stel je vraag
                    </h2>
                    <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface-variant)] mb-8">
                        Vraag in gewone taal naar overheidsdocumenten. Bijvoorbeeld: "Wat is het parkeerbeleid in Leiden?"
                    </p>
                    
                    <!-- Suggested Questions -->
                    <div class="space-y-3 max-w-2xl mx-auto">
                        <button 
                            @click="askQuestion('Wanneer kan ik mij inschrijven?')"
                            class="w-full text-left px-6 py-4 rounded-xl bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/10 transition-all duration-200 text-[var(--font-size-body-medium)] text-[var(--color-on-surface)]"
                        >
                            Wanneer kan ik mij inschrijven?
                        </button>
                        <button 
                            @click="askQuestion('Waar kan ik parkeren?')"
                            class="w-full text-left px-6 py-4 rounded-xl bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/10 transition-all duration-200 text-[var(--font-size-body-medium)] text-[var(--color-on-surface)]"
                        >
                            Waar kan ik parkeren?
                        </button>
                        <button 
                            @click="askQuestion('Kan ik vrijwilliger worden?')"
                            class="w-full text-left px-6 py-4 rounded-xl bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/10 transition-all duration-200 text-[var(--font-size-body-medium)] text-[var(--color-on-surface)]"
                        >
                            Kan ik vrijwilliger worden?
                        </button>
                    </div>
                </div>
            </template>

            <!-- Chat Messages -->
            <template x-for="(message, index) in messages" :key="index">
                <div>
                    <!-- User Message -->
                    <div x-show="message.type === 'user'" class="flex justify-end mb-4">
                        <div class="max-w-3xl">
                            <div class="bg-[var(--color-primary)] text-[var(--color-on-primary)] rounded-2xl rounded-tr-sm px-6 py-4 shadow-sm">
                                <p class="text-[var(--font-size-body-medium)]" x-text="message.text"></p>
                            </div>
                            <p class="text-[var(--font-size-label-small)] text-[var(--color-on-surface-variant)] mt-2 text-right" x-text="formatTime(message.timestamp)"></p>
                        </div>
                    </div>

                    <!-- AI Response -->
                    <div x-show="message.type === 'ai'" class="flex justify-start mb-4">
                        <div class="max-w-3xl w-full">
                            <div class="bg-[var(--color-surface)] rounded-2xl rounded-tl-sm px-6 py-4 shadow-sm border border-[var(--color-outline-variant)]">
                                <div x-show="message.loading" class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                    </div>
                                    <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface-variant)]">
                                        Open.Overheid.nl is een antwoord aan het genereren...
                                    </p>
                                </div>
                                <div x-show="!message.loading">
                                    <!-- AI Answer -->
                                    <template x-if="message.answer">
                                        <div class="mb-6">
                                            <div class="mb-4">
                                                <p class="text-[var(--font-size-body-small)] font-semibold text-[var(--color-on-surface-variant)] mb-2">Antwoord door Open.Overheid.nl</p>
                                                <p class="text-[var(--font-size-body-large)] text-[var(--color-on-surface)] leading-relaxed whitespace-pre-wrap" x-html="formatAnswerWithSources(message.answer)"></p>
                                            </div>
                                            
                                            <!-- Disclaimer -->
                                            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg" x-data="{ dismissed: false }" x-show="!dismissed">
                                                <div class="flex items-start gap-3">
                                                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 text-yellow-600 shrink-0 mt-0.5">
                                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <p class="text-[var(--font-size-body-small)] text-yellow-800">
                                                            Open.Overheid.nl kan fouten maken. Controleer altijd de bronnen. Het antwoord op je vraag wordt gegenereerd door AI met als doel om jou te ondersteunen bij het vinden van informatie.
                                                        </p>
                                                    </div>
                                                    <button @click="dismissed = true" class="text-yellow-600 hover:text-yellow-800">
                                                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Sources Section -->
                                            <template x-if="message.sources && message.sources.length > 0">
                                                <div class="mb-6">
                                                    <h3 class="text-[var(--font-size-body-medium)] font-semibold text-[var(--color-on-surface)] mb-3">Bronnen</h3>
                                                    <div class="space-y-2">
                                                        <template x-for="(source, sourceIndex) in message.sources" :key="sourceIndex">
                                                            <a 
                                                                :href="source.url"
                                                                class="block px-4 py-3 rounded-lg border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/5 transition-all duration-200"
                                                            >
                                                                <div class="flex items-start gap-3">
                                                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] text-[var(--font-size-label-small)] font-semibold shrink-0" x-text="source.number"></span>
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="text-[var(--font-size-body-medium)] font-medium text-[var(--color-on-surface)]" x-text="source.title"></p>
                                                                        <template x-if="source.organisation">
                                                                            <p class="text-[var(--font-size-body-small)] text-[var(--color-on-surface-variant)] mt-1" x-text="source.organisation"></p>
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
                                        <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface)] mb-4" x-text="message.text"></p>
                                    </template>
                                    
                                    <!-- No results message -->
                                    <template x-if="!message.answer && (!message.results || message.results.length === 0)">
                                        <p class="text-[var(--font-size-body-medium)] text-[var(--color-on-surface)] mb-4" x-text="message.text"></p>
                                    </template>
                                    
                                    <!-- Search Results -->
                                    <template x-if="message.results && message.results.length > 0">
                                        <div class="mt-6">
                                            <h3 class="text-[var(--font-size-body-medium)] font-semibold text-[var(--color-on-surface)] mb-4">
                                                Zoekresultaten (<span x-text="message.results.length"></span>)
                                            </h3>
                                            <div class="space-y-4">
                                                <template x-for="(result, resultIndex) in message.results" :key="resultIndex">
                                                    <a 
                                                        :href="'/open-overheid/documents/' + result.id"
                                                        class="block p-4 rounded-lg border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/5 transition-all duration-200"
                                                    >
                                                        <div class="flex items-start justify-between gap-4 mb-2">
                                                            <h4 class="text-[var(--font-size-body-medium)] font-semibold text-[var(--color-on-surface)] flex-1" x-text="result.title"></h4>
                                                            <template x-if="result.formatted_category">
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded text-[var(--font-size-label-small)] font-semibold bg-[var(--color-primary-light)] text-[var(--color-primary)] shrink-0" x-text="result.formatted_category"></span>
                                                            </template>
                                                        </div>
                                                        <p class="text-[var(--font-size-body-small)] text-[var(--color-on-surface-variant)] mb-3 line-clamp-2" x-text="result.description"></p>
                                                        <div class="flex items-center gap-4 text-[var(--font-size-label-small)] text-[var(--color-on-surface-variant)]">
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
                                                        class="inline-flex items-center gap-2 text-[var(--font-size-body-medium)] font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors"
                                                    >
                                                        Toon alle zoekresultaten
                                                        <i class="fas fa-arrow-right text-[var(--font-size-label-small)]" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <p class="text-[var(--font-size-label-small)] text-[var(--color-on-surface-variant)] mt-2" x-text="formatTime(message.timestamp)"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input Area -->
        <div class="sticky bottom-0 bg-[var(--color-surface)] border-t border-[var(--color-outline-variant)] py-4">
            <div class="max-w-4xl mx-auto px-6">
                <form @submit.prevent="sendMessage()" class="flex items-center gap-4">
                    <div class="flex-1 relative">
                        <input 
                            type="text"
                            x-model="inputText"
                            @keydown.enter.prevent="sendMessage()"
                            placeholder="Stel een vervolgvraag"
                            class="w-full px-6 py-4 pr-12 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent text-[var(--font-size-body-medium)] text-[var(--color-on-surface)] placeholder:text-[var(--color-on-surface-variant)]"
                            :disabled="loading"
                        >
                        <button 
                            type="submit"
                            :disabled="loading || !inputText.trim()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] hover:bg-[var(--color-primary-dark)] disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center"
                        >
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path d="M3.5 2.75a.75.75 0 0 0-1.5 0v14.5a.75.75 0 0 0 1.5 0v-4.392l1.657-.348a6.449 6.449 0 0 1 4.271.572 7.948 7.948 0 0 0 5.965.44l2.087-.694a.75.75 0 0 0 .42-.941l-.8-2.385a.75.75 0 0 0-.42-.499l-2.087-.694a7.948 7.948 0 0 0-5.965.44 6.449 6.449 0 0 1-4.271.572L3.5 7.25v-4.5Z" />
                            </svg>
                        </button>
                    </div>
                </form>
                <p class="text-center mt-4 text-[var(--font-size-body-small)] text-[var(--color-on-surface-variant)]">
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
            this.sendMessage();
        },
        
        async sendMessage() {
            if (!this.inputText.trim() || this.loading) {
                return;
            }
            
            const userMessage = this.inputText.trim();
            this.inputText = '';
            
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
                    return `${word} <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] text-[var(--font-size-label-small)] font-semibold mx-1 align-middle">${num}</span>`;
                })
                // Also match standalone numbers that are likely source references (1, 2, 3) when they appear in context
                .replace(/\b([1-5])\b(?=\s+(staan|vermeldt|volgens|document|bron|bronnen))/gi, (match, num) => {
                    return `<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[var(--color-primary)] text-[var(--color-on-primary)] text-[var(--font-size-label-small)] font-semibold mx-1 align-middle">${num}</span>`;
                });
        },
    };
}
</script>
@endpush
@endsection

