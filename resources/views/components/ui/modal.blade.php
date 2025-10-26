<x-modal>
@props([
    'title' => '',
    'maxWidth' => '2xl',
    'show' => false,
])

<div x-data="{ show: false }"
     x-show="show"
     x-on:open-modal.window="$event.detail === '{{ $title }}' ? show = true : null"
     x-on:close-modal.window="$event.detail === '{{ $title }}' ? show = false : null"
     x-on:keydown.escape.window="show = false"
     class="relative z-50"
     style="display: none;">
    
    <div x-show="show" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-{{ $maxWidth }}"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                @if($title)
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            {{ $title }}
                        </h3>
                    </div>
                @endif

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    {{ $slot }}
                </div>

                @if(isset($footer))
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>