<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        @page {
            margin: 50px 30px;
        }
        body {
            font-family: sans-serif;
            font-size: 12px;
            position: relative;
        }
        header {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        tfoot td {
            font-weight: bold;
        }
        footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 10px;
            color: #444;
        }
        .footer-left {
            float: left;
        }
        .footer-right {
            float: right;
        }
    </style>
</head>
<body>

<header>
    <h2>Laporan Keuangan Bulanan - {{ $namaBulan }} {{ $tahun }}</h2>
</header>

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
        @foreach($semuaData as $data)
            <tr>
                <td>{{ \Carbon\Carbon::parse($data['tanggal'])->format('d/m/Y') }}</td>
                <td>{{ $data['keterangan'] }}</td>
                <td>{{ $data['debit'] ? number_format($data['debit'], 0, ',', '.') : '-' }}</td>
                <td>{{ $data['kredit'] ? number_format($data['kredit'], 0, ',', '.') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Total</td>
            <td>{{ number_format($totalDebit, 0, ',', '.') }}</td>
            <td>{{ number_format($totalKredit, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2">Saldo Akhir</td>
            <td colspan="2">{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

<footer>
    <div class="footer-left">
        Dicetak pada {{ $tanggalCetak }} oleh {{ $namaUser }}
    </div>
</footer>


</body>
</html>
