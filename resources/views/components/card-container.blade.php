@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden ' . ($padding ? 'p-6' : '')]) }}>
    {{ $slot }}
</div>