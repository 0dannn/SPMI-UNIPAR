<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class Notifikasi extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['user_id', 'title', 'message', 'type', 'is_read'];
    
    protected function casts(): array {
        return ['is_read' => 'boolean'];
    }

    public function user() { return $this->belongsTo(User::class); }

    public function scopeUnread($query) { return $query->where('is_read', false); }
}
