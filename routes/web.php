<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/super', [\App\Http\Controllers\DashboardController::class, 'superDashboard'])->name('super');
    Route::get('/admin', [\App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('admin')->middleware('role:Admin');
    Route::get('/lpm', [\App\Http\Controllers\DashboardController::class, 'lpmDashboard'])->name('lpm')->middleware('role:LPM');
    Route::get('/auditee', [\App\Http\Controllers\DashboardController::class, 'auditeeDashboard'])->name('auditee')->middleware('role:Auditee');
    Route::get('/auditor', [\App\Http\Controllers\DashboardController::class, 'auditorDashboard'])->name('auditor')->middleware('role:Auditor');
    Route::get('/pimpinan', [\App\Http\Controllers\DashboardController::class, 'pimpinanDashboard'])->name('pimpinan')->middleware('role:Pimpinan');
});

Route::get('/dashboard', function () {
    $roles = auth()->check() ? auth()->user()->roles->pluck('name')->toArray() : [];
    
    if (count($roles) > 1) {
        return redirect()->route('dashboard.super');
    }

    $role = $roles[0] ?? '';
    return match($role) {
        'Admin' => redirect()->route('dashboard.admin'),
        'LPM' => redirect()->route('dashboard.lpm'),
        'Auditee' => redirect()->route('dashboard.auditee'),
        'Auditor' => redirect()->route('dashboard.auditor'),
        'Pimpinan' => redirect()->route('dashboard.pimpinan'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

Route::middleware(['auth', 'role:Admin,LPM'])->prefix('standar')->name('standar.')->group(function () {
    Route::get('/', [\App\Http\Controllers\StandarController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\StandarController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\StandarController::class, 'store'])->name('store');
    Route::get('/{standar}/edit', [\App\Http\Controllers\StandarController::class, 'edit'])->name('edit');
    Route::put('/{standar}', [\App\Http\Controllers\StandarController::class, 'update'])->name('update');
    Route::delete('/{standar}', [\App\Http\Controllers\StandarController::class, 'destroy'])->name('destroy');
    Route::get('/{standar}', [\App\Http\Controllers\StandarController::class, 'show'])->name('show');
    
    // Indikator nested routes
    Route::post('/{standar}/indikator', [\App\Http\Controllers\IndikatorController::class, 'store'])->name('indikator.store');
    Route::put('/indikator/{indikator}', [\App\Http\Controllers\IndikatorController::class, 'update'])->name('indikator.update');
    Route::delete('/indikator/{indikator}', [\App\Http\Controllers\IndikatorController::class, 'destroy'])->name('indikator.destroy');
});

Route::middleware(['auth', 'role:Auditee'])->prefix('pengukuran')->name('pengukuran.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PengukuranController::class, 'index'])->name('index');
    Route::get('/{indikator}/edit', [\App\Http\Controllers\PengukuranController::class, 'edit'])->name('edit');
    Route::put('/{pengukuran}', [\App\Http\Controllers\PengukuranController::class, 'update'])->name('update');
    Route::post('/submit-final', [\App\Http\Controllers\PengukuranController::class, 'submitFinal'])->name('submitFinal');
    Route::delete('/file/{file}', [\App\Http\Controllers\PengukuranController::class, 'deleteFile'])->name('deleteFile');
});

// File Server Route
Route::middleware(['auth'])->get('/file/{id}', function ($id) {
    $file = \App\Models\FileUpload::findOrFail($id);
    $path = \Illuminate\Support\Facades\Storage::disk('local')->path($file->file_path);
    if (!file_exists($path)) abort(404, 'File not found');
    return response()->file($path);
})->name('file.show');

Route::middleware(['auth', 'role:Admin,LPM'])->prefix('jadwal-audit')->name('jadwal-audit.')->group(function () {
    Route::get('/', [\App\Http\Controllers\JadwalAuditController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\JadwalAuditController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\JadwalAuditController::class, 'store'])->name('store');
});

Route::middleware(['auth', 'role:Auditor'])->prefix('audit-ami')->name('audit-ami.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AuditAmiController::class, 'index'])->name('index');
    Route::get('/{jadwal_id}', [\App\Http\Controllers\AuditAmiController::class, 'show'])->name('show');
    Route::post('/{jadwal_id}/store', [\App\Http\Controllers\AuditAmiController::class, 'store'])->name('store');
    Route::post('/{jadwal_id}/submit', [\App\Http\Controllers\AuditAmiController::class, 'submit'])->name('submit');
});

Route::middleware(['auth', 'role:Auditee'])->prefix('banding')->name('banding.')->group(function () {
    Route::post('/{audit_ami_id}', [\App\Http\Controllers\KomentarAmiController::class, 'store'])->name('store');
});

// P3 Modul: Rencana Tindak Lanjut
Route::middleware(['auth'])->prefix('rtm-rtl')->name('rtm-rtl.')->group(function () {
    Route::get('/', [\App\Http\Controllers\RtmRtlController::class, 'index'])->name('index');
    Route::get('/{auditAmi}', [\App\Http\Controllers\RtmRtlController::class, 'show'])->name('show');
    Route::post('/{auditAmi}/store', [\App\Http\Controllers\RtmRtlController::class, 'store'])->name('store')->middleware('role:Auditee');
    Route::put('/{rtmRtl}/update', [\App\Http\Controllers\RtmRtlController::class, 'update'])->name('update')->middleware('role:Auditee');
    Route::post('/{rtmRtl}/verify', [\App\Http\Controllers\RtmRtlController::class, 'verify'])->name('verify')->middleware('role:Admin,LPM,Auditor');
});

// Modul Notifikasi
Route::middleware(['auth'])->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('index');
    Route::post('/mark-all', [\App\Http\Controllers\NotifikasiController::class, 'markAllRead'])->name('markAllRead');
    Route::post('/{id}/mark', [\App\Http\Controllers\NotifikasiController::class, 'markRead'])->name('markRead');
    Route::get('/count', [\App\Http\Controllers\NotifikasiController::class, 'getCount'])->name('count');
});

