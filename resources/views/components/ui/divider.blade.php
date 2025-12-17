@php
    $styleAttr = '';
    if ($width) {
        $styleAttr = 'style="width: ' . $width . '"';
    }
@endphp

@if($orientation === 'vertical')
    <div class="{{ $classes }}" {!! $styleAttr !!} {{ $attributes }}></div>
@else
    @if($text)
        <div class="flex items-center {{ $width ? '' : 'w-full' }}" {!! $width ? 'style="width: ' . $width . '"' : '' !!} {{ $attributes }}>
            <div class="{{ $classes }}" {!! $styleAttr !!}></div>
            <span class="px-4 text-sm text-zinc-500 dark:text-zinc-400 whitespace-nowrap">{{ $text }}</span>
            <div class="{{ $classes }}" {!! $styleAttr !!}></div>
        </div>
    @else
        <div class="{{ $classes }}" {!! $styleAttr !!} {{ $attributes }}></div>
    @endif
@endif

