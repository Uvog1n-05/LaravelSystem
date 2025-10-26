<x-alert>
@props([
    'type' => 'info', // success, error, warning, info
    'dismissible' => false,
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-green-50',
            'text' => 'text-green-800',
            'border' => 'border-green-200',
            'icon' => 'fas fa-check-circle',
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'text' => 'text-red-800',
            'border' => 'border-red-200',
            'icon' => 'fas fa-exclamation-circle',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'text' => 'text-yellow-800',
            'border' => 'border-yellow-200',
            'icon' => 'fas fa-exclamation-triangle',
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-800',
            'border' => 'border-blue-200',
            'icon' => 'fas fa-info-circle',
        ],
    ];
    
    $style = $types[$type];
@endphp

<div x-data="{ show: true }" 
     x-show="show" 
     class="rounded-lg p-4 {{ $style['bg'] }} {{ $style['text'] }} border {{ $style['border'] }}">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $style['icon'] }}"></i>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm">
                {{ $slot }}
            </p>
        </div>
        @if($dismissible)
            <div class="ml-4 flex-shrink-0">
                <button @click="show = false" type="button" class="inline-flex text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
</div>