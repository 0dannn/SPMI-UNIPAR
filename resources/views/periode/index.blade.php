<x-app-layout>
    <x-slot name="header">Manajemen Siklus Mutu (Periode Akademik)</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Periode -->
        <div>
            <x-card title="Definisikan Periode Baru">
                <form action="{{ route('periode.store') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Periode / Tahun Akademik</label>
                        <input type="text" name="name" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Genap 2026/2027" required>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Dasawarsa</label>
                        <input type="number" name="year" class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 2026" required>
                    </div>
                    <button type="submit" x-bind:disabled="loading" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition-colors"><span x-text="loading ? 'Memproses...' : 'Tambah Periode Baru'"></span></button>
                </form>
            </x-card>
            
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                <strong class="block mb-1">Informasi Aturan Periode:</strong>
                <ul class="list-disc pl-4 space-y-1">
                    <li>Hanya boleh ada 1 Periode "Aktif" pada satu waktu.</li>
                    <li>Status "Terkunci" akan membekukan secara permanen seluruh isian form di semua unit.</li>
                </ul>
            </div>
        </div>

        <!-- Tabel Periode -->
        <div class="lg:col-span-2">
            <x-card title="Daftar Riwayat Periode SPMI">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status & Sifat</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tindakan Admin</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($periodes as $p)
                                <tr class="hover:bg-gray-50 transition-colors {{ $p->is_active ? 'bg-blue-50/50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $p->is_active ? 'text-blue-800' : 'text-gray-900' }}">{{ $p->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->year }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($p->is_active)
                                            <span class="inline-flex items-center bg-green-100 text-green-800 px-2.5 py-0.5 rounded-full text-xs font-bold shadow-sm mb-1">BERJALAN (AKTIF)</span>
                                        @endif
                                        @if($p->is_locked)
                                            <span class="inline-flex items-center bg-red-100 text-red-800 px-2.5 py-0.5 rounded-full text-xs font-bold shadow-sm">🔒 TERKUNCI (READ-ONLY)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm flex space-x-2">
                                        @if(!$p->is_active && !$p->is_locked)
                                            <form action="{{ route('periode.setAktif', $p->id) }}" method="POST" onsubmit="return confirm('Jadikan periode ini sebagai fokus utama sistem? Semua fungsi input P2/E/P3 akan merujuk ke sini.');">
                                                @csrf
                                                <button type="submit" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded text-xs font-bold border border-blue-200 transition-colors">Aktivasi</button>
                                            </form>
                                        @endif
                                        
                                        @if(!$p->is_locked)
                                            <form action="{{ route('periode.kunci', $p->id) }}" method="POST" onsubmit="return confirm('PERINGATAN KRITIKAL!\n\nMengunci periode ini akan MENGHENTIKAN secara paksa proses Evaluasi (P2), Audit (E), dan RTL (P3) di semua institusi untuk periode ini.\nData tidak akan bisa diubah lagi (hanya Read-Only).\n\nApakah siklus penjaminan mutu sudah benar-benar ditutup?');">
                                                @csrf
                                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded text-xs font-bold border border-red-200 transition-colors">Tutup & Kunci Siklus</button>
                                            </form>
                                        @else
                                            <form action="{{ route('periode.unlock', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuka gembok periode ini? Pastikan Anda tahu risikonya.');">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors" title="Buka Kunci (Force Unlock)"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
