<x-app-layout>
    <x-slot name="header">Dashboard LPM (Lembaga Penjaminan Mutu)</x-slot>

    @if(!$activePeriode)
        <x-alert type="warning" message="Belum ada periode akademik yang aktif." />
    @else
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Periode: {{ $activePeriode->name }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-6">
                <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Standar & Indikator</span>
                <span class="block text-3xl font-black text-gray-900">{{ $stats['standarCount'] }} <span class="text-base font-normal text-gray-400">Standar / {{ $stats['indikatorCount'] }} IKU</span></span>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-red-500 p-6">
                <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Temuan Audit (KTS/OB)</span>
                <span class="block text-3xl font-black text-gray-900">{{ $stats['ktsCount'] + $stats['obCount'] }} <span class="text-base font-normal text-red-500 text-sm">Masalah Terdeteksi</span></span>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-yellow-500 p-6">
                <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">RTL Overdue (Lewat Tenggat)</span>
                <span class="block text-3xl font-black text-gray-900">{{ $stats['rtlOverdueCount'] }} <span class="text-base font-normal text-yellow-600 text-sm">Menunggu Tindakan</span></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Progress Pengisian -->
            <div class="lg:col-span-2">
                <x-card title="Rekapitulasi Capaian Evaluasi Diri (P2)">
                    <div class="space-y-5 mt-4">
                        @foreach($unitProgress as $up)
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-bold text-gray-700">{{ $up['name'] }}</span>
                                    <span class="text-xs font-mono font-bold {{ $up['progress'] == 100 ? 'text-green-600' : 'text-blue-600' }}">{{ $up['progress'] }}% Selesai</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="{{ $up['progress'] == 100 ? 'bg-green-500' : 'bg-blue-600' }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ $up['progress'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            </div>

            <!-- Chart RTL -->
            <div>
                <x-card title="Distribusi Status RTL">
                    <canvas id="rtlChart" height="250"></canvas>
                </x-card>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Chart(document.getElementById('rtlChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai (Tuntas)', 'Berjalan (Diproses)', 'Belum / Direncanakan'],
                        datasets: [{
                            data: {!! $rtlChartData !!},
                            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
                        }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
