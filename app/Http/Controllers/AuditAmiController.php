<?php

namespace App\Http\Controllers;

use App\Models\JadwalAudit;
use App\Models\AuditAmi;
use App\Models\Pengukuran;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class AuditAmiController extends Controller
{
    public function index()
    {
        $jadwals = JadwalAudit::where('auditor_id', auth()->id())->with(['periode', 'unit'])->latest()->paginate(10);
        return view('audit-ami.index', compact('jadwals'));
    }

    public function show($id)
    {
        $jadwal = JadwalAudit::where('auditor_id', auth()->id())->with(['unit', 'periode'])->findOrFail($id);
        
        $pengukurans = Pengukuran::with(['indikator.standar', 'buktiFisiks', 'auditAmis' => function($q) use ($jadwal) {
            $q->where('jadwal_audit_id', $jadwal->id);
        }])
            ->where('unit_id', $jadwal->unit_id)
            ->whereHas('indikator.standar', function($q) use ($jadwal) {
                $q->where('periode_id', $jadwal->periode_id);
            })->get();
            
        return view('audit-ami.show', compact('jadwal', 'pengukurans'));
    }

    public function store(Request $request, $jadwal_id)
    {
        $jadwal = JadwalAudit::where('auditor_id', auth()->id())->findOrFail($jadwal_id);
        
        $request->validate([
            'pengukuran_id' => 'required|exists:pengukurans,id',
            'auditor_score' => 'required|in:1,2,3,4',
            'finding_type' => 'required|in:Sesuai,OB,KTS',
            'description' => 'nullable|string'
        ]);

        AuditAmi::updateOrCreate(
            [
                'jadwal_audit_id' => $jadwal->id,
                'pengukuran_id' => $request->pengukuran_id
            ],
            [
                'auditor_score' => $request->auditor_score,
                'finding_type' => $request->finding_type,
                'description' => $request->description
            ]
        );
        
        if ($jadwal->status === 'planned') {
            $jadwal->update(['status' => 'in_progress']);
        }

        return back()->with('status', 'Penilaian untuk indikator berhasil disimpan.');
    }

    public function submit(Request $request, $jadwal_id)
    {
        $jadwal = JadwalAudit::where('auditor_id', auth()->id())->findOrFail($jadwal_id);
        
        $pengukuransCount = Pengukuran::where('unit_id', $jadwal->unit_id)
            ->whereHas('indikator.standar', function($q) use ($jadwal) {
                $q->where('periode_id', $jadwal->periode_id);
            })->count();
            
        $auditsCount = AuditAmi::where('jadwal_audit_id', $jadwal->id)->count();
        
        if ($auditsCount < $pengukuransCount) {
            return back()->with('error', "Semua indikator ({$pengukuransCount}) harus dinilai sebelum submit final. Baru dinilai: {$auditsCount}");
        }

        $jadwal->update(['status' => 'completed']);
        
        $auditees = \App\Models\User::where('unit_id', $jadwal->unit_id)->whereHas('roles', function($q) {
            $q->where('name', 'Auditee');
        })->get();
        
        foreach($auditees as $auditee) {
            Notifikasi::create([
                'user_id' => $auditee->id,
                'title' => 'Laporan AMI Selesai',
                'message' => "Proses Audit Mutu Internal telah selesai. Silakan periksa hasil temuan.",
                'is_read' => false
            ]);
        }

        return redirect()->route('audit-ami.index')->with('status', 'Laporan AMI berhasil disubmit final!');
    }
}
