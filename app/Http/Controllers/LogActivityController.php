<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = LogActivity::with('user')->latest();
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->module) {
            $query->where('module', $request->module);
        }
        
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }
        
        $logs = $query->paginate(20);
        $users = \App\Models\User::all();
        $modules = LogActivity::whereNotNull('module')->select('module')->distinct()->pluck('module');
        
        return view('log-activity.index', compact('logs', 'users', 'modules'));
    }

    public function exportCsv(Request $request)
    {
        $query = LogActivity::with('user')->latest();
        
        if ($request->user_id) $query->where('user_id', $request->user_id);
        if ($request->module) $query->where('module', $request->module);
        if ($request->date) $query->whereDate('created_at', $request->date);
        
        $logs = $query->get();
        
        $filename = "audit_trail_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Waktu', 'User', 'Aksi', 'Modul', 'Deskripsi', 'IP Address', 'User Agent']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'System',
                    $log->action,
                    $log->module ?? '-',
                    $log->description,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
