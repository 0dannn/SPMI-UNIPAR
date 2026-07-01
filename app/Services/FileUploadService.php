<?php

namespace App\Services;

use App\Models\FileUpload;
use App\Models\Pengukuran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function upload(Request $request, Pengukuran $pengukuran, $fileKey = 'bukti_file')
    {
        if (!$request->hasFile($fileKey)) {
            return null;
        }

        $file = $request->file($fileKey);

        $validMimes = [
            'application/pdf', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
            'image/jpeg', 
            'image/png'
        ];
        
        $mime = $file->getMimeType();
        if (!in_array($mime, $validMimes)) {
            throw ValidationException::withMessages([
                $fileKey => 'MIME type file ditolak. Harus berupa PDF, DOCX, XLSX, JPG, atau PNG. Terdeteksi: ' . $mime
            ]);
        }
        
        if ($file->getSize() > 10240 * 1024) { // 10MB
            throw ValidationException::withMessages([
                $fileKey => 'Ukuran file melebihi batas maksimal 10MB.'
            ]);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        
        $periodeId = $pengukuran->indikator->standar->periode_id;
        $unitId = $pengukuran->unit_id;
        
        $path = $file->storeAs("bukti/{$periodeId}/{$unitId}", $filename, 'local');

        $fileUpload = new FileUpload([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'user_id' => auth()->id(),
        ]);
        
        $pengukuran->buktiFisiks()->save($fileUpload);

        return $path;
    }
}
