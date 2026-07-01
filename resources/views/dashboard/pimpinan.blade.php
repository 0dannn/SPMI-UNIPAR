<x-app-layout>
    <x-slot name="header">Executive Dashboard (Pimpinan)</x-slot>

    @if(!$activePeriode)
        <x-alert type="warning" message="Belum ada periode aktif. Hubungi Admin." />
    @else
        <div class="mb-6">
            <h2 class="text-2xl font-black text-gray-800">Laporan Mutu Komprehensif: {{ $activePeriode->name }}</h2>
            <p class="text-gray-500">Melihat kinerja mutu seluruh unit dan indikator secara *real-time*.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Pie Chart: IKU Tercapai vs Tidak -->
            <x-card title="Pencapaian Mutu Keseluruhan (Berdasarkan Temuan Auditor)">
                <div class="h-64 flex justify-center">
                    <canvas id="pieChart"></canvas>
                </div>
            </x-card>

            <!-- Line Chart: Tren Capaian -->
            <x-card title="Tren Rata-rata Skor AMI (3 Periode Terakhir)">
                <div class="h-64">
                    <canvas id="lineChart"></canvas>
                </div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Bar Chart: Skor per Unit -->
            <div class="lg:col-span-2">
                <x-card title="Perbandingan Rata-rata Skor AMI per Unit (Skala 1-4)">
                    <div class="h-72">
                        <canvas id="barChart"></canvas>
                    </div>
                </x-card>
            </div>

            <!-- Donut Chart: RTL -->
            <div>
                <x-card title="Status Rencana Tindak Lanjut (RTL)">
                    <div class="h-72 flex justify-center">
                        <canvas id="donutChart"></canvas>
                    </div>
                </x-card>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Konfigurasi Global Font
                Chart.defaults.font.family = "'Inter', 'sans-serif'";
                
                // PIE CHART
                new Chart(document.getElementById('pieChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Memenuhi Standar (Sesuai)', 'Terdapat Temuan (KTS/OB)'],
                        datasets: [{
                            data: {!! $pieChartData !!},
                            backgroundColor: ['#10B981', '#EF4444'],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
                });

                // LINE CHART
                new Chart(document.getElementById('lineChart'), {
                    type: 'line',
                    data: {
                        labels: {!! $lineChartLabels !!},
                        datasets: [{
                            label: 'Rata-rata Skor Institusi',
                            data: {!! $lineChartData !!},
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#1D4ED8',
                            pointRadius: 5
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { min: 0, max: 4 } },
                        plugins: { legend: { display: false } }
                    }
                });

                // BAR CHART
                new Chart(document.getElementById('barChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! $barChartLabels !!},
                        datasets: [{
                            label: 'Skor Audit Mutu (Max 4.0)',
                            data: {!! $barChartData !!},
                            backgroundColor: '#6366F1',
                            borderRadius: 6
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true, max: 4 } },
                        plugins: { legend: { display: false } }
                    }
                });

                // DONUT CHART
                new Chart(document.getElementById('donutChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Berjalan', 'Belum Ada RTL'],
                        datasets: [{
                            data: {!! $donutChartData !!},
                            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                            borderWidth: 0
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, cutout: '65%',
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
