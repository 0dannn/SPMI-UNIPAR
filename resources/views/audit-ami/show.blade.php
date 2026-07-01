<x-app-layout>
    <div class="flex items-center justify-between mb-2 px-2">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Penilaian AMI: {{ $jadwal->unit->name }}</h1>
            <p class="text-sm text-gray-500">Tenggat: {{ $jadwal->date_end->format('d M Y') }} | Status: <x-badge color="{{ $jadwal->status=='completed'?'green':'yellow' }}">{{ $jadwal->status }}</x-badge></p>
        </div>
        <div>
            <a href="{{ route('audit-ami.index') }}" class="text-sm text-blue-600 hover:underline mr-4">Kembali</a>
            @if($jadwal->status != 'completed')
                <form action="{{ route('audit-ami.submit', $jadwal) }}" method="POST" class="inline" onsubmit="return confirm('Kirim laporan final ke Auditee? Pastikan semua indikator telah dinilai.');">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg font-bold shadow-md transition-colors">
                        Submit Laporan Final
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Split Screen Layout -->
    <div class="flex flex-col lg:flex-row gap-4 h-[calc(100vh-12rem)] w-full" x-data="{ currentViewerUrl: '', showViewer: false }">
        
        <!-- Kiri: Viewer Bukti Fisik -->
        <div class="w-full lg:w-5/12 bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col overflow-hidden">
            <div class="bg-gray-100 p-3 border-b border-gray-200 font-semibold text-gray-700 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Dokumen Viewer
            </div>
            
            <div class="flex-1 bg-gray-50 flex items-center justify-center p-4 relative" id="viewer-container">
                <template x-if="!showViewer">
                    <div class="text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p>Klik tombol "Lihat File" pada salah satu bukti fisik untuk membukanya di sini.</p>
                    </div>
                </template>
                <template x-if="showViewer">
                    <iframe :src="currentViewerUrl" class="w-full h-full border-0 rounded bg-white shadow-inner"></iframe>
                </template>
            </div>
        </div>

        <!-- Kanan: Form Penilaian per Indikator -->
        <div class="w-full lg:w-7/12 bg-white rounded-lg shadow-sm border border-gray-200 overflow-y-auto p-4 space-y-6">
            @foreach($pengukurans as $pengukuran)
                @php
                    $indikator = $pengukuran->indikator;
                    $audit = $pengukuran->auditAmis->first();
                @endphp
                <div class="border {{ $audit ? 'border-green-200 bg-green-50/20' : 'border-gray-200 bg-gray-50' }} rounded-xl p-5 relative transition-colors">
                    @if($audit)
                        <div class="absolute top-0 right-0 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-bl-lg rounded-tr-xl">SUDAH DINILAI</div>
                    @endif
                    
                    <h3 class="font-bold text-gray-900 mb-2 leading-snug">{{ $indikator->standar->code }} - {{ $indikator->description }}</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm bg-white p-3 rounded border border-gray-100 shadow-sm">
                        <div>
                            <span class="block text-gray-500 text-xs">Target LPM:</span>
                            <span class="font-mono font-semibold text-blue-800">{{ $indikator->target }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs">Capaian Riil Unit (Self Score):</span>
                            <span class="font-medium text-gray-800">{{ $pengukuran->self_score ?? 'Tidak diisi' }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <span class="block text-gray-500 text-xs mb-2 uppercase tracking-wider font-bold">Bukti Fisik Terlampir:</span>
                        <div class="flex flex-wrap gap-2">
                            @forelse($pengukuran->buktiFisiks as $file)
                                <button type="button" @click="currentViewerUrl = '{{ route('file.show', $file->id) }}'; showViewer = true;" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-md hover:bg-blue-100 text-xs transition-colors font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ Str::limit($file->file_name, 20) }}
                                </button>
                            @empty
                                <span class="text-xs text-red-500 italic">Tidak ada bukti fisik!</span>
                            @endforelse
                        </div>
                    </div>
                    
                    <form action="{{ route('audit-ami.store', $jadwal) }}" method="POST" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        @csrf
                        <input type="hidden" name="pengukuran_id" value="{{ $pengukuran->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Skor Auditor (1-4) <span class="text-red-500">*</span></label>
                                <select name="auditor_score" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 {{ $jadwal->status=='completed'?'bg-gray-100':'' }}" required {{ $jadwal->status=='completed'?'disabled':'' }}>
                                    <option value="">-- Pilih Skor --</option>
                                    @for($i=1; $i<=4; $i++)
                                        <option value="{{ $i }}" {{ ($audit->auditor_score ?? '') == $i ? 'selected' : '' }}>Skor {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Temuan <span class="text-red-500">*</span></label>
                                <div class="flex space-x-4 mt-1">
                                    <label class="inline-flex items-center text-sm cursor-pointer">
                                        <input type="radio" name="finding_type" value="Sesuai" class="text-green-600 focus:ring-green-500" required {{ ($audit->finding_type ?? '') == 'Sesuai' ? 'checked' : '' }} {{ $jadwal->status=='completed'?'disabled':'' }}>
                                        <span class="ml-2 font-medium text-gray-700">Sesuai</span>
                                    </label>
                                    <label class="inline-flex items-center text-sm cursor-pointer">
                                        <input type="radio" name="finding_type" value="OB" class="text-yellow-600 focus:ring-yellow-500" {{ ($audit->finding_type ?? '') == 'OB' ? 'checked' : '' }} {{ $jadwal->status=='completed'?'disabled':'' }}>
                                        <span class="ml-2 font-medium text-gray-700">OB</span>
                                    </label>
                                    <label class="inline-flex items-center text-sm cursor-pointer">
                                        <input type="radio" name="finding_type" value="KTS" class="text-red-600 focus:ring-red-500" {{ ($audit->finding_type ?? '') == 'KTS' ? 'checked' : '' }} {{ $jadwal->status=='completed'?'disabled':'' }}>
                                        <span class="ml-2 font-medium text-gray-700">KTS</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Temuan / Catatan Auditor <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="3" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 {{ $jadwal->status=='completed'?'bg-gray-100':'' }}" placeholder="Tulis rincian temuan, saran perbaikan, atau alasan KTS/OB di sini..." required {{ $jadwal->status=='completed'?'disabled':'' }}>{{ $audit->description ?? '' }}</textarea>
                        </div>
                        
                        @if($jadwal->status != 'completed')
                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 text-sm shadow-sm transition-colors">
                                    {{ $audit ? 'Update Penilaian' : 'Simpan Penilaian' }}
                                </button>
                            </div>
                        @endif
                    </form>
                    
                    <!-- Fitur Banding (Jika KTS/OB dan Audit Selesai) -->
                    @if($audit && in_array($audit->finding_type, ['KTS', 'OB']) && $jadwal->status == 'completed' && auth()->user()->hasRole('Auditee'))
                        <div class="mt-4 border border-red-200 bg-red-50 p-4 rounded-lg">
                            <h4 class="text-sm font-bold text-red-800 mb-2">Ajukan Banding atas Temuan {{ $audit->finding_type }}</h4>
                            <form action="{{ route('banding.store', $audit->id) }}" method="POST">
                                @csrf
                                <textarea name="comment" rows="2" class="w-full text-sm border-red-300 rounded focus:ring-red-500 focus:border-red-500 mb-2" placeholder="Tulis alasan banding..." required></textarea>
                                <button type="submit" class="px-4 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-bold">Kirim Banding ke LPM</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
    </div>
</x-app-layout>
