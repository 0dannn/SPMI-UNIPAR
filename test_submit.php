<?php
$unitId = 6;
$activePeriode = \App\Models\Periode::where('is_active', 1)->first();
$indikatorIds = \App\Models\Indikator::whereHas('standar', function($q) use ($activePeriode) {
    $q->where('periode_id', $activePeriode->id);
})->pluck('id');
$pengukurans = \App\Models\Pengukuran::whereIn('indikator_id', $indikatorIds)->where('unit_id', $unitId)->get();
echo 'P: ' . $pengukurans->count() . ' | I: ' . $indikatorIds->count() . "\n";
if ($pengukurans->count() < $indikatorIds->count()) {
    echo "FAILED CONDITION\n";
} else {
    echo "CONDITION PASSED\n";
}

try {
    $lpmUsers = \App\Models\User::role('LPM')->get();
    foreach($lpmUsers as $lpm) {
        \App\Models\Notifikasi::create([
            'user_id' => $lpm->id,
            'title' => 'Evaluasi Diri Disubmit',
            'message' => "Test msg",
            'is_read' => false
        ]);
        echo "Notif OK\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
exit;
