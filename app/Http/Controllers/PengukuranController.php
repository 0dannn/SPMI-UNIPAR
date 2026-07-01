<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Standar;
use App\Models\Indikator;
use App\Models\Pengukuran;
use App\Models\FileUpload;
use App\Models\User;
use App\Models\Notifikasi;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengukuranController extends Controller
{
    public function index(Request $request)
    {
        $activePeriode = Periode::aktif()->first();
        if (!$activePeriode) {
            return view('pengukuran.index', ['indikators' => collect(), 'activePeriode' => null]);
        }

        $unitId = auth()->user()->unit_id;
        $standarId = $request->get('standar_id');
        
        $standars = Standar::where('periode_id', $activePeriode->id)->get();
        
        $indikatorsQuery = Indikator::with(['standar', 'pengukurans' => function($q) use ($unitId) {
            $q->where('unit_id', $unitId);
        }, 'pengukurans.buktiFisiks'])
        ->whereHas('standar', function($q) use ($activePeriode) {
            $q->where('periode_id', $activePeriode->id);
        });

        $globalIndikators = (clone $indikatorsQuery)->get();
        $globalTotal = $globalIndikators->count();
        $globalFilled = $globalIndikators->filter(function($i) {
            return $i->pengukurans->isNotEmpty() && $i->pengukurans->first()->self_score !== null;
        })->count();
        $globalProgress = $globalTotal > 0 ? round(($globalFilled / $globalTotal) * 100) : 0;

        if ($standarId) {
            $indikatorsQuery->where('standar_id', $standarId);
        }

        $indikators = $indikatorsQuery->get();
        
        $total = $indikators->count();
        $filled = $indikators->filter(function($i) {
            return $i->pengukurans->isNotEmpty() && $i->pengukurans->first()->self_score !== null;
        })->count();
        $progress = $total > 0 ? round(($filled / $total) * 100) : 0;
        
        $allSubmitted = $total > 0 && $indikators->every(function($i) {
            return $i->pengukurans->isNotEmpty() && in_array($i->pengukurans->first()->status, ['submitted', 'verified']);
        });

        return view('pengukuran.index', compact('indikators', 'standars', 'activePeriode', 'progress', 'globalProgress', 'globalTotal', 'globalFilled', 'allSubmitted', 'standarId', 'total', 'filled'));
    }

    public function edit(Indikator $indikator)
    {
        $unitId = auth()->user()->unit_id;
        $pengukuran = Pengukuran::firstOrCreate(
            ['indikator_id' => $indikator->id, 'unit_id' => $unitId],
            ['user_id' => auth()->id(), 'status' => 'draft']
        );
        
        if (in_array($pengukuran->status, ['submitted', 'verified'])) {
            return redirect()->route('pengukuran.index')->with('error', 'Evaluasi Diri sudah di-submit final dan terkunci.');
        }

        return view('pengukuran.edit', compact('indikator', 'pengukuran'));
    }

    public function update(Request $request, Pengukuran $pengukuran, FileUploadService $fileService)
    {
        if ($pengukuran->unit_id !== auth()->user()->unit_id || in_array($pengukuran->status, ['submitted', 'verified'])) {
            abort(403, 'Akses ditolak atau Evaluasi sudah dikunci.');
        }
        
        $request->validate([
            'self_score' => 'required',
            'bukti_file' => 'nullable|file|mimes:pdf,docx,xlsx,jpg,png|max:10240',
        ]);

        $pengukuran->update([
            'self_score' => $request->self_score,
            'status' => 'draft',
            'user_id' => auth()->id()
        ]);

        if ($request->hasFile('bukti_file')) {
            $fileService->upload($request, $pengukuran, 'bukti_file');
        }

        return redirect()->route('pengukuran.index')->with('status', 'Capaian berhasil disimpan sebagai draft.');
    }

    public function submitFinal(Request $request)
    {
        $unitId = auth()->user()->unit_id;
        $activePeriode = Periode::aktif()->first();
        
        if (!$activePeriode) return back();

        $indikatorIds = Indikator::whereHas('standar', function($q) use ($activePeriode) {
            $q->where('periode_id', $activePeriode->id);
        })->pluck('id');
        
        $pengukurans = Pengukuran::whereIn('indikator_id', $indikatorIds)
            ->where('unit_id', $unitId)
            ->get();
            
        if ($pengukurans->count() < $indikatorIds->count() || $pengukurans->whereNull('self_score')->count() > 0) {
            return back()->with('error', 'Semua indikator (' . $indikatorIds->count() . ' indikator) harus diisi sebelum Submit Final.');
        }
        
        Pengukuran::whereIn('indikator_id', $indikatorIds)
            ->where('unit_id', $unitId)
            ->update(['status' => 'submitted']);
            
        $lpmUsers = User::role('LPM')->get();
        $unitName = auth()->user()->unit->name;
        
        foreach($lpmUsers as $lpm) {
            Notifikasi::create([
                'user_id' => $lpm->id,
                'title' => 'Evaluasi Diri Disubmit',
                'message' => "Unit {$unitName} telah melakukan submit final evaluasi diri P2 untuk periode {$activePeriode->name}.",
                'is_read' => false
            ]);
        }

        return redirect()->route('pengukuran.index')->with('status', 'Evaluasi Diri berhasil disubmit secara final! Data terkunci untuk proses Audit.');
    }

    public function deleteFile(FileUpload $file)
    {
        $pengukuran = $file->uploadable;
        
        if (!$pengukuran instanceof Pengukuran || $pengukuran->unit_id !== auth()->user()->unit_id || in_array($pengukuran->status, ['submitted', 'verified'])) {
            abort(403);
        }
        
        Storage::disk('local')->delete($file->file_path);
        $file->delete();
        
        return back()->with('status', 'File bukti berhasil dihapus.');
    }
}
