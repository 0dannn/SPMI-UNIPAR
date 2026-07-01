<?php

namespace App\Http\Controllers;

use App\Models\Standar;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StandarController extends Controller
{
    public function index()
    {
        $activePeriode = Periode::aktif()->first();
        $standars = Standar::with('periode')->when($activePeriode, function($q) use ($activePeriode) {
            $q->where('periode_id', $activePeriode->id);
        })->paginate(10);
        
        return view('standar.index', compact('standars', 'activePeriode'));
    }

    public function create()
    {
        $periodes = Periode::all();
        $standar = new Standar();
        return view('standar.form', compact('periodes', 'standar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'code' => ['required', Rule::unique('standars')->where('periode_id', $request->periode_id)],
            'periode_id' => 'required|exists:periodes,id',
            'description' => 'nullable'
        ], [
            'title.required' => 'Nama standar wajib diisi.',
            'code.required' => 'Kode standar wajib diisi.',
            'code.unique' => 'Kode standar sudah digunakan pada periode ini.'
        ]);

        Standar::create($request->all());
        return redirect()->route('standar.index')->with('status', 'Standar Mutu berhasil ditambahkan.');
    }

    public function show(Standar $standar)
    {
        $standar->load('indikators');
        return view('standar.show', compact('standar'));
    }

    public function edit(Standar $standar)
    {
        $periodes = Periode::all();
        return view('standar.form', compact('standar', 'periodes'));
    }

    public function update(Request $request, Standar $standar)
    {
        $request->validate([
            'title' => 'required|max:255',
            'code' => ['required', Rule::unique('standars')->where('periode_id', $request->periode_id)->ignore($standar->id)],
            'periode_id' => 'required|exists:periodes,id',
            'description' => 'nullable'
        ], [
            'title.required' => 'Nama standar wajib diisi.',
            'code.required' => 'Kode standar wajib diisi.',
            'code.unique' => 'Kode standar sudah digunakan pada periode ini.'
        ]);

        $standar->update($request->all());
        return redirect()->route('standar.index')->with('status', 'Standar Mutu berhasil diperbarui.');
    }

    public function destroy(Standar $standar)
    {
        $standar->delete();
        return redirect()->route('standar.index')->with('status', 'Standar Mutu berhasil dihapus.');
    }
}
