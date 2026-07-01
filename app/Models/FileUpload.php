<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoLogActivity;

class FileUpload extends Model
{
    use AutoLogActivity;
    
    protected $fillable = ['uploadable_id', 'uploadable_type', 'file_name', 'file_path', 'file_size', 'user_id'];

    public function uploadable() { return $this->morphTo(); }
}
