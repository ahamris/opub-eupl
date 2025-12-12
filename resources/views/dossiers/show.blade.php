@extends('layouts.app')

@section('title', 'Dossier: ' . ($document->title ?? 'Onbekend') . ' - Open Overheid')

@section('breadcrumbs')
    U bent hier: 
    <a href="{{ route('home') }}" class="text-[var(--color-primary)] hover:underline focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded-sm">Home</a> / 
    <a href="{{ route('dossiers.index') }}" class="text-[var(--color-primary)] hover:underline focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded-sm">Dossiers</a> / 
    <span class="text-[var(--color-on-surface)]">Dossier</span>
@endsection

@section('content')
<div class="mx-auto max-w-7xl px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('dossiers.index') }}" 
           class="inline-flex items-center gap-2 text-[var(--color-primary)] font-medium mb-4
                  hover:text-[var(--color-primary-dark)] focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2
                  transition-colors duration-200 rounded-sm">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            <span>Terug naar dossiers</span>
        </a>
        
        <div class="bg-[var(--color-primary-light)] rounded-2xl p-6 border border-[var(--color-outline-variant)] mb-6">
            <div class="flex items-start gap-4 mb-4">
                <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-[var(--color-primary)]/20 flex items-center justify-center">
                    <i class="fas fa-folder-open text-[var(--color-primary)] text-2xl" aria-hidden="true"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <h1 class="text-[var(--font-size-headline-medium)] font-semibold text-[var(--color-on-surface)]">
                            {{ $aiContent->enhanced_title ?? $document->title ?? 'Geen titel beschikbaar' }}
                            @if($aiContent && $aiContent->enhanced_title)
                                <span class="text-xs font-normal text-[var(--color-on-surface-variant)] ml-2" title="AI-verbeterde titel">
                                    <i class="fas fa-sparkles" aria-hidden="true"></i>
                                </span>
                            @endif
                        </h1>
                        @if(!$aiContent || empty($aiContent->summary))
                            <button 
                                id="enhance-dossier-btn"
                                onclick="enhanceDossier()"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[var(--color-primary)] text-[var(--color-on-primary)]
                                       hover:bg-[var(--color-primary)]/90 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2
                                       transition-colors duration-200 text-sm font-medium">
                                <i class="fas fa-sparkles" aria-hidden="true"></i>
                                <span>Maak AI-samenvatting</span>
                            </button>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm text-[var(--color-on-surface-variant)]">
                        @if($document->document_type)
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fas fa-file-alt text-xs" aria-hidden="true"></i>
                                <span>{{ $document->document_type }}</span>
                            </span>
                        @endif
                        @if($document->publication_date)
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fas fa-calendar text-xs" aria-hidden="true"></i>
                                <span>{{ $document->publication_date->format('d-m-Y') }}</span>
                            </span>
                        @endif
                        @if($document->organisation)
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fas fa-building text-xs" aria-hidden="true"></i>
                                <span>{{ $document->organisation }}</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <p class="text-sm text-[var(--color-on-surface-variant)] mb-4">
                Dit dossier bevat <strong>{{ $dossierCount }}</strong> gerelateerd{{ $dossierCount !== 1 ? 'e' : '' }} document{{ $dossierCount !== 1 ? 'en' : '' }}.
            </p>
            @if($document->description)
                <p class="text-sm text-[var(--color-on-surface-variant)]">
                    {{ $document->description }}
                </p>
            @endif
        </div>
    </div>

    <!-- AI Summary Section -->
    @if($aiContent && !empty($aiContent->summary))
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[var(--color-outline-variant)] mb-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-[var(--color-primary)]/10 flex items-center justify-center">
                        <i class="fas fa-sparkles text-[var(--color-primary)] text-lg" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-[var(--color-on-surface)]">AI-samenvatting</h2>
                        <p class="text-xs text-[var(--color-on-surface-variant)]">Begrijpelijke samenvatting op B1-niveau</p>
                    </div>
                </div>
            </div>
            
            <div class="prose prose-sm max-w-none mb-4">
                <p class="text-sm text-[var(--color-on-surface)] leading-relaxed">
                    {{ $aiContent->summary }}
                </p>
            </div>

            @if($aiContent->keywords && !empty(json_decode($aiContent->keywords, true)))
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-[var(--color-outline-variant)]">
                    <span class="text-xs font-medium text-[var(--color-on-surface-variant)] mr-2">Keywords:</span>
                    @foreach(json_decode($aiContent->keywords, true) as $keyword)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[var(--color-primary)]/10 text-[var(--color-primary)] border border-[var(--color-primary)]/20">
                            {{ $keyword }}
                        </span>
                    @endforeach
                </div>
            @endif

            @if($aiContent->audio_url)
                <div class="mt-4 pt-4 border-t border-[var(--color-outline-variant)]">
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-[var(--color-on-surface)] mb-1">Audio samenvatting</h3>
                            <p class="text-xs text-[var(--color-on-surface-variant)]">Beluister deze samenvatting als podcast</p>
                        </div>
                        <audio controls class="flex-1 max-w-md" id="dossier-audio-player">
                            <source src="{{ $aiContent->audio_url }}" type="audio/mpeg">
                            Je browser ondersteunt geen audio element.
                        </audio>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-lg font-medium text-[var(--color-on-surface)] mb-4">Documenten in dit dossier</h2>
    </div>

    @if($dossierMembers->count() > 0)
    <div class="space-y-4">
        @foreach($dossierMembers as $member)
        <article class="bg-white rounded-2xl p-6 shadow-sm border border-[var(--color-outline-variant)] hover:border-[var(--color-primary)]/30 transition-all duration-200
                        {{ $member->id === $document->id ? 'ring-2 ring-[var(--color-primary)]/20 bg-[var(--color-primary-light)]/30' : '' }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[var(--color-primary)]/10 flex items-center justify-center">
                            <i class="fas fa-file-alt text-[var(--color-primary)]" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            @if($member->id === $document->id)
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-[var(--color-primary)]/20 text-[var(--color-primary)] text-xs font-medium mb-2">
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                    <span>Huidig document</span>
                                </span>
                            @endif
                            <h3 class="text-sm font-medium text-[var(--color-on-surface)] mb-2">
                                <a href="/open-overheid/documents/{{ $member->external_id }}" 
                                   class="hover:text-[var(--color-primary)] transition-colors duration-200 focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2 rounded-sm">
                                    {{ $member->title ?? 'Geen titel' }}
                                </a>
                            </h3>
                            <div class="flex flex-wrap gap-4 text-xs text-[var(--color-on-surface-variant)] mb-3">
                                @if($member->document_type)
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-file-alt text-xs" aria-hidden="true"></i>
                                        <span>{{ $member->document_type }}</span>
                                    </span>
                                @endif
                                @if($member->publication_date)
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-calendar text-xs" aria-hidden="true"></i>
                                        <span>{{ $member->publication_date->format('d-m-Y') }}</span>
                                    </span>
                                @endif
                                @if($member->organisation)
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-building text-xs" aria-hidden="true"></i>
                                        <span>{{ $member->organisation }}</span>
                                    </span>
                                @endif
                            </div>
                            @if($member->description)
                                <p class="text-xs text-[var(--color-on-surface-variant)] line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($member->description, 200) }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="/open-overheid/documents/{{ $member->external_id }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                  bg-[var(--color-primary)] text-[var(--color-on-primary)] border border-[var(--color-primary)]
                                  hover:bg-[var(--color-primary)]/90 hover:border-[var(--color-primary)]/80
                                  focus:outline-2 focus:outline-[var(--color-primary)] focus:outline-offset-2
                                  transition-all duration-200 text-sm font-medium">
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            <span>Bekijk document</span>
                        </a>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    @else
    <div class="bg-[var(--color-surface-variant)] rounded-2xl p-12 text-center border border-[var(--color-outline-variant)]">
        <i class="fas fa-folder-open text-4xl text-[var(--color-on-surface-variant)]/50 mb-4" aria-hidden="true"></i>
        <h2 class="text-lg font-medium text-[var(--color-on-surface)] mb-2">Geen gerelateerde documenten</h2>
        <p class="text-sm text-[var(--color-on-surface-variant)]">
            Er zijn geen andere documenten gevonden in dit dossier.
        </p>
    </div>
    @endif
</div>

@push('scripts')
<script>
    async function enhanceDossier() {
        const btn = document.getElementById('enhance-dossier-btn');
        if (!btn) return;

        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> <span>Genereren...</span>';

        try {
            const response = await fetch('{{ route("dossiers.enhance", $document->external_id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (response.ok) {
                // Show success message
                btn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i> <span>Gestart!</span>';
                btn.classList.remove('bg-[var(--color-primary)]', 'text-[var(--color-on-primary)]');
                btn.classList.add('bg-green-600', 'text-white');
                
                // Poll for summary availability
                setTimeout(() => {
                    checkSummaryStatus();
                }, 3000);
            } else {
                throw new Error(data.message || 'Fout bij genereren');
            }
        } catch (error) {
            console.error('Enhance error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Er is een fout opgetreden bij het genereren van de samenvatting. Probeer het later opnieuw.');
        }
    }

    async function checkSummaryStatus() {
        try {
            const response = await fetch('{{ route("dossiers.summary", $document->external_id) }}');
            const data = await response.json();

            if (data.status === 'ready' && data.summary) {
                // Reload page to show summary
                window.location.reload();
            } else if (data.status === 'processing') {
                // Check again in 3 seconds
                setTimeout(() => {
                    checkSummaryStatus();
                }, 3000);
            }
        } catch (error) {
            console.error('Status check error:', error);
        }
    }
</script>
@endpush
@endsection
