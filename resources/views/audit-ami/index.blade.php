<x-app-layout>
    <x-slot name="header">
        Daftar Tugas Penilaian AMI (Auditor)
    </x-slot>

    <x-card title="Jadwal Penugasan Saya">
        <x-table :headers="['Unit', 'Periode', 'Tanggal Mulai', 'Tenggat Waktu', 'Status', 'Aksi']">
            @forelse($jadwals as $j)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $j->unit->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $j->periode->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $j->date_start->format('d M Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $j->date_end->format('d M Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($j->status == 'planned')
                        <x-badge color="gray">Belum Dimulai</x-badge>
                    @elseif($j->status == 'in_progress')
                        <x-badge color="yellow">Sedang Audit</x-badge>
                    @elseif($j->status == 'completed')
                        <x-badge color="green">Selesai (Submitted)</x-badge>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('audit-ami.show', $j) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition-colors shadow-sm text-xs">
                        Lakukan Penilaian
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-center text-gray-500 bg-gray-50 italic">
                    Belum ada penugasan jadwal audit untuk Anda.
                </td>
            </tr>
            @endforelse
        </x-table>
        
        <div class="mt-4">
            {{ $jadwals->links() }}
        </div>
    </x-card>
</x-app-layout>
