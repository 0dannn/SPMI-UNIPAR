<x-app-layout>
    <x-slot name="header">Pengaturan Profil & Keamanan</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Panel Kiri: Avatar & Info Singkat -->
        <div class="lg:col-span-1">
            <x-card>
                <div class="flex flex-col items-center text-center py-4">
                    @php
                        // Buat inisial (Contoh: "Budi Santoso" -> "BS")
                        $words = explode(' ', auth()->user()->name);
                        $initials = '';
                        foreach($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
                        $initials = substr($initials, 0, 2);
                    @endphp
                    <div class="w-28 h-28 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-700 flex items-center justify-center text-white text-3xl font-black shadow-lg mb-4">
                        {{ $initials }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-500 mb-3">{{ auth()->user()->email }}</p>
                    
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-bold border border-gray-200">
                        ROTOR OTORISASI: {{ strtoupper(auth()->user()->roles->first()->name ?? 'GUEST') }}
                    </div>
                </div>
            </x-card>
        </div>
        
        <!-- Panel Kanan: Tabs (Edit Data & Password) -->
        <div class="lg:col-span-2 space-y-6">
            
            <x-card title="Informasi Data Diri">
                <form action="{{ route('profile.update') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap / Gelar Akademik</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    @if(auth()->user()->hasRole('Auditee'))
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Grup Afiliasi Institusi (Unit)</label>
                            <select name="unit_id" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ auth()->user()->unit_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    <button type="submit" x-bind:disabled="loading" class="bg-gray-900 hover:bg-black text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-colors text-sm"><span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan Informasi'"></span></button>
                </form>
            </x-card>

            <x-card title="Keamanan Kata Sandi (Password)">
                <form action="{{ route('profile.updatePassword') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>
                    
                    <button type="submit" x-bind:disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-colors text-sm"><span x-text="loading ? 'Memproses...' : 'Perbarui Kata Sandi'"></span></button>
                </form>
            </x-card>

        </div>
    </div>
</x-app-layout>
