@props(['title' => null])
<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 transition-shadow duration-200 hover:shadow-md']) }}>
    @if($title)
    <div class="px-6 py-4 border-b border-gray-100 bg-white/50 backdrop-blur-sm flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800 tracking-tight">{{ $title }}</h3>
        {{ $action ?? '' }}
    </div>
    @endif
    <div class="p-6 text-gray-900">
        {{ $slot }}
    </div>
    @if(isset($footer))
    <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
        {{ $footer }}
    </div>
    @endif
</div>
