<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    protected $notifService;

    public function __construct(NotifikasiService $notifService)
    {
        $this->notifService = $notifService;
    }

    public function index(Request $request)
    {
        $query = Notifikasi::where('user_id', auth()->id())->latest();
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        $notifikasis = $query->paginate(15);
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function markRead($id)
    {
        Notifikasi::where('user_id', auth()->id())->findOrFail($id);
        $this->notifService->tandaiDibaca($id);
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return back()->with('status', 'Notifikasi ditandai dibaca.');
    }

    public function markAllRead()
    {
        $this->notifService->tandaiSemuaDibaca(auth()->id());
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return back()->with('status', 'Semua notifikasi ditandai telah dibaca.');
    }

    public function getCount()
    {
        $count = $this->notifService->getUnread(auth()->id());
        return response()->json(['unread' => $count]);
    }
}
