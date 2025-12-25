@props(['title' => 'Informatie', 'text' => ''])

<div class="bg-white rounded-lg border border-gray-200 p-5">
    <h3 class="text-base font-semibold text-gray-900 mb-3">{{ $title }}</h3>
    <p class="text-sm text-gray-600 leading-relaxed">{{ $text }}</p>
    {{ $slot }}
</div>
