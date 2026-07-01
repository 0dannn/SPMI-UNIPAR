<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class Periode extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['name', 'year', 'is_active', 'is_locked'];
    
    protected function casts(): array {
        return ['is_active' => 'boolean'];
    }
    
    public function standars() { return $this->hasMany(Standar::class); }
    public function jadwalAudits() { return $this->hasMany(JadwalAudit::class); }
    
    public function scopeAktif($query) { return $query->where('is_active', true); }
}
