<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class Standar extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['periode_id', 'code', 'title', 'description', 'is_active'];
    
    protected function casts(): array {
        return ['is_active' => 'boolean'];
    }

    public function periode() { return $this->belongsTo(Periode::class); }
    public function indikators() { return $this->hasMany(Indikator::class); }

    public function scopeAktif($query) { return $query->where('is_active', true); }
}
