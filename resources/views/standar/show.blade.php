<x-app-layout>
    <x-slot name="header">
        Detail Standar Mutu & Indikator
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('standar.index') }}" class="text-sm text-blue-600 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <x-card title="Informasi Standar">
                <div class="space-y-5">
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Kode Standar</span>
                        <span class="block mt-1 text-base text-gray-900 font-semibold">{{ $standar->code }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Standar</span>
                        <span class="block mt-1 text-sm text-gray-800">{{ $standar->title }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Periode</span>
                        <span class="inline-block mt-1 bg-gray-100 text-gray-800 text-xs px-2.5 py-1 rounded border border-gray-200">{{ $standar->periode->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Status</span>
                        <span class="block mt-1">
                            @if($standar->is_active)
                                <x-badge color="green">Aktif</x-badge>
                            @else
                                <x-badge color="red">Nonaktif</x-badge>
                            @endif
                        </span>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Deskripsi</span>
                        <p class="mt-1 text-sm text-gray-600 leading-relaxed">{{ $standar->description ?: 'Tidak ada deskripsi.' }}</p>
                    </div>
                </div>
            </x-card>
        </div>
        
        <div class="md:col-span-2">
            <x-card title="Daftar Indikator">
                <x-slot name="action">
                    <button x-data x-on:click="$dispatch('open-modal', 'form-indikator-create')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-md text-sm transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Indikator
                    </button>
                </x-slot>

                <div class="space-y-4 mt-2">
                    @forelse($standar->indikators as $ind)
                        <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm hover:shadow transition-shadow">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-base mb-2 leading-snug">{{ $ind->description }}</p>
                                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md border border-blue-200">
                                            Target: <span class="font-bold">{{ $ind->target }}</span>
                                        </span>
                                        @if($ind->type)
                                        <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md border border-gray-200">
                                            {{ $ind->type }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-2 shrink-0">
                                    <button x-data x-on:click="$dispatch('open-modal', 'form-indikator-edit-{{ $ind->id }}')" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-md transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('standar.indikator.destroy', $ind) }}" method="POST" onsubmit="return confirm('Hapus indikator ini permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal (Uses included blade) -->
                        <x-modal name="form-indikator-edit-{{ $ind->id }}" :show="false">
                            @include('indikator.form', ['indikator' => $ind])
                        </x-modal>

                    @empty
                        <div class="text-center py-10 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada indikator</h3>
                            <p class="mt-1 text-sm text-gray-500">Standar ini belum memiliki indikator pengukuran.</p>
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>

    <!-- Create Modal (Uses included blade) -->
    <x-modal name="form-indikator-create" :show="false">
        @include('indikator.form')
    </x-modal>
</x-app-layout>
