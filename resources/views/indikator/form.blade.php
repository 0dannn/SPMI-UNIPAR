<form action="{{ isset($indikator) ? route('standar.indikator.update', $indikator) : route('standar.indikator.store', $standar) }}" method="POST" class="p-6">
    @csrf
    @if(isset($indikator)) @method('PUT') @endif
    
    <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ isset($indikator) ? 'Edit Indikator' : 'Tambah Indikator Baru' }}</h2>
        <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Indikator / Pernyataan <span class="text-red-500">*</span></label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Tuliskan pernyataan standar/indikator mutunya disini...">{{ $indikator->description ?? '' }}</textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Target <span class="text-red-500">*</span></label>
            <input type="text" name="target" value="{{ $indikator->target ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Cth: 100%, 5 Jurnal Terakreditasi, dll">
            <p class="mt-1 text-xs text-gray-500">Target spesifik yang harus dicapai (kuantitatif atau kualitatif).</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Indikator <span class="text-red-500">*</span></label>
            <select name="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="IKU" {{ (isset($indikator) && $indikator->type == 'IKU') ? 'selected' : '' }}>IKU (Indikator Kinerja Utama)</option>
                <option value="IKT" {{ (isset($indikator) && $indikator->type == 'IKT') ? 'selected' : '' }}>IKT (Indikator Kinerja Tambahan)</option>
            </select>
        </div>
    </div>
    
    <div class="mt-6 flex justify-end space-x-3 pt-4 border-t border-gray-100">
        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 shadow-sm transition-colors">Simpan Indikator</button>
    </div>
</form>
