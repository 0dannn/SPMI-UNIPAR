<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class AuditAmi extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['jadwal_audit_id', 'pengukuran_id', 'auditor_score', 'finding_type', 'description'];

    public function jadwal() { return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id'); }
    public function pengukuran() { return $this->belongsTo(Pengukuran::class); }
    public function rtls() { return $this->hasMany(RtmRtl::class); }
    public function komentars() { return $this->hasMany(KomentarAmi::class); }
}
