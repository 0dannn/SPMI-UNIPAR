<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class Pengukuran extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['indikator_id', 'unit_id', 'user_id', 'self_score', 'status'];

    public function indikator() { return $this->belongsTo(Indikator::class); }
    public function unit() { return $this->belongsTo(Unit::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function auditAmis() { return $this->hasMany(AuditAmi::class); }
    public function buktiFisiks() { return $this->morphMany(FileUpload::class, 'uploadable'); }

    public function scopeSelesai($query) { return $query->where('status', 'submitted'); }
}
