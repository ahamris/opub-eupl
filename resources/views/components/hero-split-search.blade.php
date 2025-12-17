@props([
    'badge' => null,
    'badgeText' => null,
    'title',
    'description' => null,
    'documentCount' => 0,
])

<div class="relative isolate bg-white group z-20">
    <!-- Animated Grid Background with Wave Effect -->
    <div class="hero-grid-container absolute inset-0 -z-10 overflow-hidden" x-data="heroGrid()" x-init="init()">
        <!-- Base gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/40 via-purple-50/20 to-cyan-50/30"></div>
        
        <!-- Animated grid canvas -->
        <canvas x-ref="gridCanvas" class="absolute inset-0 w-full h-full opacity-80"></canvas>
        
        <!-- Soft radial mask for fade effect -->
        <div class="absolute inset-0" style="background: radial-gradient(ellipse 120% 100% at 80% 0%, transparent 40%, white 100%);"></div>
    </div>
    

    <div class="mx-auto max-w-7xl px-6 pt-24 pb-24 sm:pt-24 sm:pb-24 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-center lg:px-8 lg:pt-32 lg:pb-32">
        <!-- Left side: Text content -->
        <div class="mx-auto max-w-2xl lg:col-span-5 lg:mx-0 xl:col-span-6">
            @if($badge || $badgeText)
            <div class="mb-8">
                <div class="inline-flex items-center gap-x-3 rounded-full bg-white px-4 py-1.5 text-sm text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)] shadow-sm">
                    @if($badge)
                    <span class="font-semibold text-[var(--color-primary)]">{{ $badge }}</span>
                    @endif
                    @if($badge && $badgeText)
                    <span aria-hidden="true" class="h-4 w-px bg-[var(--color-outline-variant)]"></span>
                    @endif
                    @if($badgeText)
                    <a href="#" class="flex items-center gap-x-1.5 hover:text-[var(--color-primary)] transition-colors duration-200">
                        {{ $badgeText }}
                        <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endif
            
            <h1 class="text-4xl font-semibold tracking-tight text-pretty text-[var(--color-on-surface)] sm:text-5xl lg:text-6xl">
                {{ $title }}
            </h1>
            
            @if($description)
            <p class="mt-6 text-lg font-medium text-pretty text-[var(--color-on-surface-variant)] sm:text-xl/8">{{ $description }}</p>
            @endif
            
            <div class="mt-8 flex items-center gap-x-2 text-sm font-medium text-[var(--color-on-surface-variant)]">
                <span class="inline-flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-[var(--color-primary)] animate-pulse" aria-hidden="true"></span>
                    <span x-data="liveDocumentCounter({{ $documentCount }})">
                        <span class="font-semibold text-[var(--color-on-surface)]" x-text="formatNumber(count)"></span>
                        <span>documenten beschikbaar</span>
                    </span>
                </span>
            </div>
        </div>
        
        <!-- Right side: Search functionality -->
        <div class="mx-auto mt-16 w-full max-w-2xl lg:col-span-7 lg:mx-0 lg:mt-0 xl:col-span-6">
            <div class="relative isolate" x-data="liveSearch()" @click.outside="showResults = false">
                <!-- Enhanced Search Card -->
                <div class="rounded-md bg-white p-8 border border-[var(--color-outline-variant)] shadow-sm transition-all duration-300 hover:shadow-md hover:border-[var(--color-primary)]/30">
                    <!-- Header Section -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-[var(--color-on-surface)]">Slim zoeken</h3>
                                <p class="mt-1 text-sm text-[var(--color-on-surface-variant)]">Zoek snel in alle documenten</p>
                            </div>
                            <a href="{{ route('chat') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-[var(--color-on-primary)] hover:bg-[var(--color-primary-dark)] focus:outline-none transition-colors duration-200 whitespace-nowrap">
                                <i class="fas fa-comments" aria-hidden="true"></i>
                                <span>Chat met AI</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Enhanced Search Input -->
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 z-10">
                            <i class="fas fa-search text-[var(--color-on-surface-variant)] text-base" aria-hidden="true"></i>
                        </div>
                        <input 
                            type="text" 
                            x-model="query"
                            @input="handleInput()"
                            @focus="handleFocus()"
                            @keydown.escape="showResults = false; query = ''"
                            @keydown.arrow-down.prevent="navigateResults(1)"
                            @keydown.arrow-up.prevent="navigateResults(-1)"
                            @keydown.enter.prevent="selectResult()"
                            class="block w-full rounded-md bg-white px-4 py-3.5 pl-12 pr-12 text-base text-[var(--color-on-surface)] border-2 border-[var(--color-outline-variant)] placeholder:text-[var(--color-on-surface-variant)] focus:outline-none focus:border-[var(--color-primary)] transition-all duration-200"
                            placeholder="Zoek in alle documenten..."
                            autocomplete="off"
                            aria-label="Zoek documenten"
                            :aria-expanded="showResults ? 'true' : 'false'"
                            aria-haspopup="listbox"
                            aria-autocomplete="list"
                        >
                        <div x-show="loading" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 z-10">
                            <i class="fas fa-circle-notch animate-spin text-[var(--color-primary)] text-base" aria-hidden="true"></i>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6">
                        <p class="text-xs font-medium text-[var(--color-on-surface-variant)] mb-3 uppercase tracking-wide">Populaire zoekopdrachten</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('themas.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[var(--color-primary-light)]/50 px-3 py-1.5 text-xs font-medium text-[var(--color-primary)] hover:bg-[var(--color-primary-light)] transition-colors duration-200 border border-[var(--color-primary)]/20">
                                <i class="fas fa-tags text-[10px]" aria-hidden="true"></i>
                                <span>Thema's</span>
                            </a>
                            <a href="{{ route('dossiers.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[var(--color-primary-light)]/50 px-3 py-1.5 text-xs font-medium text-[var(--color-primary)] hover:bg-[var(--color-primary-light)] transition-colors duration-200 border border-[var(--color-primary)]/20">
                                <i class="fas fa-folder text-[10px]" aria-hidden="true"></i>
                                <span>Dossiers</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[var(--color-primary-light)]/50 px-3 py-1.5 text-xs font-medium text-[var(--color-primary)] hover:bg-[var(--color-primary-light)] transition-colors duration-200 border border-[var(--color-primary)]/20">
                                <i class="fas fa-chart-bar text-[10px]" aria-hidden="true"></i>
                                <span>Rapporten</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Dropdown Results -->
                <div
                    x-show="showResults && (query.length >= 2 || loading)"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute inset-x-0 top-full mt-2 bg-white rounded-md border border-[var(--color-outline-variant)] shadow-lg max-h-96 overflow-y-auto z-[99999]"
                    role="listbox"
                    @click.stop
                >
                    <!-- Autocomplete Suggestions -->
                    <template x-if="suggestions.length > 0">
                        <div>
                            <div class="px-4 py-2.5 border-b border-[var(--color-outline-variant)] bg-[var(--color-surface-variant)]">
                                <p class="text-xs font-semibold text-[var(--color-on-surface-variant)] uppercase tracking-wide">Suggesties</p>
                            </div>
                            <ul class="py-1" role="listbox">
                                <template x-for="(suggestion, index) in suggestions" :key="index">
                                    <li 
                                        @click="selectSuggestion(suggestion)"
                                        @mouseenter="selectedIndex = index"
                                        :class="selectedIndex === index ? 'bg-[var(--color-primary)]/10' : 'hover:bg-[var(--color-primary)]/10'"
                                        class="px-4 py-2.5 cursor-pointer transition-colors duration-150"
                                        role="option"
                                        :aria-selected="selectedIndex === index"
                                    >
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-bolt text-xs text-[var(--color-primary)] flex-shrink-0" aria-hidden="true"></i>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-[var(--color-on-surface)] leading-snug" x-html="suggestion.highlight || suggestion.query"></div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Search Results -->
                    <template x-if="results.length > 0">
                        <div>
                            <div class="px-4 py-2.5 border-b border-[var(--color-outline-variant)] bg-[var(--color-surface-variant)]">
                                <p class="text-xs font-semibold text-[var(--color-on-surface-variant)] uppercase tracking-wide">
                                    <span x-text="found"></span> resultaten
                                    <template x-if="searchTime > 0">
                                        <span class="text-[var(--color-on-surface-variant)]/70 font-normal"> in <span x-text="searchTime"></span>ms</span>
                                    </template>
                                </p>
                            </div>
                            <ul class="py-1" role="listbox">
                                <template x-for="(result, index) in results" :key="result.id">
                                    <li 
                                        @click="goToDetail(result.id)"
                                        @mouseenter="selectedIndex = suggestions.length + index"
                                        :class="selectedIndex === (suggestions.length + index) ? 'bg-[var(--color-primary)]/10' : 'hover:bg-[var(--color-primary)]/10'"
                                        class="px-4 py-3 cursor-pointer transition-colors duration-150 border-b border-[var(--color-outline-variant)] last:border-b-0"
                                        role="option"
                                        :aria-selected="selectedIndex === (suggestions.length + index)"
                                    >
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-3 mb-1.5">
                                                    <h3 class="text-sm font-medium text-[var(--color-on-surface)] truncate flex-1 leading-snug" x-text="result.title"></h3>
                                                    <template x-if="result.formatted_category || result.category">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-[var(--color-primary-light)] text-[var(--color-primary)] shrink-0 border border-[var(--color-primary)]/20">
                                                            <span x-text="result.formatted_category || result.category"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                                <p class="mt-1 text-xs text-[var(--color-on-surface-variant)] line-clamp-2 leading-snug" x-text="result.description"></p>
                                                <div class="mt-2 flex items-center gap-4 text-[11px] text-[var(--color-on-surface-variant)]">
                                                    <template x-if="result.organisation">
                                                        <span class="inline-flex items-center gap-1.5">
                                                            <i class="fas fa-building text-[10px]" aria-hidden="true"></i>
                                                            <span class="tracking-tight" x-text="result.organisation"></span>
                                                        </span>
                                                    </template>
                                                    <template x-if="result.publication_date">
                                                        <span class="inline-flex items-center gap-1.5">
                                                            <i class="fas fa-calendar text-[10px]" aria-hidden="true"></i>
                                                            <span class="tracking-tight" x-text="formatDate(result.publication_date)"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                            <i class="fas fa-chevron-right text-[var(--color-outline-variant)] shrink-0 mt-1 text-xs" aria-hidden="true"></i>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <!-- Footer: View all results -->
                    <template x-if="(suggestions.length > 0 || results.length > 0) && query.length >= 2">
                        <div class="px-4 py-3 border-t border-[var(--color-outline-variant)] bg-[var(--color-surface-variant)]">
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="text-sm font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors duration-150 inline-flex items-center gap-2 group"
                            >
                                Bekijk alle resultaten
                                <i class="fas fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </template>
                    
                    <!-- No Results -->
                    <template x-if="suggestions.length === 0 && results.length === 0 && query.length >= 2 && !loading">
                        <div class="px-4 py-6 text-center text-sm">
                            <p class="text-[var(--color-on-surface-variant)] mb-3">Geen resultaten gevonden voor "<span class="font-medium text-[var(--color-on-surface)]" x-text="query"></span>"</p>
                            <a 
                                :href="'{{ route('zoeken') }}?zoeken=' + encodeURIComponent(query)"
                                class="text-sm font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors duration-150 inline-flex items-center gap-2 group"
                            >
                                Toch zoeken
                                <i class="fas fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Animated Grid with Wave Effect - AI-inspired soft animation
function heroGrid() {
    return {
        canvas: null,
        ctx: null,
        cells: [],
        gridSize: 40,
        animationId: null,
        time: 0,
        mouseX: -1000,
        mouseY: -1000,
        
        // Soft, AI-inspired color palette
        colors: [
            { r: 59, g: 130, b: 246 },   // blue-500
            { r: 139, g: 92, b: 246 },   // violet-500
            { r: 6, g: 182, b: 212 },    // cyan-500
            { r: 99, g: 102, b: 241 },   // indigo-500
            { r: 20, g: 184, b: 166 },   // teal-500
            { r: 168, g: 85, b: 247 },   // purple-500
        ],
        
        init() {
            this.canvas = this.$refs.gridCanvas;
            if (!this.canvas) return;
            
            this.ctx = this.canvas.getContext('2d');
            this.resize();
            this.createGrid();
            this.animate();
            
            // Handle resize
            window.addEventListener('resize', () => this.resize());
            
            // Optional: mouse interaction for subtle ripple effect
            this.canvas.addEventListener('mousemove', (e) => {
                const rect = this.canvas.getBoundingClientRect();
                this.mouseX = e.clientX - rect.left;
                this.mouseY = e.clientY - rect.top;
            });
            
            this.canvas.addEventListener('mouseleave', () => {
                this.mouseX = -1000;
                this.mouseY = -1000;
            });
        },
        
        resize() {
            const dpr = window.devicePixelRatio || 1;
            const rect = this.canvas.parentElement.getBoundingClientRect();
            
            this.canvas.width = rect.width * dpr;
            this.canvas.height = rect.height * dpr;
            this.canvas.style.width = rect.width + 'px';
            this.canvas.style.height = rect.height + 'px';
            
            this.ctx.scale(dpr, dpr);
            this.createGrid();
        },
        
        createGrid() {
            this.cells = [];
            const rect = this.canvas.parentElement.getBoundingClientRect();
            const cols = Math.ceil(rect.width / this.gridSize) + 1;
            const rows = Math.ceil(rect.height / this.gridSize) + 1;
            
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    // Randomly select some cells to be colored
                    if (Math.random() < 0.15) {
                        this.cells.push({
                            x: col * this.gridSize,
                            y: row * this.gridSize,
                            color: this.colors[Math.floor(Math.random() * this.colors.length)],
                            phase: Math.random() * Math.PI * 2,
                            speed: 0.25 + Math.random() * 0.2,
                            baseOpacity: 0.08 + Math.random() * 0.12
                        });
                    }
                }
            }
        },
        
        animate() {
            this.time += 0.005;
            this.draw();
            this.animationId = requestAnimationFrame(() => this.animate());
        },
        
        draw() {
            const rect = this.canvas.parentElement.getBoundingClientRect();
            this.ctx.clearRect(0, 0, rect.width, rect.height);
            
            // Draw grid lines
            this.ctx.strokeStyle = 'rgba(191, 219, 254, 0.3)';
            this.ctx.lineWidth = 0.5;
            
            const cols = Math.ceil(rect.width / this.gridSize) + 1;
            const rows = Math.ceil(rect.height / this.gridSize) + 1;
            
            // Vertical lines
            for (let col = 0; col <= cols; col++) {
                const x = col * this.gridSize;
                // Add subtle wave to line opacity
                const waveOffset = Math.sin(this.time * 0.3 + col * 0.1) * 0.1 + 0.3;
                this.ctx.strokeStyle = `rgba(191, 219, 254, ${waveOffset})`;
                this.ctx.beginPath();
                this.ctx.moveTo(x, 0);
                this.ctx.lineTo(x, rect.height);
                this.ctx.stroke();
            }
            
            // Horizontal lines
            for (let row = 0; row <= rows; row++) {
                const y = row * this.gridSize;
                const waveOffset = Math.sin(this.time * 0.3 + row * 0.1) * 0.1 + 0.3;
                this.ctx.strokeStyle = `rgba(191, 219, 254, ${waveOffset})`;
                this.ctx.beginPath();
                this.ctx.moveTo(0, y);
                this.ctx.lineTo(rect.width, y);
                this.ctx.stroke();
            }
            
            // Draw animated colored cells
            this.cells.forEach(cell => {
                // Calculate wave-based opacity
                const waveValue = Math.sin(this.time * cell.speed + cell.phase);
                const normalizedWave = (waveValue + 1) / 2; // 0 to 1
                
                // Mouse proximity effect (subtle)
                let mouseEffect = 0;
                const dx = this.mouseX - (cell.x + this.gridSize / 2);
                const dy = this.mouseY - (cell.y + this.gridSize / 2);
                const distance = Math.sqrt(dx * dx + dy * dy);
                if (distance < 150) {
                    mouseEffect = (1 - distance / 150) * 0.15;
                }
                
                // Global wave ripple effect
                const centerX = rect.width * 0.7;
                const centerY = rect.height * 0.3;
                const distFromCenter = Math.sqrt(
                    Math.pow(cell.x - centerX, 2) + 
                    Math.pow(cell.y - centerY, 2)
                );
                const ripple = Math.sin(distFromCenter * 0.01 - this.time * 0.8) * 0.5 + 0.5;
                
                const opacity = (cell.baseOpacity * normalizedWave * 0.6 + ripple * 0.08 + mouseEffect) * 0.8;
                
                // Draw the cell with soft glow
                this.ctx.fillStyle = `rgba(${cell.color.r}, ${cell.color.g}, ${cell.color.b}, ${opacity})`;
                this.ctx.fillRect(cell.x + 1, cell.y + 1, this.gridSize - 2, this.gridSize - 2);
                
                // Add subtle inner glow for highlighted cells
                if (normalizedWave > 0.7 || mouseEffect > 0.05) {
                    const glowOpacity = opacity * 0.3;
                    this.ctx.fillStyle = `rgba(255, 255, 255, ${glowOpacity})`;
                    this.ctx.fillRect(cell.x + 4, cell.y + 4, this.gridSize - 8, this.gridSize - 8);
                }
            });
        },
        
        destroy() {
            if (this.animationId) {
                cancelAnimationFrame(this.animationId);
            }
        }
    };
}

