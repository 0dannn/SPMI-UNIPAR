<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class PasswordReset extends Model
{
    use AutoLogActivity;
    
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = ['email', 'token', 'created_at'];
    
    protected function casts(): array {
        return ['created_at' => 'datetime'];
    }
}
