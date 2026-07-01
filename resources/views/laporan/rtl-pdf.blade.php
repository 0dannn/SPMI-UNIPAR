@extends('laporan.layout', ['title' => 'Rekapitulasi Rencana Tindak Lanjut (RTL)', 'periode' => $periode])

@section('content')
    <div style="margin-bottom: 10px;">
        <strong>Filter Status:</strong> {{ $request->status ? $request->status : 'Semua Status' }}<br>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Unit Pelaksana</th>
                <th width="10%">Temuan</th>
                <th width="30%">Tindakan Korektif / Rencana Perbaikan</th>
                <th width="15%">Pembuat (PJ)</th>
                <th width="10%">Target Waktu</th>
                <th width="10%">Status Saat Ini</th>
                <th width="10%">Verifikasi LPM</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rtls as $rtl)
                <tr>
                    <td><strong>{{ $rtl->auditAmi->jadwal->unit->name }}</strong></td>
                    <td>{{ $rtl->auditAmi->pengukuran->indikator->standar->code }} <br> ({{ $rtl->auditAmi->finding_type }})</td>
                    <td>{{ $rtl->description }}</td>
                    <td>{{ $rtl->auditee->name ?? '-' }}</td>
                    <td class="text-center">{{ $rtl->target_date->format('d M Y') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $rtl->status == 'Selesai' ? 'bg-green' : ($rtl->status == 'Berjalan' ? 'bg-yellow' : 'bg-gray') }}">
                            {{ strtoupper($rtl->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($rtl->auditor_validation)
                            <strong style="color: green;">SAH</strong>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada catatan dokumen Tindak Lanjut (RTL).</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
