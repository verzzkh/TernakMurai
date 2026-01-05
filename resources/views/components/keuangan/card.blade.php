@props(['title', 'value', 'color' => 'blue', 'icon' => 'cash'])

@php
    $icons = [
        'cash' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'cart' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        'arrow' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'users' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 0 014 0zM7 10a2 2 0 11-4 0 2 0 014 0z',
    ];
@endphp

<div class="flex items-center justify-between p-4 bg-white rounded-md shadow-sm dark:bg-darker">
    <div>
        <h6 class="text-xs font-medium tracking-wide text-gray-500 uppercase dark:text-primary-light">
            {{ $title }}
        </h6>

        <div class="mt-1 text-xl font-semibold text-{{ $color }}-600 dark:text-{{ $color }}-400">
            {{ $value }}
        </div>
    </div>

    <div>
        <svg class="w-12 h-12 text-gray-300 dark:text-primary-dark" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="{{ $icons[$icon] ?? $icons['cash'] }}" />
        </svg>
    </div>
</div>
