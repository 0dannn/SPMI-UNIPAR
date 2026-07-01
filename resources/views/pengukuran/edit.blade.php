<x-app-layout>
    <x-slot name="header">
        Isi Capaian Riil Evaluasi Diri
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('pengukuran.index') }}" class="text-sm text-blue-600 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Indikator
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Indikator -->
        <div class="lg:col-span-1 space-y-6">
            <x-card title="Informasi Indikator Standar">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Standar</span>
                        <span class="block mt-1 text-sm text-gray-900 font-semibold">{{ $indikator->standar->code }} - {{ $indikator->standar->title }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Pernyataan Indikator</span>
                        <span class="block mt-1 text-base text-gray-900 leading-snug">{{ $indikator->description }}</span>
                    </div>
                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <span class="block text-xs font-bold text-blue-400 uppercase tracking-wider mb-1">Target yang harus dicapai</span>
                        <span class="block text-lg text-blue-800 font-mono font-bold">{{ $indikator->target }}</span>
                    </div>
                </div>
            </x-card>
            
            <x-card title="Bukti Fisik Tersimpan">
                <div class="space-y-3">
                    @forelse($pengukuran->buktiFisiks as $bukti)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                <div class="bg-blue-100 p-2 rounded shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="truncate">
                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $bukti->file_name }}">{{ $bukti->file_name }}</p>
                                    <p class="text-xs text-gray-500">{{ round($bukti->file_size / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <form action="{{ route('pengukuran.deleteFile', $bukti) }}" method="POST" onsubmit="return confirm('Hapus file ini?');" class="shrink-0 ml-2">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-50 rounded hover:bg-red-100 transition-colors" title="Hapus File">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-4 text-sm text-gray-500 border-2 border-dashed border-gray-200 rounded-lg">
                            Belum ada file lampiran.
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>

        <!-- Form Input Capaian -->
        <div class="lg:col-span-2">
            <x-card title="Input Form Capaian">
                <form action="{{ route('pengukuran.update', $pengukuran) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Capaian Riil (Self Score) <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-3 text-xs text-blue-800 rounded-r">
                            Jelaskan secara spesifik nilai capaian riil unit Anda saat ini terhadap target indikator yang diminta.
                        </div>
                        <textarea name="self_score" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Tuliskan capaian riil secara deskriptif/kuantitatif sesuai pencapaian unit...">{{ old('self_score', $pengukuran->self_score) }}</textarea>
                        @error('self_score') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-6 border border-gray-200 rounded-lg p-5 bg-gray-50">
                        <label class="block text-sm font-medium text-gray-900 mb-1">Upload Bukti Fisik Baru</label>
                        <p class="text-xs text-gray-500 mb-3">Jika perlu, lampirkan dokumen pendukung. Format: PDF, DOCX, XLSX, JPG, PNG (Maks 10MB). File yang sudah ada tidak akan hilang.</p>
                        
                        <input type="file" name="bukti_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-md bg-white">
                        @error('bukti_file') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="flex items-center justify-between pt-5 border-t border-gray-100">
                        <span class="text-sm text-gray-500">Status saat ini: <x-badge color="{{ $pengukuran->status == 'draft' ? 'yellow' : 'gray' }}">{{ strtoupper(str_replace('_', ' ', $pengukuran->status)) }}</x-badge></span>
                        <div class="space-x-3">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-md text-sm font-bold hover:bg-blue-700 shadow-sm transition-colors flex items-center inline-flex">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan sebagai Draft
                            </button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
