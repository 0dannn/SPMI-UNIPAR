<x-app-layout>
    <x-slot name="header">
        Manajemen Jadwal Audit (LPM)
    </x-slot>

    <x-card title="Daftar Plotting Jadwal Audit">
        <x-slot name="action">
            <a href="{{ route('jadwal-audit.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center text-sm shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Buat Jadwal Baru
            </a>
        </x-slot>
        
        <x-table :headers="['Unit', 'Periode', 'Auditor Ditugaskan', 'Tanggal Mulai', 'Selesai', 'Status']">
            @forelse($jadwals as $j)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $j->unit->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $j->periode->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-700 font-medium">
                    {{ $j->auditor->name ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $j->date_start->format('d M Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $j->date_end->format('d M Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($j->status == 'planned')
                        <x-badge color="gray">Terjadwal</x-badge>
                    @elseif($j->status == 'in_progress')
                        <x-badge color="yellow">Sedang Audit</x-badge>
                    @elseif($j->status == 'completed')
                        <x-badge color="green">Selesai</x-badge>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-center text-gray-500 bg-gray-50 italic">
                    Belum ada jadwal audit yang dibuat.
                </td>
            </tr>
            @endforelse
        </x-table>
        
        <div class="mt-4">
            {{ $jadwals->links() }}
        </div>
    </x-card>
</x-app-layout>
