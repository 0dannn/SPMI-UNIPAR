<?php

namespace App\Http\Controllers;

use App\Models\JadwalAudit;
use App\Models\Periode;
use App\Models\Unit;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class JadwalAuditController extends Controller
{
    public function index()
    {
        $jadwals = JadwalAudit::with(['periode', 'unit', 'auditor'])->latest()->paginate(10);
        return view('jadwal-audit.index', compact('jadwals'));
    }

    public function create()
    {
        $periodes = Periode::aktif()->get();
        $units = Unit::all();
        $auditors = User::role('Auditor')->get();
        
        return view('jadwal-audit.create', compact('periodes', 'units', 'auditors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'unit_id' => 'required|exists:units,id',
            'auditor_id' => 'required|exists:users,id',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        $jadwal = JadwalAudit::create([
            'periode_id' => $request->periode_id,
            'unit_id' => $request->unit_id,
            'auditor_id' => $request->auditor_id,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'status' => 'planned'
        ]);

        $unitName = Unit::find($request->unit_id)->name;
        
        Notifikasi::create([
            'user_id' => $request->auditor_id,
            'title' => 'Tugas Audit Baru',
            'message' => "Anda ditugaskan sebagai Auditor untuk unit {$unitName} mulai " . $jadwal->date_start->format('d M Y') . ".",
            'is_read' => false
        ]);

        return redirect()->route('jadwal-audit.index')->with('status', 'Jadwal berhasil ditambahkan dan notifikasi dikirim ke Auditor.');
    }
}
