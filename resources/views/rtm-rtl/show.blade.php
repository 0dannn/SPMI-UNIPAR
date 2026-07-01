<x-app-layout>
    <x-slot name="header">Pemantauan Rencana Tindak Lanjut (P3)</x-slot>

    <div class="mb-4">
        <a href="{{ route('rtm-rtl.index') }}" class="text-sm text-blue-600 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar RTL
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-6">
            <!-- Informasi Temuan -->
            <x-card title="Konteks Temuan Audit (Sumber RTL)">
                <div class="space-y-4 text-sm">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-400 text-xs uppercase">Unit Auditee</span>
                        <span class="font-medium text-gray-900 text-base">{{ $auditAmi->jadwal->unit->name }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-400 text-xs uppercase">Indikator & Target</span>
                        <span class="font-mono text-blue-700 mt-1 font-bold">{{ $auditAmi->pengukuran->indikator->standar->code }}</span>
                        <span class="text-gray-700 mt-1">{{ $auditAmi->pengukuran->indikator->description }}</span>
                    </div>
                    <div class="bg-red-50/50 p-4 rounded-xl border border-red-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-red-800 uppercase text-xs tracking-wider">Temuan {{ $auditAmi->finding_type }}</span>
                            <span class="bg-white border border-gray-200 text-gray-600 text-[10px] px-2 py-0.5 rounded shadow-sm">Skor: {{ $auditAmi->auditor_score }}</span>
                        </div>
                        <p class="text-red-900 leading-relaxed font-medium">"{{ $auditAmi->description }}"</p>
                    </div>
                </div>
            </x-card>

            <!-- Status RTL -->
            <x-card title="Dokumen Rencana Tindak Lanjut (RTL)">
                @if(!$auditAmi->rtmRtl)
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-600 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Belum Ada Rencana (RTL)</h3>
                        <p class="text-xs text-gray-500 mt-1">Pihak Unit Auditee belum merumuskan tindak lanjut perbaikan.</p>
                    </div>
                @else
                    <div class="space-y-5 text-sm">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 relative shadow-sm">
                            <svg class="absolute top-4 right-4 w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path></svg>
                            <span class="block text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-2">Tindakan Korektif Unit</span>
                            <span class="block mt-1 font-medium text-gray-900 leading-relaxed relative z-10">{{ $auditAmi->rtmRtl->description }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Target Selesai</span>
                                <span class="block mt-1 font-mono font-bold text-gray-800 text-lg">{{ $auditAmi->rtmRtl->target_date->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Progres Unit</span>
                                <x-badge color="{{ $auditAmi->rtmRtl->status == 'Selesai' ? 'green' : ($auditAmi->rtmRtl->status == 'Berjalan' ? 'yellow' : 'blue') }}">{{ $auditAmi->rtmRtl->status }}</x-badge>
                            </div>
                        </div>
                        
                        <div class="pt-5 border-t border-gray-100 mt-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Validasi Akhir (LPM)</span>
                                    @if($auditAmi->rtmRtl->auditor_validation)
                                        <div class="inline-flex items-center bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-md text-xs font-bold">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            TERVERIFIKASI TUNTAS
                                        </div>
                                    @else
                                        <div class="inline-flex items-center bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1.5 rounded-md text-xs font-bold animate-pulse">
                                            Menunggu Verifikasi LPM
                                        </div>
                                    @endif
                                </div>
                                
                                @if(auth()->user()->hasRole(['Admin', 'LPM']) && !$auditAmi->rtmRtl->auditor_validation)
                                    <form action="{{ route('rtm-rtl.verify', $auditAmi->rtmRtl) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin RTL ini telah dijalankan dan tuntas secara fisik? Klik OK untuk memverifikasi permanen.');">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-5 py-2.5 rounded-lg font-bold shadow-md hover:bg-green-700 text-xs transition-colors flex items-center">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Setujui Verifikasi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- Kolom Chat / Komentar -->
        <div>
            <x-card title="Utas Diskusi & Monitoring">
                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 flex flex-col h-[500px]">
                    
                    <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                        @forelse($auditAmi->komentars as $komentar)
                            @php $isMe = $komentar->user_id == auth()->id(); @endphp
                            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                                <span class="text-[10px] font-bold text-gray-500 mb-1 {{ $isMe ? 'mr-1' : 'ml-1' }}">
                                    {{ $isMe ? 'Anda' : $komentar->user->name }}
                                    <span class="font-normal text-gray-400 ml-1">({{ $komentar->created_at->diffForHumans() }})</span>
                                </span>
                                <div class="px-4 py-2.5 rounded-2xl max-w-[85%] text-sm {{ $isMe ? 'bg-blue-600 text-white rounded-tr-none shadow-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-none shadow-sm' }}">
                                    {{ $komentar->comment }}
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center text-gray-400">
                                <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <p class="text-sm italic">Belum ada diskusi monitoring terkait temuan ini.</p>
                                <p class="text-xs mt-1">Kirim pesan pertama Anda di bawah.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <form action="{{ route('banding.store', $auditAmi) }}" method="POST" class="mt-4 pt-4 border-t border-gray-200">
                        @csrf
                        <div class="flex items-end gap-2">
                            <textarea name="comment" rows="2" class="w-full text-sm border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 custom-scrollbar resize-none" required placeholder="Tuliskan catatan progres atau tanggapan Anda..."></textarea>
                            <button type="submit" class="shrink-0 p-3 bg-blue-600 text-white rounded-xl shadow-sm hover:bg-blue-700 transition-colors" title="Kirim Pesan">
                                <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
