<?php
$unitId = 6;
$activePeriode = \App\Models\Periode::where('is_active', 1)->first();
$indikatorsQuery = \App\Models\Indikator::with(['standar', 'pengukurans' => function($q) use ($unitId) {
    $q->where('unit_id', $unitId);
}, 'pengukurans.buktiFisiks'])->whereHas('standar', function($q) use ($activePeriode) {
    $q->where('periode_id', $activePeriode->id);
});
$globalIndikators = $indikatorsQuery->get();
$globalTotal = $globalIndikators->count();
$globalFilled = $globalIndikators->filter(function($i) {
    return $i->pengukurans->isNotEmpty() && $i->pengukurans->first()->self_score !== null;
})->count();
echo 'Total: ' . $globalTotal . ' | Filled: ' . $globalFilled . "\n";
exit;
