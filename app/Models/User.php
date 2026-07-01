<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\AutoLogActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, AutoLogActivity;
    
    protected $fillable = ['unit_id', 'name', 'email', 'password', 'is_active'];
    protected $hidden = ['password', 'remember_token'];
    
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function unit() { return $this->belongsTo(Unit::class); }
    public function pengukurans() { return $this->hasMany(Pengukuran::class); }
    public function jadwalAsAuditor() { return $this->hasMany(JadwalAudit::class, 'auditor_id'); }
}
