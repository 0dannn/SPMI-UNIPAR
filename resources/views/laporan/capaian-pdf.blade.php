@extends('laporan.layout', ['title' => 'Rekapitulasi Capaian Indikator Mutu', 'periode' => $periode])

@section('content')
    <div style="margin-bottom: 10px;">
        <strong>Unit Auditee:</strong> {{ $unit ? $unit->name : 'Semua Unit' }}<br>
        <strong>Status Data:</strong> Per Tanggal {{ now()->format('d M Y') }}
    </div>

    <table>
        <thead>
            <tr>
                @if(!$unit) <th width="15%">Unit Auditee</th> @endif
                <th width="10%">Kode Standar</th>
                <th width="25%">Indikator & Target LPM</th>
                <th width="25%">Capaian Riil (Unit)</th>
                <th width="15%">Skor Auditor</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengukurans as $p)
                @php
                    $audit = $p->auditAmis->first();
                    $status = 'Belum Dinilai';
                    $color = 'bg-gray';
                    if ($audit) {
                        $status = $audit->finding_type;
                        $color = $status == 'Sesuai' ? 'bg-green' : ($status == 'KTS' ? 'bg-red' : 'bg-yellow');
                    }
                @endphp
                <tr>
                    @if(!$unit) <td>{{ $p->unit->name }}</td> @endif
                    <td><strong>{{ $p->indikator->standar->code }}</strong></td>
                    <td>
                        {{ $p->indikator->description }}<br><br>
                        <em>Target: {{ $p->indikator->target }}</em>
                    </td>
                    <td>{{ $p->self_score ?? 'Tidak diisi' }}</td>
                    <td class="text-center">
                        @if($audit)
                            <strong>{{ $audit->auditor_score }}</strong> / 4<br><br>
                            <span class="badge {{ $color }}">{{ strtoupper($status) }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($p->status == 'submitted')
                            Disubmit
                        @else
                            Draft
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $unit ? 5 : 6 }}" class="text-center">Belum ada data capaian indikator.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
