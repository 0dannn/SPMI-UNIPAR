<?php

namespace App\Http\Controllers;

use App\Models\AuditAmi;
use App\Models\KomentarAmi;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;

class KomentarAmiController extends Controller
{
    public function store(Request $request, $audit_ami_id)
    {
        $request->validate(['comment' => 'required|string']);
        
        $auditAmi = AuditAmi::with('jadwal')->findOrFail($audit_ami_id);
        
        KomentarAmi::create([
            'audit_ami_id' => $auditAmi->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);
        
        $lpmUsers = User::role('LPM')->get();
        foreach($lpmUsers as $lpm) {
            Notifikasi::create([
                'user_id' => $lpm->id,
                'title' => 'Banding Temuan AMI',
                'message' => "Auditee dari " . auth()->user()->unit->name . " mengajukan banding terhadap temuan {$auditAmi->finding_type}.",
                'is_read' => false
            ]);
        }

        return back()->with('status', 'Banding / Komentar berhasil dikirim ke LPM.');
    }
}
