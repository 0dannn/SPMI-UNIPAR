<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder {
    public function run(): void {
        $roles = ['Admin', 'LPM', 'Auditee', 'Auditor', 'Pimpinan'];
        foreach($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}

class UnitSeeder extends Seeder {
    public function run(): void {
        $units = ['Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Teknik Industri', 'Manajemen Bisnis'];
        foreach($units as $unit) {
            DB::table('units')->insert(['name' => $unit, 'type' => 'Prodi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }
    }
}

class PeriodeSeeder extends Seeder {
    public function run(): void {
        DB::table('periodes')->insert(['name' => 'Ganjil 2024/2025', 'year' => 2024, 'is_active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
    }
}

class AdminSeeder extends Seeder {
    public function run(): void {
        $userId = DB::table('users')->insertGetId([
            'unit_id' => null,
            'name' => 'Administrator',
            'email' => 'admin@spmi.com',
            'password' => Hash::make('password123'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $user = \App\Models\User::find($userId);
        $user->assignRole('Admin');
    }
}

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UnitSeeder::class,
            PeriodeSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
