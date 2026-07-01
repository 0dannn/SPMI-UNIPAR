<x-app-layout>
    <x-slot name="header">
        Evaluasi Diri Auditee (P2)
    </x-slot>

    @if(!$activePeriode)
        <x-alert type="error" message="Tidak ada Periode Akademik yang aktif. Silakan hubungi admin/LPM." />
    @else
        <!-- Filter Card -->
        <x-card class="mb-6">
            <form action="{{ route('pengukuran.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filter Standar Mutu</label>
                    <select name="standar_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Tampilkan Semua Standar --</option>
                        @foreach($standars as $std)
                            <option value="{{ $std->id }}" {{ $standarId == $std->id ? 'selected' : '' }}>
                                {{ $std->code }} - {{ $std->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium text-sm transition-colors shadow-sm">
                    Saring
                </button>
            </form>
        </x-card>

        <!-- Progress Overview -->
        <x-card class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Progress Pengisian Evaluasi Diri</h3>
                            <p class="text-sm text-gray-500">Periode: <span class="font-semibold">{{ $activePeriode->name }}</span> | Unit: <span class="font-semibold">{{ auth()->user()->unit->name }}</span></p>
                        </div>
                        <span class="text-xl font-bold text-blue-600">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Terisi: {{ $filled }} dari {{ $total }} Indikator</p>
                </div>

                @if(!$allSubmitted)
                <div class="md:border-l md:border-gray-200 md:pl-6">
                    <form action="{{ route('pengukuran.submitFinal') }}" method="POST" id="submit-final-form">
                        @csrf
                        <button type="button" onclick="if(confirm('Apakah Anda yakin ingin Submit Final? Setelah submit, form akan terkunci dan tidak bisa diedit kembali.')) document.getElementById('submit-final-form').submit();" class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-sm transition-colors shadow-md flex items-center justify-center {{ $globalProgress < 100 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $globalProgress < 100 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Submit Final ke LPM
                        </button>
                    </form>
                    @if($globalProgress < 100)
                        <p class="text-xs text-red-500 mt-2 text-center">Isi semua indikator ({{ $globalFilled }}/{{ $globalTotal }}) untuk bisa submit final.</p>
                    @endif
                </div>
                @else
                <div class="md:border-l md:border-gray-200 md:pl-6 flex flex-col items-center">
                    <div class="bg-green-100 text-green-800 p-3 rounded-full mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold text-green-700">Terkunci (Telah Disubmit)</span>
                </div>
                @endif
            </div>
        </x-card>

        <x-card title="Daftar Target Indikator" class="mb-10">
            <x-table :headers="['Kode', 'Teks Target / Pernyataan', 'Target', 'Capaian Riil (Self Score)', 'Bukti Fisik', 'Status', 'Aksi']">
                @forelse($indikators as $ind)
                @php
                    $pengukuran = $ind->pengukurans->first();
                    $status = $pengukuran ? $pengukuran->status : 'belum_isi';
                @endphp
                <tr class="hover:bg-gray-50 {{ $status == 'belum_isi' ? 'bg-red-50/20' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $ind->standar->code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 min-w-[250px]">{{ $ind->description }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono bg-gray-100/50">{{ $ind->target }}</td>
                    
                    <td class="px-6 py-4 text-sm font-medium {{ $pengukuran && $pengukuran->self_score ? 'text-blue-700' : 'text-gray-400 italic' }}">
                        {{ $pengukuran && $pengukuran->self_score ? Str::limit($pengukuran->self_score, 50) : 'Belum diisi' }}
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($pengukuran && $pengukuran->buktiFisiks->count() > 0)
                            <span class="inline-flex items-center text-green-600 font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                {{ $pengukuran->buktiFisiks->count() }} File
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($status == 'belum_isi')
                            <x-badge color="gray">Belum Isi</x-badge>
                        @elseif($status == 'draft')
                            <x-badge color="yellow">Draft</x-badge>
                        @elseif($status == 'submitted')
                            <x-badge color="blue">Submitted</x-badge>
                        @elseif($status == 'verified')
                            <x-badge color="green">Terverifikasi</x-badge>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if(in_array($status, ['submitted', 'verified']))
                            <button class="text-gray-400 cursor-not-allowed px-2 py-1 bg-gray-100 rounded" title="Terkunci">Terkunci</button>
                        @else
                            <a href="{{ route('pengukuran.edit', $ind) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded transition-colors shadow-sm text-xs flex items-center inline-flex">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Isi Capaian
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 bg-gray-50">Tidak ada indikator yang ditemukan.</td>
                </tr>
                @endforelse
            </x-table>
        </x-card>
    @endif
</x-app-layout>
