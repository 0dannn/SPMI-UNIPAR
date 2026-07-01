<x-app-layout>
    <x-slot name="header">Kelola Sistem (Manajemen Pengguna)</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah User -->
        <div>
            <x-card title="Registrasi Akun Baru">
                <form action="{{ route('users.store') }}" method="POST" x-data="{ role: '', loading: false }" @submit="loading = true">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap Pengguna</label>
                        <input type="text" name="name" class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Surel (Email Resmi)</label>
                        <input type="email" name="email" class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" required>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tentukan Hak Akses / Jabatan</label>
                        <select name="role" x-model="role" class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Akses --</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Muncul jika role Auditee dipilih -->
                    <div class="mb-6" x-show="role == 'Auditee'" style="display:none;">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ditempatkan di Unit Kerja</label>
                        <select name="unit_id" class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Penempatan --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" x-bind:disabled="loading" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2 px-4 rounded-md shadow-sm transition-colors text-sm"><span x-text="loading ? 'Memproses...' : 'Registrasikan Akun & Buat Sandi'"></span></button>
                </form>
            </x-card>
        </div>

        <!-- Tabel User -->
        <div class="lg:col-span-2">
            <x-card title="Daftar Pengguna Sistem SPMI">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/70">
                            <tr>
                                <th class="px-5 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Identitas Pengguna</th>
                                <th class="px-5 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Otorisasi Hak Akses</th>
                                <th class="px-5 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Izin Login (Aktif)</th>
                                <th class="px-5 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kontrol</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs mr-3">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        @foreach($user->roles as $role)
                                            <x-badge color="blue">{{ $role->name }}</x-badge>
                                        @endforeach
                                        @if($user->unit)
                                            <div class="text-[10px] text-gray-400 mt-1 uppercase font-bold">{{ $user->unit->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center text-green-600 font-bold text-xs"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Aktif</span>
                                        @else
                                            <span class="inline-flex items-center text-red-600 font-bold text-xs"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Diblokir</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-sm">
                                        @if(!$user->hasRole('Admin') && $user->id != auth()->id())
                                            <form action="{{ route('users.toggleActive', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1.5 rounded text-xs font-bold transition-colors">
                                                    {{ $user->is_active ? 'Cabut Akses (Blokir)' : 'Pulihkan Akses' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            </x-card>
        </div>
    </div>
</x-app-layout>
