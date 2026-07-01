<?php

namespace App\Services;

use App\Models\LogActivity;

class LogActivityService
{
    public function log($userId, $aksi, $modul, $detail, $request = null)
    {
        $ip = $request ? $request->ip() : request()->ip();
        $agent = $request ? $request->userAgent() : request()->userAgent();

        return LogActivity::create([
            'user_id' => $userId,
            'action' => $aksi,
            'module' => $modul,
            'description' => $detail,
            'ip_address' => $ip,
            'user_agent' => $agent
        ]);
    }
}
