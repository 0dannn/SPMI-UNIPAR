<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function edit()
    {
        $units = Unit::all();
        return view('profil.edit', compact('units'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        ];
        
        if ($user->hasRole('Auditee')) {
            $rules['unit_id'] = 'required|exists:units,id';
        }
        
        $request->validate($rules);
        
        $user->name = $request->name;
        $user->email = $request->email;
        if ($user->hasRole('Auditee')) {
            $user->unit_id = $request->unit_id;
        }
        
        $user->save();
        
        return back()->with('status', 'Informasi Data Diri berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('status', 'Password (Kata Sandi) berhasil diperbarui dan diamankan.');
    }
}
