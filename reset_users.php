<?php
use App\Models\User;
use App\Models\Unit;

$unit = Unit::firstOrCreate(['name' => 'Fakultas Teknik', 'type' => 'Fakultas']);

$roles = ['LPM', 'Auditee', 'Auditor', 'Pimpinan'];
foreach ($roles as $role) {
    $email = strtolower($role) . '@spmi.com';
    $u = User::firstOrCreate(['email' => $email], [
        'name' => 'Demo ' . $role,
        'password' => bcrypt('password'),
        'unit_id' => ($role == 'Auditee') ? $unit->id : null,
        'is_active' => true,
    ]);
    if (!$u->hasRole($role)) {
        $u->assignRole($role);
    }
}

$users = User::with('roles')->get();
foreach ($users as $u) {
    $u->password = bcrypt('password');
    $u->save();
    $role = $u->roles->first() ? $u->roles->first()->name : 'N/A';
    echo $u->email . " | Role: " . $role . "\n";
}
exit;
