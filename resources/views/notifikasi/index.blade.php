<x-app-layout>
    <x-slot name="header">Pusat Notifikasi</x-slot>

    <x-card class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <form action="{{ route('notifikasi.index') }}" method="GET" class="flex items-center space-x-2">
                <select name="type" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 font-medium text-gray-700" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Notifikasi --</option>
                    <option value="sistem" {{ request('type')=='sistem'?'selected':'' }}>Sistem</option>
                    <option value="temuan" {{ request('type')=='temuan'?'selected':'' }}>Temuan Audit</option>
                    <option value="deadline" {{ request('type')=='deadline'?'selected':'' }}>Tenggat Waktu</option>
                    <option value="verifikasi" {{ request('type')=='verifikasi'?'selected':'' }}>Verifikasi RTL</option>
                    <option value="info" {{ request('type')=='info'?'selected':'' }}>Informasi Umum</option>
                </select>
            </form>
            
            <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg font-bold shadow-sm transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </x-card>

    <div class="space-y-4">
        @forelse($notifikasis as $notif)
            @php
                $color = match($notif->type) {
                    'temuan' => 'red',
                    'deadline' => 'yellow',
                    'verifikasi' => 'green',
                    'sistem' => 'gray',
                    default => 'blue'
                };
            @endphp
            <div class="{{ $notif->is_read ? 'bg-white opacity-75' : 'bg-blue-50 border-l-4 border-blue-500' }} rounded-xl shadow-sm p-5 transition-all border border-gray-100 relative">
                @if(!$notif->is_read)
                    <span class="absolute top-4 right-4 flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </span>
                @endif
                <div class="flex justify-between items-start pr-8">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <x-badge color="{{ $color }}">{{ strtoupper($notif->type) }}</x-badge>
                            <span class="text-xs text-gray-500 font-mono">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 leading-snug">{{ $notif->title }}</h3>
                        <p class="text-sm text-gray-700 mt-2 leading-relaxed">{{ $notif->message }}</p>
                    </div>
                    @if(!$notif->is_read)
                        <form action="{{ route('notifikasi.markRead', $notif->id) }}" method="POST" class="ml-4 shrink-0 absolute right-4 bottom-4">
                            @csrf
                            <button type="submit" class="text-blue-600 bg-blue-100 hover:bg-blue-200 px-3 py-1 text-xs font-bold rounded-lg transition-colors" title="Tandai Dibaca">
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Kotak Masuk Bersih!</h3>
                <p class="text-gray-500 text-sm">Anda telah membaca semua notifikasi atau belum ada pemberitahuan baru.</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-8">{{ $notifikasis->links() }}</div>
</x-app-layout>
