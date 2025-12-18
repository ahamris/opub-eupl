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
                <div class="inline-flex items-center gap-x-3 rounded-md bg-white px-4 py-1.5 text-sm text-[var(--color-on-surface-variant)] border border-[var(--color-outline-variant)]">
                    @if($badge)
                    <span class="font-semibold text-[var(--color-purple)]">{{ $badge }}</span>
                    @endif
                    @if($badge && $badgeText)
                    <span aria-hidden="true" class="h-4 w-px bg-[var(--color-outline-variant)]"></span>
                    @endif
                    @if($badgeText)
                    <a href="#" class="inline-flex items-center gap-x-1.5 hover:text-[var(--color-primary)] transition-colors duration-200">
                        <span class="leading-none select-none">{{ $badgeText }}</span>
                        <i class="fas fa-arrow-right text-[10px] leading-none transform translate-y-[0.5px] transition-transform" aria-hidden="true"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endif
            
            <h1 class="text-4xl font-semibold tracking-tight text-pretty sm:text-5xl lg:text-6xl">
                {{ $title }}
            </h1>
            
            @if($description)
            <p class="mt-6 text-lg font-medium text-pretty text-[var(--color-on-surface-variant)] sm:text-xl/8">{{ $description }}</p>
            @endif
            
            <div class="mt-8 flex items-center gap-x-2 text-sm font-medium text-[var(--color-on-surface-variant)]">
                <span class="inline-flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-[var(--color-purple)] animate-pulse" aria-hidden="true"></span>
                    <span x-data="liveDocumentCounter({{ $documentCount }})">
                        <span class="font-semibold text-[var(--color-on-surface)]" x-text="formatNumber(count)"></span>
                        <span>documenten beschikbaar</span>
                    </span>
                </span>
            </div>
        </div>
        
        <!-- Right side: Search functionality -->
        <div class="mx-auto mt-16 w-full max-w-2xl lg:col-span-7 lg:mx-0 lg:mt-0 xl:col-span-6">
            <div class="relative isolate">
                <!-- Enhanced Search Card -->
                <div class="rounded-md bg-white p-8 border border-[var(--color-outline-variant)] transition-all duration-300">
                    <!-- Header Section -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-xl font-semibold">Slim zoeken</h3>
                                <p class="mt-1 text-sm">Zoek snel in alle documenten</p>
                            </div>
                            <a href="{{ route('chat') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-gradient-to-br from-blue-600 to-purple-600 text-white text-xs font-bold tracking-wide transition-all duration-200 whitespace-nowrap hover:opacity-90 active:opacity-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" class="size-4">
                                    <path fill-rule="evenodd" d="M5 4a.75.75 0 0 1 .738.616l.252 1.388A1.25 1.25 0 0 0 6.996 7.01l1.388.252a.75.75 0 0 1 0 1.476l-1.388.252A1.25 1.25 0 0 0 5.99 9.996l-.252 1.388a.75.75 0 0 1-1.476 0L4.01 9.996A1.25 1.25 0 0 0 3.004 8.99l-1.388-.252a.75.75 0 0 1 0-1.476l1.388-.252A1.25 1.25 0 0 0 4.01 6.004l.252-1.388A.75.75 0 0 1 5 4ZM12 1a.75.75 0 0 1 .721.544l.195.682c.118.415.443.74.858.858l.682.195a.75.75 0 0 1 0 1.442l-.682.195a1.25 1.25 0 0 0-.858.858l-.195.682a.75.75 0 0 1-1.442 0l-.195-.682a1.25 1.25 0 0 0-.858-.858l-.682-.195a.75.75 0 0 1 0-1.442l.682-.195a1.25 1.25 0 0 0 .858-.858l.195-.682A.75.75 0 0 1 12 1ZM10 11a.75.75 0 0 1 .728.568.968.968 0 0 0 .704.704.75.75 0 0 1 0 1.456.968.968 0 0 0-.704.704.75.75 0 0 1-1.456 0 .968.968 0 0 0-.704-.704.75.75 0 0 1 0-1.456.968.968 0 0 0 .704-.704A.75.75 0 0 1 10 11Z" clip-rule="evenodd"/>
                                </svg>
                                <span>Chat met AI</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Typesense Autocomplete Container -->
                    <div id="hero-autocomplete" class="ts-autocomplete-wrapper"></div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6">
                        <p class="text-xs font-medium text-[var(--color-primary-dark)] mb-3 uppercase">Populaire zoekopdrachten</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('themas.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[var(--color-primary-dark)] px-2.5 py-1.5 text-xs font-medium text-[var(--color-on-primary)] hover:bg-[var(--color-primary-dark)]/80 transition-colors duration-200 border border-[var(--color-primary)]/20">
                                <i class="fas fa-tags text-[10px]" aria-hidden="true"></i>
                                <span>Thema's</span>
                            </a>
                            <a href="{{ route('dossiers.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[var(--color-purple)] px-2.5 py-1.5 text-xs font-medium text-[var(--color-on-primary)] hover:bg-[var(--color-purple)]/80 transition-colors duration-200 border border-[var(--color-primary)]/20">
                                <i class="fas fa-folder text-[10px]" aria-hidden="true"></i>
                                <span>Dossiers</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-medium text-[var(--color-primary-dark)] hover:bg-[var(--color-primary)]/10 transition-colors duration-200 border border-[var(--color-primary)]/20">
                                <i class="fas fa-chart-bar text-[10px]" aria-hidden="true"></i>
                                <span>Rapporten</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Animated Grid with "Random Sparkle & Fade" Effect
function heroGrid() {
    return {
        canvas: null,
        ctx: null,
        cells: [],
        gridSize: 80,
        animationId: null,
        mouseX: -1000,
        mouseY: -1000,
        
        colors: [
            { r: 59, g: 130, b: 246 },
            { r: 139, g: 92, b: 246 },
            { r: 6, g: 182, b: 212 },
            { r: 99, g: 102, b: 241 },
            { r: 168, g: 85, b: 247 },
        ],
        
        init() {
            this.canvas = this.$refs.gridCanvas;
            if (!this.canvas) return;
            this.ctx = this.canvas.getContext('2d');
            this.resize();
            this.animate();
            window.addEventListener('resize', () => this.resize());
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
            this.ctx.scale(dpr, dpr);
            this.createGrid();
        },
        
        createGrid() {
            this.cells = [];
            const rect = this.canvas.parentElement.getBoundingClientRect();
            const cols = Math.ceil(rect.width / this.gridSize);
            const rows = Math.ceil(rect.height / this.gridSize);
            
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    this.cells.push({
                        x: col * this.gridSize,
                        y: row * this.gridSize,
                        color: this.colors[Math.floor(Math.random() * this.colors.length)],
                        opacity: 0,
                        state: 'idle', // idle, appearing, disappearing
                        speed: 0.0002 + Math.random() * 0.00005 
                    });
                }
            }
        },
        
        animate() {
            this.draw();
            this.animationId = requestAnimationFrame(() => this.animate());
        },
        
        draw() {
            const rect = this.canvas.parentElement.getBoundingClientRect();
            this.ctx.clearRect(0, 0, rect.width, rect.height);
            
            // Sabit Grid Çizgileri (Çok hafif)
            this.ctx.strokeStyle = 'rgba(191, 219, 254, 0.08)';
            this.ctx.lineWidth = 0.5;

            // Çok nadir yeni kare başlat (Saniyede 1-2 tane)
            if (Math.random() < 0.01) { 
                const idleCells = this.cells.filter(c => c.state === 'idle');
                if (idleCells.length > 0) {
                    const cell = idleCells[Math.floor(Math.random() * idleCells.length)];
                    cell.state = 'appearing';
                }
            }

            this.cells.forEach(cell => {
                // Durum Yönetimi (Soft Belirme ve Kaybolma)
                if (cell.state === 'appearing') {
                    cell.opacity += cell.speed;
                    if (cell.opacity >= 0.25) { // Max parlaklık %25
                        cell.state = 'disappearing';
                    }
                } else if (cell.state === 'disappearing') {
                    cell.opacity -= cell.speed;
                    if (cell.opacity <= 0) {
                        cell.opacity = 0;
                        cell.state = 'idle';
                    }
                }
                
                // Mouse Etkisi (Bağımsız ve yumuşak)
                let mouseOpacity = 0;
                const dx = this.mouseX - (cell.x + this.gridSize / 2);
                const dy = this.mouseY - (cell.y + this.gridSize / 2);
                const distance = Math.sqrt(dx * dx + dy * dy);
                if (distance < 120) {
                    mouseOpacity = (1 - distance / 120) * 0.12;
                }

                const finalOpacity = Math.max(cell.opacity, mouseOpacity);

                if (finalOpacity > 0) {
                    this.ctx.fillStyle = `rgba(${cell.color.r}, ${cell.color.g}, ${cell.color.b}, ${finalOpacity})`;
                    this.ctx.fillRect(cell.x + 1, cell.y + 1, this.gridSize - 2, this.gridSize - 2);
                    
                    // Daha da yumuşak iç ışık
                    if (finalOpacity > 0.05) {
                        this.ctx.fillStyle = `rgba(255, 255, 255, ${finalOpacity * 0.2})`;
                        this.ctx.fillRect(cell.x + this.gridSize*0.3, cell.y + this.gridSize*0.3, this.gridSize*0.4, this.gridSize*0.4);
                    }
                }
                this.ctx.strokeRect(cell.x, cell.y, this.gridSize, this.gridSize);
            });
        },
        
        destroy() {
            if (this.animationId) cancelAnimationFrame(this.animationId);
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

// Initialize Typesense Autocomplete on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize autocomplete if container exists and function is available
    if (document.getElementById('hero-autocomplete') && typeof window.initTypesenseAutocomplete === 'function') {
        window.initTypesenseAutocomplete({
            container: '#hero-autocomplete',
            liveSearchUrl: '{{ route("api.live-search") }}',
            autocompleteUrl: '{{ route("api.autocomplete") }}',
            searchRoute: '{{ route("zoeken") }}',
            documentRoute: '/open-overheid/documents',
            placeholder: 'Zoek in alle documenten...',
        });
    }
});
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
