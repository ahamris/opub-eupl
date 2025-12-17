@extends('layouts.chat')

@section('title', 'Chat met Open Overheid - Vraag het Open.Overheid.nl')

@section('content')
    <!-- Chat Container -->
    <div class="flex flex-col flex-1 min-h-0 bg-[var(--color-surface)]" x-data="chatInterface()">
        
        <!-- Chat Messages Area (Scrollable) -->
        <div class="chat-messages px-4 sm:px-6 lg:px-8 py-4 scrollbar-hide" x-ref="messagesContainer">
            <div class="max-w-3xl mx-auto min-h-full flex flex-col" :class="messages.length === 0 ? 'justify-center' : 'justify-start'">
                
                <!-- Welcome Message -->
                <template x-if="messages.length === 0">
                    <div class="py-10 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row items-center gap-6 mb-8">
                            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-[var(--color-primary-light)] shrink-0">
                                <svg class="w-10 h-10 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-semibold text-[var(--color-on-surface)]">
                                    Waarmee kan ik je helpen?
                                </h2>
                                <p class="text-base text-[var(--color-on-surface-variant)] mt-2 max-w-xl">
                                    Stel je vraag over overheidsdocumenten in gewone taal. Ik zoek door duizenden bronnen om je het juiste antwoord te geven.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Suggested Questions Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-2xl mx-auto sm:mx-0">
                            <button 
                                @click="askQuestion('Wat zijn de regels voor parkeervergunningen?')"
                                class="text-left p-4 rounded-xl bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:bg-[var(--color-surface-variant)] hover:border-[var(--color-outline-variant)] transition-all duration-200 group"
                            >
                                <span class="block text-sm font-medium text-[var(--color-on-surface)] mb-1">Parkeervergunningen</span>
                                <span class="block text-xs text-[var(--color-on-surface-variant)] group-hover:text-[var(--color-on-surface)]">Wat zijn de regels?</span>
                            </button>
                            <button 
                                @click="askQuestion('Hoe vraag ik een uitkering aan?')"
                                class="text-left p-4 rounded-xl bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:bg-[var(--color-surface-variant)] hover:border-[var(--color-outline-variant)] transition-all duration-200 group"
                            >
                                <span class="block text-sm font-medium text-[var(--color-on-surface)] mb-1">Uitkering aanvragen</span>
                                <span class="block text-xs text-[var(--color-on-surface-variant)] group-hover:text-[var(--color-on-surface)]">Hoe werkt het proces?</span>
                            </button>
                            <button 
                                @click="askQuestion('Wanneer zijn de schoolvakanties?')"
                                class="text-left p-4 rounded-xl bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:bg-[var(--color-surface-variant)] hover:border-[var(--color-outline-variant)] transition-all duration-200 group"
                            >
                                <span class="block text-sm font-medium text-[var(--color-on-surface)] mb-1">Schoolvakanties</span>
                                <span class="block text-xs text-[var(--color-on-surface-variant)] group-hover:text-[var(--color-on-surface)]">Wanneer zijn ze dit jaar?</span>
                            </button>
                            <button 
                                @click="askQuestion('Zoek documenten over duurzaamheid')"
                                class="text-left p-4 rounded-xl bg-[var(--color-surface)] border border-[var(--color-outline-variant)] hover:bg-[var(--color-surface-variant)] hover:border-[var(--color-outline-variant)] transition-all duration-200 group"
                            >
                                <span class="block text-sm font-medium text-[var(--color-on-surface)] mb-1">Duurzaamheid</span>
                                <span class="block text-xs text-[var(--color-on-surface-variant)] group-hover:text-[var(--color-on-surface)]">Zoek relevante documenten</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Chat Messages -->
                <div class="space-y-6 py-4">
                    <template x-for="(message, index) in messages" :key="index">
                        <div class="flex flex-col gap-4">
                            <!-- User Message -->
                            <div x-show="message.type === 'user'" class="flex justify-end">
                                <div class="max-w-[85%] sm:max-w-[75%] bg-[var(--color-primary)] text-white px-5 py-3.5 rounded-2xl rounded-tr-sm shadow-sm">
                                    <p class="text-sm sm:text-base whitespace-pre-wrap" x-text="message.text"></p>
                                </div>
                            </div>

                            <!-- AI Message -->
                            <div x-show="message.type === 'ai'" class="flex gap-3 max-w-full">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-dark)] flex items-center justify-center mt-1 shadow-sm">
                                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 space-y-3">
                                    <!-- Loading State -->
                                    <div x-show="message.loading" class="flex items-center gap-1.5 py-3">
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                                        <div class="w-2 h-2 bg-[var(--color-primary)] rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                                    </div>

                                    <!-- Answer Content -->
                                    <div x-show="!message.loading" class="text-[var(--color-on-surface)]">
                                        <!-- Main Answer -->
                                        <div x-show="message.answer">
                                            <div class="text-sm sm:text-base leading-relaxed whitespace-pre-wrap" x-html="formatAnswerWithSources(message.answer)"></div>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center gap-1 mt-3">
                                                <button 
                                                    @click="copyToClipboard(message.answer)" 
                                                    class="p-1.5 rounded-lg text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] transition-colors"
                                                    title="Kopieer antwoord"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                </button>
                                                <button class="p-1.5 rounded-lg text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] transition-colors" title="Nuttig">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                                </button>
                                                <button class="p-1.5 rounded-lg text-[var(--color-on-surface-variant)] hover:bg-[var(--color-surface-variant)] hover:text-[var(--color-on-surface)] transition-colors" title="Niet nuttig">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Sources -->
                                        <template x-if="message.sources && message.sources.length > 0">
                                            <div class="mt-4 pt-4 border-t border-[var(--color-outline-variant)]">
                                                <h4 class="text-xs font-semibold text-[var(--color-on-surface-variant)] uppercase tracking-wider mb-3">Gebruikte bronnen</h4>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    <template x-for="(source, idx) in message.sources" :key="idx">
                                                        <a :href="source.url" target="_blank" class="flex items-start gap-3 p-3 rounded-lg border border-[var(--color-outline-variant)] hover:bg-[var(--color-surface-variant)] transition-colors group">
                                                            <span class="shrink-0 flex items-center justify-center w-5 h-5 rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-xs font-medium" x-text="idx + 1"></span>
                                                            <div class="min-w-0">
                                                                <p class="text-sm font-medium text-[var(--color-on-surface)] truncate group-hover:text-[var(--color-primary)] transition-colors" x-text="source.title"></p>
                                                                <p class="text-xs text-[var(--color-on-surface-variant)] truncate" x-text="source.organisation || 'Open Overheid'"></p>
                                                            </div>
                                                        </a>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Search Results (Fallback) -->
                                        <template x-if="!message.answer && message.results && message.results.length > 0">
                                            <div class="space-y-3">
                                                <p class="text-sm text-[var(--color-on-surface)]" x-text="message.text"></p>
                                                <div class="grid gap-3">
                                                    <template x-for="(result, idx) in message.results" :key="idx">
                                                        <a :href="'/open-overheid/documents/' + result.id" class="block p-4 rounded-lg border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)] transition-colors">
                                                            <h4 class="text-sm font-semibold text-[var(--color-primary)] mb-1" x-text="result.title"></h4>
                                                            <p class="text-xs text-[var(--color-on-surface-variant)] line-clamp-2" x-text="result.description"></p>
                                                        </a>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- No results message -->
                                        <template x-if="!message.answer && (!message.results || message.results.length === 0) && !message.loading">
                                            <p class="text-sm text-[var(--color-on-surface)]" x-text="message.text"></p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Input Area (Fixed at bottom within flex) -->
        <div class="chat-input bg-[var(--color-surface)] border-t border-[var(--color-outline-variant)] p-3 sm:p-4">
            <div class="max-w-3xl mx-auto">
                <!-- Input Container -->
                <div class="relative flex items-end gap-2 bg-[var(--color-surface)] rounded-md border border-[var(--color-outline-variant)] transition-colors focus-within:border-[var(--color-primary)]">
                    <textarea 
                        x-ref="promptInput"
                        x-model="inputText"
                        @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                        @input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'"
                        rows="1"
                        placeholder="Stel je vraag..."
                        class="w-full bg-transparent border-0 focus:ring-0 resize-none py-3 px-4 text-sm text-[var(--color-on-surface)] placeholder:text-[var(--color-on-surface-variant)] max-h-[120px] overflow-y-auto scrollbar-hide"
                        style="min-height: 44px;"
                    ></textarea>
                    
                    <button 
                        @click="sendMessage()"
                        :disabled="!inputText.trim() || loading"
                        class="shrink-0 p-2 m-1.5 rounded-md bg-[var(--color-primary)] text-white disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[var(--color-primary-dark)] transition-colors"
                    >
                        <svg x-show="!loading" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12h12M12 6l6 6-6 6" />
                        </svg>
                        <div x-show="loading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    </button>
                </div>
                <p class="text-center text-xs text-[var(--color-on-surface-variant)] mt-2">
                    AI kan fouten maken. Controleer belangrijke informatie.
                </p>
            </div>
        </div>
    </div>
@endsection

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
            
            // Reset textarea height
            if (this.$refs.promptInput) {
                this.$refs.promptInput.style.height = '44px';
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
        
        copyToClipboard(text) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                // Could add a toast notification here
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
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
            return answer
                .replace(/(document|Document|volgens|Volgens|in|In)\s+(\d+)/gi, (match, word, num) => {
                    return `${word} <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[var(--color-primary)] text-white text-xs font-medium mx-0.5 align-middle">${num}</span>`;
                })
                .replace(/\b([1-5])\b(?=\s+(staan|vermeldt|volgens|document|bron|bronnen))/gi, (match, num) => {
                    return `<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[var(--color-primary)] text-white text-xs font-medium mx-0.5 align-middle">${num}</span>`;
                });
        },
    };
}
</script>
@endpush
