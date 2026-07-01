<x-app-layout>
    <x-slot name="header">
        {{ $standar->exists ? 'Edit Standar Mutu' : 'Tambah Standar Mutu Baru' }}
    </x-slot>

    <x-card title="Formulir Standar Mutu">
        <form action="{{ $standar->exists ? route('standar.update', $standar) : route('standar.store') }}" method="POST">
            @csrf
            @if($standar->exists) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <select name="periode_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ old('periode_id', $standar->periode_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} {{ $p->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('periode_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Standar</label>
                    <input type="text" name="code" value="{{ old('code', $standar->code) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required placeholder="Cth: STD-01">
                    @error('code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Standar</label>
                <input type="text" name="title" value="{{ old('title', $standar->title) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required placeholder="Cth: Standar Kompetensi Lulusan">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Ruang Lingkup</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $standar->description) }}</textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                <label class="flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('is_active', $standar->exists ? $standar->is_active : true) ? 'checked' : '' }}>
                    <div class="ml-3">
                        <span class="block text-sm font-medium text-gray-900">Aktifkan Standar</span>
                        <span class="block text-xs text-gray-500">Standar yang aktif akan dievaluasi pada siklus AMI berjalan.</span>
                    </div>
                </label>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('standar.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 shadow-sm transition-colors">Simpan Standar Mutu</button>
            </div>
        </form>
    </x-card>
</x-app-layout>
