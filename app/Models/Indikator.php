<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class Indikator extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['standar_id', 'type', 'description', 'target'];

    public function standar() { return $this->belongsTo(Standar::class); }
    public function pengukurans() { return $this->hasMany(Pengukuran::class); }
}
