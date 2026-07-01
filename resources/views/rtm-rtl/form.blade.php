<form action="{{ $rtl ? route('rtm-rtl.update', $rtl) : route('rtm-rtl.store', $auditAmi) }}" method="POST" class="p-6">
    @csrf
    @if($rtl) @method('PUT') @endif
    
    <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
        <h2 class="text-lg font-bold text-gray-900">{{ $rtl ? 'Update Status Progres RTL' : 'Rumuskan Tindak Lanjut (RTL)' }}</h2>
        <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
    </div>
    
    <div class="mb-5 bg-red-50 border-l-4 border-red-400 p-3 text-sm text-red-900 rounded-r shadow-sm">
        <div class="font-bold text-xs uppercase tracking-wide text-red-700 mb-1">Catatan Temuan {{ $auditAmi->finding_type }} dari Auditor:</div>
        <p class="leading-relaxed">"{{ $auditAmi->description }}"</p>
    </div>

    <div class="mb-5">
        <label class="block text-sm font-bold text-gray-700 mb-2">Langkah / Rencana Perbaikan <span class="text-red-500">*</span></label>
        <textarea name="description" rows="4" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Tuliskan secara spesifik apa yang akan Anda perbaiki dan strategi penanganannya...">{{ $rtl->description ?? '' }}</textarea>
    </div>
    
    <div class="grid grid-cols-2 gap-5 mb-5">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Status Pengerjaan <span class="text-red-500">*</span></label>
            <select name="status" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="Direncanakan" {{ ($rtl->status ?? '') == 'Direncanakan' ? 'selected' : '' }}>📋 Direncanakan (Baru)</option>
                <option value="Berjalan" {{ ($rtl->status ?? '') == 'Berjalan' ? 'selected' : '' }}>⏳ Berjalan (Progres)</option>
                <option value="Selesai" {{ ($rtl->status ?? '') == 'Selesai' ? 'selected' : '' }}>✅ Selesai (Tuntas)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Estimasi Target Selesai <span class="text-red-500">*</span></label>
            <input type="date" name="target_date" value="{{ $rtl ? $rtl->target_date->format('Y-m-d') : '' }}" min="{{ date('Y-m-d') }}" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>
    </div>
    
    <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-gray-100">
        <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 border border-gray-300 bg-white rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-blue-700 transition-colors">{{ $rtl ? 'Simpan Perubahan RTL' : 'Simpan Rencana RTL' }}</button>
    </div>
</form>
