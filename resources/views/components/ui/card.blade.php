<x-card>
@props([
    'padding' => 'normal', // none, normal, large
    'rounded' => 'md', // none, sm, md, lg
    'shadow' => 'md', // none, sm, md, lg
])

@php
    $baseClasses = 'bg-white border border-gray-200';
    
    $paddings = [
        'none' => '',
        'normal' => 'p-4',
        'large' => 'p-6',
    ];
    
    $roundings = [
        'none' => '',
        'sm' => 'rounded-sm',
        'md' => 'rounded-lg',
        'lg' => 'rounded-xl',
    ];
    
    $shadows = [
        'none' => '',
        'sm' => 'shadow-sm',
        'md' => 'shadow',
        'lg' => 'shadow-lg',
    ];
    
    $classes = $baseClasses . ' ' . 
               $paddings[$padding] . ' ' . 
               $roundings[$rounded] . ' ' . 
               $shadows[$shadow];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>