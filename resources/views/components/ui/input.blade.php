<x-input>
@props([
    'type' => 'text',
    'label' => '',
    'error' => '',
    'fullWidth' => false,
])

<div class="{{ $fullWidth ? 'w-full' : '' }}">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        {{ $attributes->merge([
            'class' => 'block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm' . 
                      ($fullWidth ? ' w-full' : '') .
                      ($error ? ' border-red-300' : '')
        ]) }}
    >
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>