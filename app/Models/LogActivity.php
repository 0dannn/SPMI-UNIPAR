<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    // NO AutoLogActivity trait here to prevent infinite loop
    
    protected $table = 'log_activities';
    
    protected $fillable = ['user_id', 'action', 'module', 'description', 'ip_address', 'user_agent'];

    public function user() { return $this->belongsTo(User::class); }
}
