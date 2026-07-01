<?php

namespace App\Http\Controllers;

use App\Models\Standar;
use App\Models\Indikator;
use Illuminate\Http\Request;

class IndikatorController extends Controller
{
    public function store(Request $request, Standar $standar)
    {
        $request->validate([
            'description' => 'required',
            'target' => 'required'
        ], [
            'description.required' => 'Deskripsi indikator wajib diisi.',
            'target.required' => 'Target wajib diisi.'
        ]);

        $data = $request->all();
        if (empty($data['type'])) {
            $data['type'] = 'IKU';
        }
        $standar->indikators()->create($data);
        
        return redirect()->route('standar.show', $standar)->with('status', 'Indikator berhasil ditambahkan.');
    }

    public function update(Request $request, Indikator $indikator)
    {
        $request->validate([
            'description' => 'required',
            'target' => 'required'
        ], [
            'description.required' => 'Deskripsi indikator wajib diisi.',
            'target.required' => 'Target wajib diisi.'
        ]);

        $data = $request->all();
        if (empty($data['type'])) {
            $data['type'] = 'IKU';
        }
        $indikator->update($data);
        
        return redirect()->route('standar.show', $indikator->standar_id)->with('status', 'Indikator berhasil diperbarui.');
    }

    public function destroy(Indikator $indikator)
    {
        $standarId = $indikator->standar_id;
        $indikator->delete();
        return redirect()->route('standar.show', $standarId)->with('status', 'Indikator berhasil dihapus.');
    }
}
