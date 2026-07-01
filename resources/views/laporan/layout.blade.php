<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Laporan SPMI' }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; color: #333; }
        .watermark { position: fixed; top: 35%; left: 10%; transform: rotate(-45deg); opacity: 0.08; font-size: 90px; font-weight: bold; color: #000; z-index: -1000; text-align: center; width: 100%; white-space: nowrap; }
        .header { width: 100%; border-bottom: 2px solid #111; padding-bottom: 10px; margin-bottom: 15px; text-align: center; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; font-weight: bold; }
        .header h2 { margin: 5px 0 0 0; font-size: 13px; font-weight: normal; color: #555; }
        .header .doc-title { margin-top: 15px; font-size: 14px; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #777; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 30px; font-size: 9px; border-top: 1px solid #ddd; padding-top: 5px; color: #777; }
        .page-number:after { content: counter(page); }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; color: white; }
        .bg-green { background-color: #10B981; }
        .bg-red { background-color: #EF4444; }
        .bg-yellow { background-color: #F59E0B; }
        .bg-blue { background-color: #3B82F6; }
        .bg-gray { background-color: #6B7280; }
    </style>
</head>
<body>
    <div class="watermark">KONFIDENSIAL</div>
    <div class="header">
        <h1>Lembaga Penjaminan Mutu Internal</h1>
        <h2>Sistem Penjaminan Mutu Internal (SPMI)</h2>
        <div class="doc-title">{{ $title ?? 'LAPORAN' }}</div>
        <h2>Tahun Akademik: {{ $periode->name }}</h2>
    </div>
    
    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="border: none; padding: 0;">Dicetak oleh: {{ auth()->user()->name ?? 'System' }} | Waktu: {{ now()->format('d M Y H:i:s') }}</td>
                <td style="border: none; padding: 0; text-align: right;">Halaman <span class="page-number"></span></td>
            </tr>
        </table>
    </div>
</body>
</html>
