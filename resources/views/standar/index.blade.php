<x-app-layout>
    <x-slot name="header">
        Standar Mutu (P1)
    </x-slot>

    <x-card title="Daftar Standar Mutu">
        <x-slot name="action">
            <a href="{{ route('standar.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center text-sm transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Standar
            </a>
        </x-slot>
        
        @if($activePeriode)
            <div class="mb-5">
                <x-badge color="blue">Periode Aktif: {{ $activePeriode->name }}</x-badge>
            </div>
        @else
            <x-alert type="warning" message="Belum ada periode aktif. Silakan set periode di menu Pengaturan terlebih dahulu." />
        @endif

        <x-table :headers="['Kode', 'Nama Standar', 'Periode', 'Status', 'Indikator', 'Aksi']">
            @forelse($standars as $s)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $s->code }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $s->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $s->periode->name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($s->is_active)
                        <x-badge color="green">Aktif</x-badge>
                    @else
                        <x-badge color="red">Nonaktif</x-badge>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                    {{ $s->indikators()->count() }} Indikator
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                    <a href="{{ route('standar.show', $s) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-2 py-1 rounded">Detail</a>
                    <a href="{{ route('standar.edit', $s) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('standar.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus standar ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-center text-gray-500 italic bg-gray-50/50">
                    Data standar mutu belum tersedia di periode ini.
                </td>
            </tr>
            @endforelse
        </x-table>
        
        <div class="mt-5">
            {{ $standars->links() }}
        </div>
    </x-card>
</x-app-layout>
