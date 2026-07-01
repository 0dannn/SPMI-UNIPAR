<?php

namespace App\Http\Controllers;

use App\Models\AuditAmi;
use App\Models\RtmRtl;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;

class RtmRtlController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = AuditAmi::with(['jadwal.unit', 'jadwal.periode', 'pengukuran.indikator.standar', 'rtmRtl', 'komentars'])
            ->whereIn('finding_type', ['OB', 'KTS'])
            ->whereHas('jadwal', function($q) {
                $q->where('status', 'completed');
            });

        if ($user->hasRole('Auditee')) {
            $query->whereHas('jadwal', function($q) use ($user) {
                $q->where('unit_id', $user->unit_id);
            });
        } elseif ($request->unit_id) {
            $query->whereHas('jadwal', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }
        
        if ($request->status) {
            if ($request->status == 'belum') {
                $query->doesntHave('rtmRtl');
            } else {
                $query->whereHas('rtmRtl', function($q) use ($request) {
                    $q->where('status', $request->status);
                });
            }
        }

        $auditAmis = $query->latest()->paginate(15);
        $units = Unit::all();

        return view('rtm-rtl.index', compact('auditAmis', 'units'));
    }

    public function show(AuditAmi $auditAmi)
    {
        $auditAmi->load(['jadwal.unit', 'jadwal.periode', 'pengukuran.indikator.standar', 'rtmRtl', 'komentars.user']);
        return view('rtm-rtl.show', compact('auditAmi'));
    }

    public function store(Request $request, AuditAmi $auditAmi)
    {
        $request->validate([
            'description' => 'required',
            'target_date' => 'required|date|after_or_equal:today',
            'status' => 'required'
        ]);

        RtmRtl::create([
            'audit_ami_id' => $auditAmi->id,
            'auditee_id' => auth()->id(),
            'description' => $request->description,
            'target_date' => $request->target_date,
            'status' => $request->status,
            'auditor_validation' => false
        ]);

        $lpmUsers = User::role('LPM')->get();
        foreach($lpmUsers as $lpm) {
            Notifikasi::create([
                'user_id' => $lpm->id,
                'title' => 'RTL Baru Dibuat',
                'message' => "Auditee dari " . auth()->user()->unit->name . " telah merumuskan RTL untuk temuan " . $auditAmi->finding_type . ".",
                'is_read' => false
            ]);
        }

        return back()->with('status', 'Rencana Tindak Lanjut berhasil disimpan.');
    }

    public function update(Request $request, RtmRtl $rtmRtl)
    {
        $request->validate([
            'description' => 'required',
            'target_date' => 'required|date',
            'status' => 'required'
        ]);

        $rtmRtl->update([
            'description' => $request->description,
            'target_date' => $request->target_date,
            'status' => $request->status
        ]);

        return back()->with('status', 'Rencana Tindak Lanjut berhasil diperbarui.');
    }

    public function verify(Request $request, RtmRtl $rtmRtl)
    {
        $rtmRtl->update([
            'auditor_validation' => true,
        ]);

        Notifikasi::create([
            'user_id' => $rtmRtl->auditee_id,
            'title' => 'RTL Diverifikasi',
            'message' => "Rencana Tindak Lanjut Anda telah diverifikasi dan disetujui selesai oleh LPM/Auditor.",
            'is_read' => false
        ]);

        return back()->with('status', 'RTL berhasil diverifikasi.');
    }
}