// Live Document Counter - Updates periodically to show real-time document count
function liveDocumentCounter(initialCount) {
    return {
        count: initialCount,
        interval: null,
        
        init() {
            // Update every 30 seconds to show live count
            this.interval = setInterval(() => {
                this.updateCount();
            }, 30000);
            
            // Initial update after 5 seconds
            setTimeout(() => {
                this.updateCount();
            }, 5000);
        },
        
        async updateCount() {
            try {
                const response = await fetch('{{ route("api.live-search") }}?q=&limit=1');
                if (response.ok) {
                    const data = await response.json();
                    if (data.total_found !== undefined) {
                        // Smooth transition to new count
                        const targetCount = data.total_found;
                        this.animateCount(this.count, targetCount);
                        
                        // Update page title if it exists
                        const titleElement = document.querySelector('title');
                        if (titleElement && titleElement.__x) {
                            titleElement.__x.$data.documentCount = targetCount;
                        }
                    }
                }
            } catch (error) {
                console.error('Failed to update document count:', error);
            }
        },
        
        animateCount(from, to) {
            const duration = 1000;
            const startTime = Date.now();
            const difference = to - from;
            
            const animate = () => {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                this.count = Math.round(from + (difference * easeOutQuart));
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    this.count = to;
                }
            };
            
            animate();
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat('nl-NL').format(num);
        },
        
        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        }
    }
}

