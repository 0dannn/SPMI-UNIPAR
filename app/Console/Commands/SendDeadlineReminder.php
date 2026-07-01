<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalAudit;
use App\Models\User;
use App\Models\Pengukuran;
use App\Services\NotifikasiService;
use Illuminate\Support\Carbon;

class SendDeadlineReminder extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'spmi:send-deadline-reminder';

    /**
     * The console command description.
     */
    protected $description = 'Mengirim notifikasi otomatis ke Auditee pada H-7 dan H-3 sebelum deadline pengumpulan dokumen Evaluasi Diri (P2).';

    /**
     * Execute the console command.
     */
    public function handle(NotifikasiService $notifikasiService)
    {
        $this->info('Memulai pengecekan deadline periode audit...');
        
        $h7 = Carbon::now()->addDays(7)->toDateString();
        $h3 = Carbon::now()->addDays(3)->toDateString();

        // Mencari jadwal yang berada tepat pada H-7 atau H-3
        $jadwals = JadwalAudit::whereIn('date_end', [$h7, $h3])
            ->where('status', 'in_progress')
            ->get();

        $count = 0;
        foreach ($jadwals as $jadwal) {
            
            // Periksa Auditee di Unit terkait
            $auditees = User::role('Auditee')->where('unit_id', $jadwal->unit_id)->get();
            $sisaHari = (Carbon::now()->format('Y-m-d') == $h7) ? 7 : 3;
            
            foreach ($auditees as $auditee) {
                // Opsional: Cek apakah Auditee ini belum menyelesaikan pengukurannya
                $belumSelesai = Pengukuran::where('unit_id', $jadwal->unit_id)
                    ->whereNull('self_score')
                    ->exists();
                
                if ($belumSelesai) {
                    $notifikasiService->kirim(
                        $auditee->id,
                        "🚨 Peringatan Deadline Sistem (H-{$sisaHari})",
                        "Perhatian! Batas waktu penyelesaian input Evaluasi Diri (P2) tinggal {$sisaHari} hari lagi (" . Carbon::parse($jadwal->date_end)->format('d M Y') . "). Segera lengkapi borang yang masih kosong.",
                        'deadline'
                    );
                    $count++;
                }
            }
        }

        $this->info("Pengecekan selesai. Sebanyak {$count} notifikasi reminder berhasil disebar via Queue.");
    }
}
