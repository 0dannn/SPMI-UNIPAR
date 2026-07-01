<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'unit'])->latest()->paginate(15);
        $roles = Role::where('name', '!=', 'Admin')->get();
        $units = Unit::all();
        
        return view('users.index', compact('users', 'roles', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,name',
            'unit_id' => 'required_if:role,Auditee'
        ]);

        $password = \Illuminate\Support\Str::random(8);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'unit_id' => $request->role == 'Auditee' ? $request->unit_id : null,
            'is_active' => true
        ]);
        
        $user->assignRole($request->role);

        return back()->with('status', "Pengguna berhasil dibuat! Password Semenara: {$password} (Berikan ke user bersangkutan).");
    }

    public function toggleActive($id)
    {
        if ($id == auth()->id()) return back()->with('error', 'Kesalahan: Tidak bisa menonaktifkan akun sendiri.');
        
        $user = User::findOrFail($id);
        
        if ($user->hasRole('Admin')) return back()->with('error', 'Kesalahan: Super Admin tidak bisa dinonaktifkan.');

        $user->update(['is_active' => !$user->is_active]);
        
        return back()->with('status', 'Status aktif (izin login) pengguna berhasil diubah.');
    }
}
