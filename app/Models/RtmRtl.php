<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class RtmRtl extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['audit_ami_id', 'auditee_id', 'description', 'target_date', 'status', 'auditor_validation'];
    
    protected function casts(): array {
        return [
            'target_date' => 'datetime',
            'auditor_validation' => 'boolean'
        ];
    }

    public function auditAmi() { return $this->belongsTo(AuditAmi::class); }
    public function auditee() { return $this->belongsTo(User::class, 'auditee_id'); }
    public function lampirans() { return $this->morphMany(FileUpload::class, 'uploadable'); }
}
