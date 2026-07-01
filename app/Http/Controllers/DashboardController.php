<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Standar;
use App\Models\Indikator;
use App\Models\Pengukuran;
use App\Models\AuditAmi;
use App\Models\RtmRtl;
use App\Models\JadwalAudit;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getCommonStats($periodeId)
    {
        $standars = Standar::where('periode_id', $periodeId)->pluck('id');
        $indikatorCount = Indikator::whereIn('standar_id', $standars)->count();
        $standarCount = $standars->count();
        
        $jadwals = JadwalAudit::where('periode_id', $periodeId)->pluck('id');
        
        $audits = AuditAmi::whereIn('jadwal_audit_id', $jadwals)->get();
        $ktsCount = $audits->where('finding_type', 'KTS')->count();
        $obCount = $audits->where('finding_type', 'OB')->count();
        $sesuaiCount = $audits->where('finding_type', 'Sesuai')->count();

        $rtlOverdueCount = RtmRtl::whereHas('auditAmi', function($q) use ($jadwals) {
            $q->whereIn('jadwal_audit_id', $jadwals);
        })->where('status', '!=', 'Selesai')->where('target_date', '<', now())->count();

        return compact('standarCount', 'indikatorCount', 'ktsCount', 'obCount', 'sesuaiCount', 'rtlOverdueCount');
    }

    public function adminDashboard()
    {
        $activePeriode = Periode::aktif()->first();
        if (!$activePeriode) {
            return view('dashboard.admin', ['stats' => null, 'activePeriode' => null, 'usersCount' => 0, 'unitsCount' => 0, 'logCount' => 0]);
        }
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_stats_'.$activePeriode->id, 300, function() use ($activePeriode) {
            return $this->getCommonStats($activePeriode->id);
        });
        
        $usersCount = \Illuminate\Support\Facades\Cache::remember('admin_users_count', 300, fn() => User::count());
        $unitsCount = \Illuminate\Support\Facades\Cache::remember('admin_units_count', 300, fn() => Unit::count());
        $logCount = \App\Models\LogActivity::count();
        
        return view('dashboard.admin', compact('activePeriode', 'stats', 'usersCount', 'unitsCount', 'logCount'));
    }

    public function lpmDashboard()
    {
        $activePeriode = Periode::aktif()->first();
        if (!$activePeriode) {
            return view('dashboard.lpm', ['stats' => null, 'activePeriode' => null]);
        }
        $stats = \Illuminate\Support\Facades\Cache::remember('lpm_stats_'.$activePeriode->id, 300, function() use ($activePeriode) {
            return $this->getCommonStats($activePeriode->id);
        });
        
        $units = Unit::all();
        $indikatorCount = $stats['indikatorCount'];
        
        $unitProgress = [];
        foreach($units as $unit) {
            $filled = Pengukuran::where('unit_id', $unit->id)
                ->whereHas('indikator.standar', function($q) use ($activePeriode) {
                    $q->where('periode_id', $activePeriode->id);
                })->whereNotNull('self_score')->count();
            
            $unitProgress[] = [
                'name' => $unit->name,
                'progress' => $indikatorCount > 0 ? round(($filled / $indikatorCount) * 100) : 0
            ];
        }

        $jadwals = JadwalAudit::where('periode_id', $activePeriode->id)->pluck('id');
        $rtlSelesai = RtmRtl::whereHas('auditAmi', function($q) use ($jadwals) { $q->whereIn('jadwal_audit_id', $jadwals); })->where('status', 'Selesai')->count();
        $rtlBerjalan = RtmRtl::whereHas('auditAmi', function($q) use ($jadwals) { $q->whereIn('jadwal_audit_id', $jadwals); })->where('status', 'Berjalan')->count();
        $rtlBelum = AuditAmi::whereIn('jadwal_audit_id', $jadwals)->whereIn('finding_type', ['KTS', 'OB'])->doesntHave('rtls')->count();
        $rtlDirencanakan = RtmRtl::whereHas('auditAmi', function($q) use ($jadwals) { $q->whereIn('jadwal_audit_id', $jadwals); })->where('status', 'Direncanakan')->count();
        
        $rtlChartData = json_encode([$rtlSelesai, $rtlBerjalan, $rtlDirencanakan + $rtlBelum]);

        return view('dashboard.lpm', compact('activePeriode', 'stats', 'unitProgress', 'rtlChartData'));
    }

    public function auditeeDashboard()
    {
        $activePeriode = Periode::aktif()->first();
        $unitId = auth()->user()->unit_id;
        
        if (!$activePeriode) {
            return view('dashboard.auditee', ['activePeriode' => null]);
        }

        $indikatorCount = Indikator::whereHas('standar', function($q) use ($activePeriode) {
            $q->where('periode_id', $activePeriode->id);
        })->count();

        $filled = Pengukuran::where('unit_id', $unitId)
            ->whereHas('indikator.standar', function($q) use ($activePeriode) {
                $q->where('periode_id', $activePeriode->id);
            })->whereNotNull('self_score')->count();
            
        $progress = $indikatorCount > 0 ? round(($filled / $indikatorCount) * 100) : 0;
        
        $jadwal = JadwalAudit::where('periode_id', $activePeriode->id)->where('unit_id', $unitId)->first();
        
        $temuan = [];
        if ($jadwal) {
            $temuan = AuditAmi::where('jadwal_audit_id', $jadwal->id)->whereIn('finding_type', ['KTS', 'OB'])->get();
        }

        return view('dashboard.auditee', compact('activePeriode', 'progress', 'filled', 'indikatorCount', 'jadwal', 'temuan'));
    }

    public function auditorDashboard()
    {
        $jadwals = JadwalAudit::where('auditor_id', auth()->id())->with(['periode', 'unit'])->latest()->get();
        $pendingTasks = $jadwals->where('status', '!=', 'completed')->count();
        $completedTasks = $jadwals->where('status', 'completed')->count();
        
        return view('dashboard.auditor', compact('jadwals', 'pendingTasks', 'completedTasks'));
    }

    public function pimpinanDashboard()
    {
        $activePeriode = Periode::aktif()->first();
        if (!$activePeriode) {
            return view('dashboard.pimpinan', ['stats' => null, 'activePeriode' => null]);
        }
        $stats = \Illuminate\Support\Facades\Cache::remember('pimpinan_stats_'.$activePeriode->id, 300, function() use ($activePeriode) {
            return $this->getCommonStats($activePeriode->id);
        });
        
        $units = Unit::all();
        $barChartLabels = [];
        $barChartData = [];
        
        $jadwals = JadwalAudit::where('periode_id', $activePeriode->id)->get();
        
        foreach($units as $unit) {
            $jadwal = $jadwals->where('unit_id', $unit->id)->first();
            $avg = 0;
            if ($jadwal) {
                $avg = AuditAmi::where('jadwal_audit_id', $jadwal->id)->avg('auditor_score');
            }
            $barChartLabels[] = $unit->name;
            $barChartData[] = round($avg ?: 0, 2);
        }

        $pieChartData = [$stats['sesuaiCount'], $stats['obCount'] + $stats['ktsCount']];
        
        $periodes = Periode::latest()->take(3)->get()->reverse();
        $lineChartLabels = [];
        $lineChartData = [];
        
        foreach($periodes as $p) {
            $lineChartLabels[] = $p->name;
            $jIds = JadwalAudit::where('periode_id', $p->id)->pluck('id');
            $avgScore = AuditAmi::whereIn('jadwal_audit_id', $jIds)->avg('auditor_score');
            $lineChartData[] = round($avgScore ?: 0, 2);
        }

        $jadwalsActive = JadwalAudit::where('periode_id', $activePeriode->id)->pluck('id');
        $rtlSelesai = RtmRtl::whereHas('auditAmi', function($q) use ($jadwalsActive) { $q->whereIn('jadwal_audit_id', $jadwalsActive); })->where('status', 'Selesai')->count();
        $rtlBerjalan = RtmRtl::whereHas('auditAmi', function($q) use ($jadwalsActive) { $q->whereIn('jadwal_audit_id', $jadwalsActive); })->where('status', 'Berjalan')->count();
        $rtlDirencanakan = RtmRtl::whereHas('auditAmi', function($q) use ($jadwalsActive) { $q->whereIn('jadwal_audit_id', $jadwalsActive); })->where('status', 'Direncanakan')->count();
        $rtlBelum = AuditAmi::whereIn('jadwal_audit_id', $jadwalsActive)->whereIn('finding_type', ['KTS', 'OB'])->doesntHave('rtls')->count();
        
        $donutChartData = [$rtlSelesai, $rtlBerjalan, $rtlDirencanakan + $rtlBelum];

        return view('dashboard.pimpinan', [
            'activePeriode' => $activePeriode,
            'stats' => $stats,
            'barChartLabels' => json_encode($barChartLabels),
            'barChartData' => json_encode($barChartData),
            'pieChartData' => json_encode($pieChartData),
            'lineChartLabels' => json_encode($lineChartLabels),
            'lineChartData' => json_encode($lineChartData),
            'donutChartData' => json_encode($donutChartData)
        ]);
    }

    public function superDashboard()
    {
        $activePeriode = Periode::aktif()->first();
        $roles = auth()->user()->roles->pluck('name')->toArray();
        return view('dashboard.super', compact('activePeriode', 'roles'));
    }
}
