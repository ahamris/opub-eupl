@extends('layouts.app')

@section('title', $blog->og_title ?? $blog->title . ' - Blog - Open Overheid')

@push('styles')
    {{-- Open Graph Meta Tags --}}
    @php
        $ogTitle = $blog->og_title ?? $blog->title;
        $ogDescription = $blog->og_description ?? strip_tags($blog->short_body ?? '');
        $ogImage = $blog->og_image ? asset('storage/' . $blog->og_image) : ($blog->image ? asset('storage/' . $blog->image) : (get_setting('og_image') ? asset('storage/' . get_setting('og_image')) : null));
        $ogUrl = url()->current();
    @endphp
    
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ $ogUrl }}">
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    @if($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif
@endpush

@section('content')
    <!-- Blog Header Section -->
    <div class="relative bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 -z-10">
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/50" fill="none">
                <defs>
                    <pattern id="blog-show-header-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M.5 40V.5H40" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#blog-show-header-grid)" />
            </svg>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
        </div>
        
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12 sm:py-16 relative z-10">
            <!-- Breadcrumb -->
            @if(!empty($breadcrumbs))
            <div class="mb-8">
                <x-breadcrumbs :items="$breadcrumbs" />
            </div>
            @endif
            
            <!-- Header Content -->
            <div class="max-w-4xl space-y-6">
                <!-- Title -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[var(--color-primary-dark)] leading-tight">
                    {{ $blog->title }}
                </h1>
                
                <!-- Short Description -->
                @if($blog->short_body)
                <p class="text-base sm:text-lg text-[var(--color-on-surface-variant)] leading-relaxed max-w-3xl">
                    {{ strip_tags($blog->short_body) }}
                </p>
                @endif
                
                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <!-- Category -->
                    @if($blog->blog_category)
                    <a href="{{ route('blog.index', ['category' => $blog->blog_category->slug]) }}" 
                       class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-3 py-1.5 rounded-md font-semibold hover:bg-[var(--color-primary-dark)] transition-colors">
                        <i class="fas fa-folder text-xs"></i>
                        {{ $blog->blog_category->name }}
                    </a>
                    @endif
                    
                    <!-- Date -->
                    <span class="inline-flex items-center gap-2 text-[var(--color-primary-dark)]">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $blog->created_at->format('d F Y') }}
                    </span>

                    <!-- Reading Time (estimated) -->
                    @php
                        $wordCount = str_word_count(strip_tags($blog->long_body ?? ''));
                        $readingTime = max(1, ceil($wordCount / 200));
                    @endphp
                    <span class="inline-flex items-center gap-2 text-[var(--color-primary-dark)]">
                        <i class="fas fa-clock"></i>
                        {{ $readingTime }} {{ $readingTime === 1 ? 'minuut' : 'minuten' }} leestijd
                    </span>
                    
                    <!-- Author -->
                    @if($blog->author)
                    <span class="inline-flex items-center gap-2 text-[var(--color-primary-dark)]">
                        <i class="fas fa-user"></i>
                        {{ $blog->author->name }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Bottom gradient line -->
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto w-full px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
            
            <!-- Article Content (3 columns) -->
            <article class="lg:col-span-3">
                <!-- Featured Image -->
                @if($blog->image)
                <div class="mb-8 rounded-lg overflow-hidden border border-[var(--color-outline-variant)]">
                    <img src="{{ asset('storage/' . $blog->image) }}" 
                         alt="{{ $blog->title }}" 
                         class="w-full h-auto object-cover">
                </div>
                @endif
                
                <!-- Article Body -->
                <div class="prose prose-lg max-w-none 
                            prose-headings:text-[var(--color-on-surface)] prose-headings:font-semibold
                            prose-p:text-[var(--color-on-surface-variant)] prose-p:leading-relaxed
                            prose-a:text-[var(--color-primary)] prose-a:no-underline hover:prose-a:underline
                            prose-strong:text-[var(--color-on-surface)]
                            prose-ul:text-[var(--color-on-surface-variant)]
                            prose-ol:text-[var(--color-on-surface-variant)]
                            prose-li:marker:text-[var(--color-primary)]
                            prose-blockquote:border-l-[var(--color-primary)] prose-blockquote:text-[var(--color-on-surface-variant)]
                            prose-code:text-[var(--color-primary)] prose-code:bg-[var(--color-surface-variant)] prose-code:px-1 prose-code:py-0.5 prose-code:rounded
                            prose-img:rounded-lg prose-img:border prose-img:border-[var(--color-outline-variant)]">
                    {!! $blog->long_body !!}
                </div>
                
                <!-- Share Section -->
                <div class="mt-10 pt-8 border-t border-[var(--color-outline-variant)]">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Category Link -->
                        @if($blog->blog_category)
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-[var(--color-on-surface-variant)]">Categorie:</span>
                            <a href="{{ route('blog.index', ['category' => $blog->blog_category->slug]) }}" 
                               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[var(--color-primary)]/10 text-[var(--color-primary)] hover:bg-[var(--color-primary)]/20 transition-colors">
                                {{ $blog->blog_category->name }}
                            </a>
                        </div>
                        @endif
                        
                        <!-- Share Buttons -->
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-[var(--color-on-surface-variant)]">Delen:</span>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-[var(--color-surface-variant)] flex items-center justify-center text-[var(--color-on-surface-variant)] hover:bg-[var(--color-primary)] hover:text-white transition-colors">
                                <i class="fab fa-x-twitter text-sm"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($blog->title) }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-[var(--color-surface-variant)] flex items-center justify-center text-[var(--color-on-surface-variant)] hover:bg-[var(--color-primary)] hover:text-white transition-colors">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-[var(--color-surface-variant)] flex items-center justify-center text-[var(--color-on-surface-variant)] hover:bg-[var(--color-primary)] hover:text-white transition-colors">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href); this.classList.add('bg-green-500', 'text-white'); setTimeout(() => this.classList.remove('bg-green-500', 'text-white'), 2000);"
                                    class="w-8 h-8 rounded-full bg-[var(--color-surface-variant)] flex items-center justify-center text-[var(--color-on-surface-variant)] hover:bg-[var(--color-primary)] hover:text-white transition-colors"
                                    title="Kopieer link">
                                <i class="fas fa-link text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Back to Blog -->
                <div class="mt-8">
                    <a href="{{ route('blog.index') }}" 
                       class="inline-flex items-center gap-2 text-sm font-medium text-[var(--color-primary)] hover:text-[var(--color-primary-dark)] transition-colors">
                        <i class="fas fa-arrow-left text-xs"></i>
                        Terug naar alle artikelen
                    </a>
                </div>
            </article>

            <!-- Sidebar (1 column) -->
            <aside class="lg:col-span-1 space-y-6">
                <!-- Author Card -->
                @if($blog->author)
                <div class="bg-[var(--color-surface)] rounded-lg p-5 border border-[var(--color-outline-variant)]">
                    <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-4">Over de auteur</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center">
                            <i class="fas fa-user text-lg text-[var(--color-primary)]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[var(--color-on-surface)]">{{ $blog->author->name }}</p>
                            <p class="text-xs text-[var(--color-on-surface-variant)]">Auteur</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Related Articles -->
                @if($relatedBlogs->count() > 0)
                <div class="bg-[var(--color-surface)] rounded-lg p-5 border border-[var(--color-outline-variant)]">
                    <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-4">Gerelateerde artikelen</h3>
                    <div class="space-y-4">
                        @foreach($relatedBlogs as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="flex gap-3 group">
                            <div class="shrink-0 w-14 h-14 rounded-md overflow-hidden bg-[var(--color-surface-variant)]">
                                @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" 
                                     alt="{{ $related->title }}" 
                                     class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-gradient-to-br from-[var(--color-primary)]/10 to-[var(--color-primary)]/5 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-xs text-[var(--color-primary)]/30"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors line-clamp-2">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-xs text-[var(--color-on-surface-variant)] mt-1">
                                    {{ $related->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- CTA -->
                <div class="bg-gradient-to-br from-[var(--color-primary)]/5 to-[var(--color-primary)]/10 rounded-lg p-5 border border-[var(--color-primary)]/20">
                    <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-2">Meer lezen?</h3>
                    <p class="text-xs text-[var(--color-on-surface-variant)] mb-4">
                        Bekijk al onze artikelen over open overheid en transparantie.
                    </p>
                    <a href="{{ route('blog.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--color-primary)] text-white text-sm font-medium rounded-md hover:bg-[var(--color-primary-dark)] transition-colors w-full justify-center">
                        <i class="fas fa-newspaper text-xs"></i>
                        Alle artikelen
                    </a>
                </div>
            </aside>
        </div>
    </main>
@endsection
