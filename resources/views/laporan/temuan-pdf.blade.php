@extends('laporan.layout', ['title' => 'Laporan Temuan Audit Mutu Internal (AMI)', 'periode' => $periode])

@section('content')
    <p style="text-align: justify; margin-bottom: 15px;">
        Berikut adalah daftar temuan Ketidaksesuaian (KTS) dan Observasi (OB) yang ditemukan selama proses Audit Mutu Internal untuk periode {{ $periode->name }}. Temuan di bawah ini wajib segera ditindaklanjuti oleh masing-masing unit Auditee.
    </p>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Unit Auditee</th>
                <th width="10%">Kode Standar</th>
                <th width="10%">Jenis Temuan</th>
                <th width="30%">Deskripsi Temuan (Catatan Auditor)</th>
                <th width="15%">Status Tindak Lanjut</th>
                <th width="15%">Target Penyelesaian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($temuans as $index => $t)
                @php
                    $rtl = $t->rtmRtl;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $t->jadwal->unit->name }}</strong></td>
                    <td>{{ $t->pengukuran->indikator->standar->code }}</td>
                    <td class="text-center">
                        <span class="badge {{ $t->finding_type == 'KTS' ? 'bg-red' : 'bg-yellow' }}">{{ $t->finding_type }}</span>
                    </td>
                    <td>{{ $t->description }}</td>
                    <td class="text-center">
                        @if(!$rtl)
                            <span class="badge bg-red">Belum Ada RTL</span>
                        @else
                            <span class="badge {{ $rtl->status == 'Selesai' ? 'bg-green' : 'bg-blue' }}">{{ $rtl->status }}</span>
                            @if($rtl->auditor_validation)
                                <br><small style="color:green;">&#10003; Terverifikasi</small>
                            @endif
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $rtl ? $rtl->target_date->format('d M Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Luar biasa! Tidak ada temuan KTS maupun OB pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
