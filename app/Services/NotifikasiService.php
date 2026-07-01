<?php

namespace App\Services;

use App\Models\Notifikasi;

class NotifikasiService
{
    public function kirim($userId, $judul, $pesan, $jenis = 'info')
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'title' => $judul,
            'message' => $pesan,
            'type' => $jenis,
            'is_read' => false
        ]);
    }

    public function kirimBulk($userIds, $judul, $pesan, $jenis = 'info')
    {
        $data = [];
        $now = now();
        foreach ($userIds as $id) {
            $data[] = [
                'user_id' => $id,
                'title' => $judul,
                'message' => $pesan,
                'type' => $jenis,
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        return Notifikasi::insert($data);
    }

    public function tandaiDibaca($notifikasiId)
    {
        return Notifikasi::where('id', $notifikasiId)->update(['is_read' => true]);
    }

    public function tandaiSemuaDibaca($userId)
    {
        return Notifikasi::where('user_id', $userId)->where('is_read', false)->update(['is_read' => true]);
    }

    public function getUnread($userId)
    {
        return Notifikasi::where('user_id', $userId)->where('is_read', false)->count();
    }
}
