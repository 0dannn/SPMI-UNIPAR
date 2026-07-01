<x-app-layout>
    <x-slot name="header">Rencana Tindak Lanjut (P3)</x-slot>

    <x-card class="mb-6">
        <form action="{{ route('rtm-rtl.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            @if(!auth()->user()->hasRole('Auditee'))
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Filter Unit</label>
                <select name="unit_id" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Filter Status RTL</label>
                <select name="status" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Status RTL --</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Ada RTL (Merah)</option>
                    <option value="Direncanakan" {{ request('status') == 'Direncanakan' ? 'selected' : '' }}>Direncanakan</option>
                    <option value="Berjalan" {{ request('status') == 'Berjalan' ? 'selected' : '' }}>Berjalan (Kuning)</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai (Hijau)</option>
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-gray-800 text-white text-sm rounded font-medium shadow-sm hover:bg-gray-700 transition-colors">Terapkan Filter</button>
        </form>
    </x-card>

    <x-card title="Daftar Temuan (KTS & OB) yang Butuh Perbaikan">
        <x-table :headers="['Unit', 'Kode Indikator', 'Temuan Auditor', 'Kategori', 'Status Progres RTL', 'Target Selesai', 'Aksi']">
            @forelse($auditAmis as $ami)
                @php $rtl = $ami->rtmRtl; @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $ami->jadwal->unit->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-700 font-bold font-mono">{{ $ami->pengukuran->indikator->standar->code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 min-w-[200px]">{{ Str::limit($ami->description, 60) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <x-badge color="{{ $ami->finding_type == 'KTS' ? 'red' : 'yellow' }}">{{ $ami->finding_type }}</x-badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if(!$rtl)
                            <x-badge color="red">Belum Ada RTL</x-badge>
                        @elseif($rtl->status == 'Selesai')
                            <x-badge color="green">Selesai</x-badge>
                        @elseif($rtl->status == 'Berjalan')
                            <x-badge color="yellow">Berjalan</x-badge>
                        @else
                            <x-badge color="gray">{{ $rtl->status }}</x-badge>
                        @endif
                        
                        @if($rtl && $rtl->auditor_validation)
                            <svg class="inline w-4 h-4 text-green-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Diverifikasi"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                        {{ $rtl ? $rtl->target_date->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm flex space-x-2">
                        <a href="{{ route('rtm-rtl.show', $ami) }}" class="px-3 py-1 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 font-medium transition-colors">Detail/Chat</a>
                        @if(auth()->user()->hasRole('Auditee') && !$rtl)
                            <button x-data x-on:click="$dispatch('open-modal', 'create-rtl-{{ $ami->id }}')" class="px-3 py-1 bg-green-50 text-green-700 rounded hover:bg-green-100 font-medium transition-colors border border-green-200">+ Buat RTL</button>
                        @endif
                        @if(auth()->user()->hasRole('Auditee') && $rtl && !$rtl->auditor_validation)
                            <button x-data x-on:click="$dispatch('open-modal', 'edit-rtl-{{ $rtl->id }}')" class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded hover:bg-yellow-100 font-medium transition-colors">Update RTL</button>
                        @endif
                    </td>
                </tr>

                @if(auth()->user()->hasRole('Auditee'))
                    @if(!$rtl)
                    <x-modal name="create-rtl-{{ $ami->id }}" :show="false">
                        @include('rtm-rtl.form', ['auditAmi' => $ami, 'rtl' => null])
                    </x-modal>
                    @else
                    <x-modal name="edit-rtl-{{ $rtl->id }}" :show="false">
                        @include('rtm-rtl.form', ['auditAmi' => $ami, 'rtl' => $rtl])
                    </x-modal>
                    @endif
                @endif
            @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500 bg-gray-50 italic">Hore! Tidak ada temuan yang memerlukan Tindak Lanjut pada kriteria ini.</td></tr>
            @endforelse
        </x-table>
        <div class="mt-4">{{ $auditAmis->links() }}</div>
    </x-card>
</x-app-layout>
