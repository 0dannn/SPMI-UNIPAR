<x-app-layout>
    <x-slot name="header">Dashboard Auditee ({{ auth()->user()->unit->name ?? 'Unit' }})</x-slot>

    @if(!$activePeriode)
        <x-alert type="info" message="Belum ada periode evaluasi mutu yang berjalan saat ini." />
    @else
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Evaluasi Diri Periode: {{ $activePeriode->name }}</h2>
        </div>

        <!-- Progress Widget -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-2xl shadow-lg p-8 mb-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64 -mt-10 -mr-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-blue-200 mb-1">Status Pengisian Capaian</h3>
                    <div class="text-5xl font-black">{{ $progress }}<span class="text-2xl text-blue-300">%</span></div>
                    <p class="mt-2 text-blue-100 text-sm">Telah diisi: {{ $filled }} dari total {{ $indikatorCount }} Indikator Mutu.</p>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="w-full bg-white/20 rounded-full h-4 mb-3">
                        <div class="bg-green-400 h-4 rounded-full shadow-inner transition-all duration-1000" style="width: {{ $progress }}%"></div>
                    </div>
                    @if($progress == 100)
                        <a href="{{ route('pengukuran.index') }}" class="inline-block bg-white text-blue-800 px-5 py-2 rounded-lg font-bold text-sm shadow hover:bg-gray-100 transition-colors">Lihat Evaluasi Final</a>
                    @else
                        <a href="{{ route('pengukuran.index') }}" class="inline-block bg-green-500 text-white px-5 py-2 rounded-lg font-bold text-sm shadow hover:bg-green-600 transition-colors">Lanjutkan Pengisian Laporan -></a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card title="Jadwal Audit Lapangan Anda">
                @if(!$jadwal)
                    <div class="text-center py-6 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm">LPM belum memploting jadwal audit untuk unit Anda.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-sm font-bold text-gray-500">Auditor Ditugaskan</span>
                            <span class="font-bold text-gray-900">{{ $jadwal->auditor->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-sm font-bold text-gray-500">Tanggal Pelaksanaan</span>
                            <span class="font-mono text-gray-800">{{ $jadwal->date_start->format('d M Y') }} - {{ $jadwal->date_end->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-1">
                            <span class="text-sm font-bold text-gray-500">Status Audit</span>
                            <x-badge color="{{ $jadwal->status=='completed'?'green':($jadwal->status=='in_progress'?'yellow':'gray') }}">{{ strtoupper($jadwal->status) }}</x-badge>
                        </div>
                    </div>
                @endif
            </x-card>

            <x-card title="Temuan Membutuhkan Tindakan (P3)">
                @if(count($temuan) == 0)
                    <div class="text-center py-6 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm">Bagus! Tidak ada temuan KTS/OB yang memerlukan RTL saat ini.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($temuan->take(4) as $t)
                            <div class="p-3 bg-red-50 rounded border border-red-100 flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-red-600 block mb-1">Indikator: {{ $t->pengukuran->indikator->standar->code }}</span>
                                    <span class="text-sm text-gray-800">{{ Str::limit($t->description, 40) }}</span>
                                </div>
                                <a href="{{ route('rtm-rtl.index') }}" class="text-xs bg-white border border-red-200 text-red-700 px-3 py-1.5 rounded hover:bg-red-100 font-bold">Buat RTL</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    @endif
</x-app-layout>