function liveSearch() {
    return {
        query: '',
        suggestions: [],
        results: [],
        found: 0,
        searchTime: 0,
        loading: false,
        showResults: false,
        selectedIndex: -1,
        searchTimeout: null,
        
        handleInput() {
            if (this.query.length >= 2) {
                this.showResults = true;
            }
            
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            this.searchTimeout = setTimeout(() => {
                this.search();
            }, 200);
        },
        
        handleFocus() {
            if (this.query.length >= 2) {
                this.showResults = true;
                if (this.results.length === 0 && this.suggestions.length === 0 && !this.loading) {
                    this.search();
                }
            }
        },
        
        async search() {
            if (this.query.length < 2) {
                this.suggestions = [];
                this.results = [];
                this.found = 0;
                this.showResults = false;
                this.loading = false;
                return;
            }
            
            this.loading = true;
            this.selectedIndex = -1;
            this.showResults = true;
            
            try {
                // Fetch both autocomplete suggestions and search results in parallel
                const [suggestionsResponse, searchResponse] = await Promise.all([
                    fetch(`{{ route('api.autocomplete') }}?q=${encodeURIComponent(this.query)}&limit=3`),
                    fetch(`{{ route('api.live-search') }}?q=${encodeURIComponent(this.query)}&limit=5`)
                ]);
                
                // Process autocomplete suggestions first (prioritized)
                if (suggestionsResponse.ok) {
                    const suggestionsData = await suggestionsResponse.json();
                    this.suggestions = (suggestionsData.suggestions || []).map(suggestion => ({
                        ...suggestion,
                        isSuggestion: true
                    }));
                }
                
                // Process search results
                if (searchResponse.ok) {
                    const searchData = await searchResponse.json();
                    this.results = searchData.hits || [];
                    this.found = searchData.found || 0;
                    this.searchTime = searchData.search_time_ms || 0;
                }
                
                this.showResults = true;
            } catch (error) {
                console.error('Search error:', error);
                this.suggestions = [];
                this.results = [];
                this.found = 0;
                this.showResults = true;
            } finally {
                this.loading = false;
            }
        },
        
        get allItems() {
            // Prioritize suggestions first, then results
            return [...this.suggestions, ...this.results];
        },
        
        navigateResults(direction) {
            const items = this.allItems;
            if (items.length === 0) return;
            
            this.selectedIndex += direction;
            if (this.selectedIndex < 0) {
                this.selectedIndex = items.length - 1;
            } else if (this.selectedIndex >= items.length) {
                this.selectedIndex = 0;
            }
        },
        
        selectResult() {
            const items = this.allItems;
            if (this.selectedIndex >= 0 && items[this.selectedIndex]) {
                const item = items[this.selectedIndex];
                if (item.isSuggestion && item.query) {
                    // Autocomplete suggestion - navigate to search page
                    window.location.href = `{{ route('zoeken') }}?zoeken=${encodeURIComponent(item.query)}`;
                } else if (item.id) {
                    // Document result - navigate to document detail
                    this.goToDetail(item.id);
                }
            } else if (this.query.length >= 2) {
                window.location.href = `{{ route('zoeken') }}?zoeken=${encodeURIComponent(this.query)}`;
            }
        },
        
        selectSuggestion(suggestion) {
            if (suggestion.id) {
                // If suggestion has an ID, go directly to the document
                this.goToDetail(suggestion.id);
            } else if (suggestion.query) {
                // Update search input with suggestion query and trigger live search
                this.query = suggestion.query;
                // Trigger live search immediately
                this.search();
            }
        },
        
        goToDetail(id) {
            if (id) {
                window.location.href = `/open-overheid/documents/${id}`;
            }
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
    }
}
</script>
@endpush

@push('styles')
<style>
    .hero-grid-container canvas {
        pointer-events: none;
    }
    
    /* Reduce motion for accessibility */
    @media (prefers-reduced-motion: reduce) {
        .hero-grid-container canvas {
            opacity: 0.4;
        }
    }
</style>
@endpush
