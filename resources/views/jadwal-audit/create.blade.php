<x-app-layout>
    <x-slot name="header">
        Buat Jadwal Audit Baru
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('jadwal-audit.index') }}" class="text-sm text-blue-600 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Kalender Jadwal
        </a>
    </div>

    <x-card title="Plotting Auditor ke Unit">
        <form action="{{ route('jadwal-audit.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode Akademik Aktif <span class="text-red-500">*</span></label>
                    <select name="periode_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('periode_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Unit (Auditee) <span class="text-red-500">*</span></label>
                    <select name="unit_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->type }})</option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Auditor Ditugaskan <span class="text-red-500">*</span></label>
                <select name="auditor_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">-- Assign Auditor --</option>
                    @foreach($auditors as $a)
                        <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->email }})</option>
                    @endforeach
                </select>
                @error('auditor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Audit <span class="text-red-500">*</span></label>
                    <input type="date" name="date_start" value="{{ old('date_start') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    @error('date_start') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Audit <span class="text-red-500">*</span></label>
                    <input type="date" name="date_end" value="{{ old('date_end') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    @error('date_end') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('jadwal-audit.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 shadow-sm transition-colors">Simpan Jadwal & Kirim Notifikasi</button>
            </div>
        </form>
    </x-card>
</x-app-layout>
