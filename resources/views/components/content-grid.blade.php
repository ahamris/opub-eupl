@props([
    'testimonial' => null,
    'testimonialAuthor' => null,
    'testimonialRole' => null,
    'testimonialImage' => null,
])

<div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:mt-10 lg:max-w-none lg:grid-cols-12">
    @if($testimonial)
    <!-- Testimonial -->
    <div class="relative lg:order-last lg:col-span-5">
        <svg aria-hidden="true" class="absolute -top-160 left-1 -z-10 h-256 w-702 -translate-x-1/2 mask-[radial-gradient(64rem_64rem_at_111.5rem_0%,white,transparent)] stroke-outline-variant/50">
            <defs>
                <pattern id="grid-pattern-{{ uniqid() }}" width="200" height="200" patternUnits="userSpaceOnUse">
                    <path d="M0.5 0V200M200 0.5L0 0.499983" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern-{{ uniqid() }})" stroke-width="0" />
        </svg>
        <figure class="border-l-4 border-primary pl-8">
            <blockquote class="text-xl/8 font-semibold tracking-tight text-on-surface">
                <p>{{ $testimonial }}</p>
            </blockquote>
            @if($testimonialAuthor)
            <figcaption class="mt-8 flex gap-x-4">
                @if($testimonialImage)
                <img
                    src="{{ $testimonialImage }}"
                    alt="{{ $testimonialAuthor }}"
                    class="mt-1 size-10 flex-none rounded-full bg-surface-variant"
                />
                @else
                <div class="mt-1 size-10 flex-none rounded-full bg-primary-container flex items-center justify-center">
                    <i class="fas fa-user text-on-primary-container" aria-hidden="true"></i>
                </div>
                @endif
                <div class="text-sm/6">
                    <div class="font-semibold text-on-surface">{{ $testimonialAuthor }}</div>
                    @if($testimonialRole)
                    <div class="text-on-surface-variant">{{ $testimonialRole }}</div>
                    @endif
                </div>
            </figcaption>
            @endif
        </figure>
    </div>
    @endif

    <!-- Content -->
    <div class="max-w-xl text-base/7 text-on-surface-variant lg:col-span-{{ $testimonial ? '7' : '12' }}">
        {{ $slot }}
    </div>
</div>

