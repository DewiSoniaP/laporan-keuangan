<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan Bulanan - {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h2>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Debit (Rp)</th>
                <th>Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDebit = 0;
                $totalKredit = 0;
            @endphp

            @foreach($pendapatan as $data)
            <tr>
                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $data->keterangan ?? '-' }}</td>
                <td>{{ number_format($data->jumlah, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            @php $totalDebit += $data->jumlah; @endphp
            @endforeach

            @foreach($pengeluaran as $data)
            <tr>
                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $data->keterangan ?? '-' }}</td>
                <td></td>
                <td>{{ number_format($data->jumlah, 0, ',', '.') }}</td>
            </tr>
            @php $totalKredit += $data->jumlah; @endphp
            @endforeach

            <tr>
                <th colspan="2">Total</th>
                <th>{{ number_format($totalDebit, 0, ',', '.') }}</th>
                <th>{{ number_format($totalKredit, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="2">Saldo Akhir</th>
                <th colspan="2">{{ number_format($totalDebit - $totalKredit, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 40px;">Dicetak oleh: {{ $namaUser }}</p>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
</body>
</html>
