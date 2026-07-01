<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait AutoLogActivity
{
    public static function bootAutoLogActivity()
    {
        static::created(function ($model) {
            self::recordLog('Created', $model);
        });
        
        static::updated(function ($model) {
            self::recordLog('Updated', $model);
        });
        
        static::deleted(function ($model) {
            self::recordLog('Deleted', $model);
        });
    }
    
    protected static function recordLog($action, $model)
    {
        if (Auth::check()) {
            DB::table('log_activities')->insert([
                'user_id' => Auth::id(),
                'action' => $action . ' ' . class_basename($model),
                'description' => $action . ' record in ' . $model->getTable() . ' with ID: ' . $model->getKey(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
