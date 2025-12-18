@extends('layouts.app')

@section('title', 'Blog - Open Overheid')

@section('content')
    <x-page-header 
        eyebrow="Nieuws & Updates"
        title="Blog"
        description="Blijf op de hoogte van het laatste nieuws, updates en inzichten over open overheid en transparantie."
        :breadcrumbs="$breadcrumbs"
    />

    <!-- Main Content Section -->
    <section class="flex items-center justify-center py-12" id="content">
        <div class="max-w-7xl mx-auto w-full px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">

                    <!-- Main Content (3 columns) -->
                    <div class="lg:col-span-3 space-y-10">
                        
                        <!-- Featured Blog Post (First/Latest) -->
                        @if($featuredBlogs->count() > 0)
                            @php $featured = $featuredBlogs->first(); @endphp
                            <a href="{{ route('blog.show', $featured->slug) }}" class="block bg-[var(--color-surface)] rounded-lg border border-[var(--color-outline-variant)] overflow-hidden hover:border-[var(--color-primary)]/50 transition-all duration-300 group">
                                <div class="md:flex">
                                    <div class="md:w-1/2 relative">
                                        <div class="aspect-square md:aspect-auto md:h-full">
                                            @if($featured->image)
                                            <img src="{{ asset('storage/' . $featured->image) }}" 
                                                 alt="{{ $featured->title }}" 
                                                 class="w-full h-full object-cover">
                                            @else
                                            <div class="w-full h-full min-h-[300px] bg-gradient-to-br from-[var(--color-primary)]/10 to-[var(--color-primary)]/5 flex items-center justify-center">
                                                <i class="fas fa-newspaper text-6xl text-[var(--color-primary)]/20"></i>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="md:w-1/2 p-6 lg:p-8 flex flex-col justify-center">
                                        @if($featured->blog_category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-3">
                                            {{ $featured->blog_category->name }}
                                        </span>
                                        @endif
                                        <h2 class="text-xl lg:text-2xl font-semibold text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors duration-200 mb-4 line-clamp-3">
                                            {{ $featured->title }}
                                        </h2>
                                        @if($featured->short_body)
                                        <p class="text-sm text-[var(--color-on-surface-variant)] line-clamp-4 mb-4">
                                            {{ Str::limit(strip_tags($featured->short_body), 200) }}
                                        </p>
                                        @endif
                                        <div class="flex items-center gap-3 text-xs text-[var(--color-on-surface-variant)]">
                                            @if($featured->author)
                                            <span>{{ $featured->author->name }}</span>
                                            <span>•</span>
                                            @endif
                                            <span>{{ $featured->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif
                        
                        <!-- Blog Grid -->
                        @if($blogs->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($blogs as $blog)
                            <a href="{{ route('blog.show', $blog->slug) }}" class="block bg-[var(--color-surface)] rounded-lg overflow-hidden border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)]/50 transition-all duration-300 group">
                                <div class="aspect-[16/10] overflow-hidden">
                                    @if($blog->image)
                                    <img src="{{ asset('storage/' . $blog->image) }}" 
                                         alt="{{ $blog->title }}" 
                                         class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full bg-gradient-to-br from-[var(--color-primary)]/10 to-[var(--color-primary)]/5 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-3xl text-[var(--color-primary)]/20"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="p-5">
                                    @if($blog->blog_category)
                                    <span class="text-xs font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2 block">
                                        {{ $blog->blog_category->name }}
                                    </span>
                                    @endif
                                    <h3 class="text-base font-semibold text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors duration-200 line-clamp-2 mb-2">
                                        {{ $blog->title }}
                                    </h3>
                                    <p class="text-xs text-[var(--color-on-surface-variant)]">
                                        {{ $blog->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($blogs->hasPages())
                        <div class="pt-6">
                            {{ $blogs->withQueryString()->links() }}
                        </div>
                        @endif
                        @else
                        <div class="bg-[var(--color-surface)] rounded-lg border border-[var(--color-outline-variant)] p-12 text-center">
                            <i class="fas fa-newspaper text-4xl text-[var(--color-on-surface-variant)]/30 mb-4"></i>
                            <p class="text-base text-[var(--color-on-surface-variant)] mb-2">Geen artikelen gevonden.</p>
                            <p class="text-sm text-[var(--color-on-surface-variant)]">
                                @if(request('category'))
                                    Er zijn nog geen artikelen in deze categorie.
                                    <a href="{{ route('blog.index') }}" class="text-[var(--color-primary)] hover:underline">Bekijk alle artikelen</a>
                                @else
                                    Er zijn nog geen blogartikelen gepubliceerd.
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar (1 column) -->
                    <aside class="lg:col-span-1 space-y-6">
                        
                        <!-- Search -->
                        <div>
                            <label for="blog-search" class="sr-only">Zoeken</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-[var(--color-on-surface-variant)]"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       id="blog-search" 
                                       placeholder="Zoeken in blog..." 
                                       class="block w-full pl-10 pr-4 py-3 border border-[var(--color-outline-variant)] rounded-lg bg-[var(--color-surface)] text-[var(--color-on-surface)] placeholder-[var(--color-on-surface-variant)] focus:outline-none focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)] text-sm transition-colors">
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="bg-[var(--color-surface)] rounded-lg p-5 border border-[var(--color-outline-variant)]">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-4">Categorieën</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('blog.index') }}" 
                                       class="flex items-center justify-between py-1.5 text-sm transition-colors {{ !request('category') ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }}">
                                        <span>Alle artikelen</span>
                                        <span class="text-xs bg-[var(--color-surface-variant)] px-2 py-0.5 rounded-full">{{ $blogs->total() }}</span>
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                                       class="flex items-center justify-between py-1.5 text-sm transition-colors {{ request('category') === $category->slug ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-on-surface-variant)] hover:text-[var(--color-primary)]' }}">
                                        <span>{{ $category->name }}</span>
                                        <span class="text-xs bg-[var(--color-surface-variant)] px-2 py-0.5 rounded-full">{{ $category->blogs_count }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Recent Posts -->
                        @if($featuredBlogs->count() > 1)
                        <div class="bg-[var(--color-surface)] rounded-lg p-5 border border-[var(--color-outline-variant)]">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-4">Recente artikelen</h3>
                            <div class="space-y-4">
                                @foreach($featuredBlogs->skip(1)->take(3) as $recent)
                                <a href="{{ route('blog.show', $recent->slug) }}" class="flex gap-3 group">
                                    <div class="shrink-0 w-14 h-14 rounded-md overflow-hidden bg-[var(--color-surface-variant)]">
                                        @if($recent->image)
                                        <img src="{{ asset('storage/' . $recent->image) }}" 
                                             alt="{{ $recent->title }}" 
                                             class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full bg-gradient-to-br from-[var(--color-primary)]/10 to-[var(--color-primary)]/5 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-xs text-[var(--color-primary)]/30"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-[var(--color-on-surface)] group-hover:text-[var(--color-primary)] transition-colors line-clamp-2">
                                            {{ $recent->title }}
                                        </h4>
                                        <p class="text-xs text-[var(--color-on-surface-variant)] mt-1">
                                            {{ $recent->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- CTA -->
                        <div class="bg-gradient-to-br from-[var(--color-primary)]/5 to-[var(--color-primary)]/10 rounded-lg p-5 border border-[var(--color-primary)]/20">
                            <h3 class="text-sm font-semibold text-[var(--color-on-surface)] mb-2">Blijf op de hoogte</h3>
                            <p class="text-xs text-[var(--color-on-surface-variant)] mb-4">
                                Ontvang updates over belangrijke ontwikkelingen in open overheid.
                            </p>
                            <a href="{{ route('contact') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--color-primary)] text-white text-sm font-medium rounded-md hover:bg-[var(--color-primary-dark)] transition-colors w-full justify-center">
                                <i class="fas fa-envelope text-xs"></i>
                                Contact opnemen
                            </a>
                        </div>
                    </aside>

                </div>
        </div>
    </section>
@endsection
