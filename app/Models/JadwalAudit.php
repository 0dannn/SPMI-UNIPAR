<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class JadwalAudit extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['periode_id', 'unit_id', 'auditor_id', 'date_start', 'date_end', 'status'];
    
    protected function casts(): array {
        return [
            'date_start' => 'datetime',
            'date_end' => 'datetime'
        ];
    }

    public function periode() { return $this->belongsTo(Periode::class); }
    public function unit() { return $this->belongsTo(Unit::class); }
    public function auditor() { return $this->belongsTo(User::class, 'auditor_id'); }
    public function auditAmis() { return $this->hasMany(AuditAmi::class); }
}
