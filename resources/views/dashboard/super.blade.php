<x-app-layout>
    <x-slot name="header">
        Super Admin Portal
    </x-slot>

    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-xl shadow-lg p-6 sm:p-10 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-extrabold tracking-tight mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100 max-w-2xl text-lg">Anda login sebagai <span class="font-bold text-white">Super User</span> dengan akses penuh ke seluruh modul sistem SPMI. Silakan pilih portal dashboard di bawah ini untuk mengelola aplikasi.</p>
            </div>
            <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 7.5l-10-5v10.5l10 5 10-5V4.5l-10 5z"></path></svg>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-8">
            <!-- Admin -->
            <a href="{{ route('dashboard.admin') }}" class="group block bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div class="h-2 bg-blue-500 w-full"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Dashboard Admin</h3>
                    <p class="text-gray-500 text-sm">Kelola pengguna, unit kerja, periode SPMI, dan log aktivitas sistem secara menyeluruh.</p>
                </div>
            </a>

            <!-- LPM -->
            <a href="{{ route('dashboard.lpm') }}" class="group block bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div class="h-2 bg-indigo-500 w-full"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <svg class="w-6 h-6 text-gray-300 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Dashboard LPM</h3>
                    <p class="text-gray-500 text-sm">Pantau penyusunan standar mutu, jadwal audit, dan persentase kepatuhan seluruh unit.</p>
                </div>
            </a>

            <!-- Pimpinan -->
            <a href="{{ route('dashboard.pimpinan') }}" class="group block bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div class="h-2 bg-purple-500 w-full"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <svg class="w-6 h-6 text-gray-300 group-hover:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Dashboard Pimpinan</h3>
                    <p class="text-gray-500 text-sm">Akses laporan eksekutif, grafik performa lintas periode, dan statistik evaluasi komprehensif.</p>
                </div>
            </a>

            <!-- Auditor -->
            <a href="{{ route('dashboard.auditor') }}" class="group block bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div class="h-2 bg-amber-500 w-full"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <svg class="w-6 h-6 text-gray-300 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Dashboard Auditor</h3>
                    <p class="text-gray-500 text-sm">Cek jadwal tugas audit Anda, periksa dokumen, dan catat temuan AMI secara objektif.</p>
                </div>
            </a>

            <!-- Auditee -->
            <a href="{{ route('dashboard.auditee') }}" class="group block bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div class="h-2 bg-green-500 w-full"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-50 text-green-600 rounded-lg group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <svg class="w-6 h-6 text-gray-300 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Dashboard Auditee</h3>
                    <p class="text-gray-500 text-sm">Lakukan evaluasi diri, unggah bukti fisik, dan tanggapi temuan audit untuk unit Anda.</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
