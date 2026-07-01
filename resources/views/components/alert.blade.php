@props(['type' => 'info', 'message' => null])

@php
    $types = [
        'success' => ['class' => 'bg-green-50 text-green-800 border-green-200', 'icon' => '<svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'],
        'error' => ['class' => 'bg-red-50 text-red-800 border-red-200', 'icon' => '<svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'],
        'warning' => ['class' => 'bg-yellow-50 text-yellow-800 border-yellow-200', 'icon' => '<svg class="w-5 h-5 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'],
        'info' => ['class' => 'bg-blue-50 text-blue-800 border-blue-200', 'icon' => '<svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'],
    ];
    $theme = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center p-4 mb-4 text-sm border rounded-lg shadow-sm {$theme['class']}"]) }} role="alert">
    {!! $theme['icon'] !!}
    <div>
        @if($message)
            <span class="font-medium">{{ $message }}</span>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
