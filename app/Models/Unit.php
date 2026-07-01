<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class Unit extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['name', 'type'];
    
    public function users() { return $this->hasMany(User::class); }
    public function pengukurans() { return $this->hasMany(Pengukuran::class); }
    public function jadwalAudits() { return $this->hasMany(JadwalAudit::class); }
}
