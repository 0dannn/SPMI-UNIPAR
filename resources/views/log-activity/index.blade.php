<x-app-layout>
    <x-slot name="header">Audit Trail / Log Aktivitas Sistem (Admin)</x-slot>

    <x-card class="mb-6">
        <form action="{{ route('log-activity.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Filter Pengguna</label>
                <select name="user_id" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Pengguna --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Filter Modul</label>
                <select name="module" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Modul --</option>
                    @foreach($modules as $m)
                        <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>Modul: {{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal spesifik</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Pencarian Kata Kunci</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari IP, Aksi, atau Deskripsi..." class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex space-x-2 w-full lg:w-auto">
                <button type="submit" class="px-5 py-2 bg-gray-800 text-white rounded font-bold text-sm shadow hover:bg-gray-700 transition-colors">Terapkan Filter</button>
                <a href="{{ route('log-activity.exportCsv', request()->all()) }}" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-bold text-sm flex items-center shadow transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export CSV
                </a>
            </div>
        </form>
    </x-card>

    <x-card title="Tabel Rekaman Jejak Keamanan & Sistem">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase text-[10px] tracking-wider">Waktu Terjadi</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase text-[10px] tracking-wider">Aktor / User</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase text-[10px] tracking-wider">Jenis Aksi</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase text-[10px] tracking-wider">Modul Terkait</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase text-[10px] tracking-wider">Deskripsi Aktivitas</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase text-[10px] tracking-wider">Jejak Digital (IP & Agent)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($logs as $log)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 font-mono text-xs">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-900">{{ $log->user->name ?? 'SYSTEM/GUEST' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $c = strtolower($log->action);
                                $color = str_contains($c, 'create') || str_contains($c, 'login') ? 'green' : (str_contains($c, 'delete') ? 'red' : 'blue');
                            @endphp
                            <x-badge color="{{ $color }}">{{ strtoupper($log->action) }}</x-badge>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600 font-medium">{{ $log->module ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700 min-w-[250px]">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 max-w-[200px] truncate" title="{{ $log->user_agent }}">
                            <span class="inline-block font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 border border-gray-200">{{ $log->ip_address }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500 italic bg-gray-50">Data audit trail kosong / tidak ditemukan sesuai filter Anda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $logs->links() }}</div>
    </x-card>
</x-app-layout>
