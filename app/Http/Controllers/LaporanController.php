<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Unit;
use App\Models\Pengukuran;
use App\Models\AuditAmi;
use App\Models\RtmRtl;
use App\Models\JadwalAudit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function exportCapaian(Request $request)
    {
        $request->validate(['periode_id' => 'required']);
        $periode = Periode::findOrFail($request->periode_id);
        
        $query = Pengukuran::with(['indikator.standar', 'unit', 'auditAmis' => function($q) use ($periode) {
            $q->whereHas('jadwal', function($j) use ($periode) {
                $j->where('periode_id', $periode->id);
            });
        }])->whereHas('indikator.standar', function($q) use ($periode) {
            $q->where('periode_id', $periode->id);
        });
        
        if ($request->unit_id) {
            $query->where('unit_id', $request->unit_id);
            $unit = Unit::find($request->unit_id);
        } else {
            $unit = null;
        }
        
        $pengukurans = $query->get()->sortBy(function($p) {
            return $p->unit_id . '-' . $p->indikator->standar->code;
        });
        
        $pdf = Pdf::loadView('laporan.capaian-pdf', compact('pengukurans', 'periode', 'unit'))
                  ->setPaper('a4', 'landscape');
                  
        return $pdf->download('Laporan_Capaian_Indikator_' . $periode->name . '.pdf');
    }

    public function exportTemuan(Request $request)
    {
        $request->validate(['periode_id' => 'required']);
        $periode = Periode::findOrFail($request->periode_id);
        
        $jadwalIds = JadwalAudit::where('periode_id', $periode->id)->pluck('id');
        
        $temuans = AuditAmi::with(['jadwal.unit', 'pengukuran.indikator.standar', 'rtmRtl'])
            ->whereIn('jadwal_audit_id', $jadwalIds)
            ->whereIn('finding_type', ['KTS', 'OB'])
            ->get();
            
        $pdf = Pdf::loadView('laporan.temuan-pdf', compact('temuans', 'periode'))
                  ->setPaper('a4', 'portrait');
                  
        return $pdf->download('Laporan_Temuan_AMI_' . $periode->name . '.pdf');
    }

    public function exportRtl(Request $request)
    {
        $request->validate(['periode_id' => 'required']);
        $periode = Periode::findOrFail($request->periode_id);
        
        $jadwalIds = JadwalAudit::where('periode_id', $periode->id)->pluck('id');
        
        $query = RtmRtl::with(['auditAmi.jadwal.unit', 'auditAmi.pengukuran.indikator.standar', 'auditee'])
            ->whereHas('auditAmi', function($q) use ($jadwalIds) {
                $q->whereIn('jadwal_audit_id', $jadwalIds);
            });
            
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $rtls = $query->get();
        
        $pdf = Pdf::loadView('laporan.rtl-pdf', compact('rtls', 'periode', 'request'))
                  ->setPaper('a4', 'landscape');
                  
        return $pdf->download('Laporan_Rekapitulasi_RTL_' . $periode->name . '.pdf');
    }
}
