<x-app-layout>
    <x-slot name="header">Dashboard Administrator</x-slot>

    @if(!$activePeriode)
        <x-alert type="error" message="Belum ada periode akademik yang aktif. Silakan aktifkan periode di menu Master Data." />
    @else
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Tahun Akademik: {{ $activePeriode->name }}</h2>
            <x-badge color="green">Sistem Berjalan Baik</x-badge>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 font-bold uppercase tracking-wider">Total Pengguna</span>
                    <span class="block text-2xl font-black text-gray-900">{{ $usersCount }}</span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 font-bold uppercase tracking-wider">Total Unit Auditee</span>
                    <span class="block text-2xl font-black text-gray-900">{{ $unitsCount }}</span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 font-bold uppercase tracking-wider">Standar Mutu</span>
                    <span class="block text-2xl font-black text-gray-900">{{ $stats['standarCount'] }} <span class="text-sm font-normal text-gray-400">/ {{ $stats['indikatorCount'] }} Indikator</span></span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 font-bold uppercase tracking-wider">Log Aktivitas</span>
                    <span class="block text-2xl font-black text-gray-900">{{ number_format($logCount) }}</span>
                </div>
            </div>
        </div>
        
        <x-card title="Quick Actions">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('standar.index') }}" class="py-3 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-center transition-colors font-semibold text-gray-700 text-sm">
                    Manajemen Standar Mutu
                </a>
                <a href="{{ route('jadwal-audit.index') }}" class="py-3 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-center transition-colors font-semibold text-gray-700 text-sm">
                    Buat Jadwal Audit
                </a>
                <a href="{{ route('log-activity.index') }}" class="py-3 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-center transition-colors font-semibold text-gray-700 text-sm">
                    Pantau Audit Trail
                </a>
                <a href="{{ route('profile.edit') }}" class="py-3 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-center transition-colors font-semibold text-gray-700 text-sm">
                    Pengaturan Profil
                </a>
            </div>
        </x-card>
    @endif
</x-app-layout>
