<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class KomentarAmi extends Model
{
    use AutoLogActivity;
    
    protected $table = 'komentar_amis';
    protected $fillable = ['audit_ami_id', 'user_id', 'comment'];

    public function auditAmi() { return $this->belongsTo(AuditAmi::class); }
    public function user() { return $this->belongsTo(User::class); }
}
