<x-app-layout>
    <x-slot name="header">Dashboard Auditor Lapangan</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl shadow-lg p-6 text-white flex items-center justify-between">
            <div>
                <span class="block text-sm font-bold uppercase tracking-wider mb-1 text-yellow-100">Tugas Audit Menunggu</span>
                <span class="text-5xl font-black">{{ $pendingTasks }}</span>
                <span class="block mt-2 text-sm text-yellow-50">Unit belum dievaluasi final.</span>
            </div>
            <svg class="w-20 h-20 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white flex items-center justify-between">
            <div>
                <span class="block text-sm font-bold uppercase tracking-wider mb-1 text-green-100">Audit Diselesaikan</span>
                <span class="text-5xl font-black">{{ $completedTasks }}</span>
                <span class="block mt-2 text-sm text-green-50">Laporan disubmit ke LPM.</span>
            </div>
            <svg class="w-20 h-20 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <x-card title="Daftar Antrean Audit Anda (Terbaru)">
        <x-table :headers="['Unit Auditee', 'Tenggat Waktu', 'Status', 'Aksi']">
            @forelse($jadwals->take(5) as $j)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $j->unit->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                        {{ $j->date_end->format('d M Y') }}
                        @if($j->date_end < now() && $j->status != 'completed')
                            <span class="ml-2 text-xs text-red-500 font-bold">(Terlambat)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($j->status == 'completed')
                            <x-badge color="green">Selesai</x-badge>
                        @elseif($j->status == 'in_progress')
                            <x-badge color="yellow">Sedang Dinilai</x-badge>
                        @else
                            <x-badge color="gray">Belum Dimulai</x-badge>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('audit-ami.show', $j->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold shadow-sm text-xs">Akses Ruang Audit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 bg-gray-50 italic">Tidak ada penugasan audit untuk saat ini.</td></tr>
            @endforelse
        </x-table>
        @if($jadwals->count() > 5)
            <div class="mt-4 text-right">
                <a href="{{ route('audit-ami.index') }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua Tugas &rarr;</a>
            </div>
        @endif
    </x-card>
</x-app-layout>
