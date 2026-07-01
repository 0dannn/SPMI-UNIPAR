<?php
namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::latest('year')->get();
        return view('periode.index', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        $isFirst = Periode::count() === 0;

        Periode::create([
            'name' => $request->name,
            'year' => $request->year,
            'is_active' => $isFirst,
            'is_locked' => false
        ]);

        return back()->with('status', 'Periode Akademik berhasil ditambahkan.');
    }

    public function setAktif($id)
    {
        $periode = Periode::findOrFail($id);
        
        if ($periode->is_locked) {
            return back()->with('error', 'Gagal! Periode ini sudah dikunci secara permanen.');
        }

        Periode::where('id', '!=', $id)->update(['is_active' => false]);
        $periode->update(['is_active' => true]);

        return back()->with('status', "Periode {$periode->name} sekarang menjadi Periode Aktif.");
    }

    public function kunci($id)
    {
        $periode = Periode::findOrFail($id);
        $periode->update(['is_locked' => true, 'is_active' => false]);
        
        return back()->with('status', 'Periode telah dikunci dan diamankan. Semua modul terkait periode ini dihentikan.');
    }

    public function unlock($id)
    {
        $periode = Periode::findOrFail($id);
        $periode->update(['is_locked' => false]);
        return back()->with('status', 'Peringatan: Kunci periode berhasil dibuka kembali.');
    }
}