// Modul Audit Trail / Log Activity (Admin)
Route::middleware(['auth', 'role:Admin'])->prefix('log-activity')->name('log-activity.')->group(function () {
    Route::get('/', [\App\Http\Controllers\LogActivityController::class, 'index'])->name('index');
    Route::get('/export', [\App\Http\Controllers\LogActivityController::class, 'exportCsv'])->name('exportCsv');
});

// Modul Cetak Laporan (P4)
Route::middleware(['auth', 'role:Admin,LPM,Pimpinan'])->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/capaian/export', [\App\Http\Controllers\LaporanController::class, 'exportCapaian'])->name('export.capaian');
    Route::get('/temuan/export', [\App\Http\Controllers\LaporanController::class, 'exportTemuan'])->name('export.temuan');
    Route::get('/rtl/export', [\App\Http\Controllers\LaporanController::class, 'exportRtl'])->name('export.rtl');
});


// Periode Management
Route::middleware(['auth', 'role:Admin'])->prefix('periode')->name('periode.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PeriodeController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\PeriodeController::class, 'store'])->name('store');
    Route::post('/{id}/aktif', [\App\Http\Controllers\PeriodeController::class, 'setAktif'])->name('setAktif');
    Route::post('/{id}/kunci', [\App\Http\Controllers\PeriodeController::class, 'kunci'])->name('kunci');
    Route::post('/{id}/unlock', [\App\Http\Controllers\PeriodeController::class, 'unlock'])->name('unlock');
});

// Users Management
Route::middleware(['auth', 'role:Admin'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('store');
    Route::post('/{id}/toggle', [\App\Http\Controllers\UserController::class, 'toggleActive'])->name('toggleActive');
});

require __DIR__.'/auth.php';
