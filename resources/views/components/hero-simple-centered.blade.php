@props([
    'title',
    'description' => null,
    'actions' => null,
])

<div class="bg-surface">
    <div class="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h1 class="text-4xl font-semibold tracking-tight text-on-surface sm:text-6xl">
                {{ $title }}
            </h1>
            @if($description)
            <p class="mt-6 text-lg/8 text-on-surface-variant">
                {{ $description }}
            </p>
            @endif
            @if($actions)
            <div class="mt-10 flex items-center justify-center gap-x-6">
                {{ $actions }}
            </div>
            @endif
        </div>
    </div>
</div>

