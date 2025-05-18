<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Cetak Laporan</title>
</head>
<body>
    <h2>🗓️ Pilih Bulan & Tahun untuk Cetak Laporan</h2>

    <form method="GET" action="{{ route('cetak.index') }}">
        <label>Bulan:</label>
        <select name="bulan">
            @foreach(range(1,12) as $b)
                <option value="{{ sprintf('%02d', $b) }}" {{ $bulan == sprintf('%02d', $b) ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                </option>
            @endforeach
        </select>

        <label>Tahun:</label>
        <select name="tahun">
            @for($t = date('Y') - 5; $t <= date('Y'); $t++)
                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endfor
        </select>

        <button type="submit">🔍 Tampilkan</button>
        @if(count($pendapatan) || count($pengeluaran))
        <a href="{{ route('cetak.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank">
            🖨️ Cetak PDF
        </a>
        @endif
    </form>

    @if(count($pendapatan) || count($pengeluaran))
    <h3>📋 Data Terverifikasi</h3>
    <ul>
        <li>Pendapatan: {{ count($pendapatan) }} item</li>
        <li>Pengeluaran: {{ count($pengeluaran) }} item</li>
    </ul>
    @else
        <p>🔕 Tidak ada data terverifikasi untuk bulan ini.</p>
    @endif
</body>
</html>
