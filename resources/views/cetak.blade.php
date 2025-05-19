<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <div class="max-w-5xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
            🗓️ <span>Pilih Bulan & Tahun untuk Cetak Laporan</span>
        </h2>

        <form method="GET" action="{{ route('cetak.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan:</label>
                    <div class="mt-1 w-full rounded-md border shadow-sm px-2 py-1">
                        <select name="bulan" id="bulan" class="w-full outline-none">
                            @foreach(range(1,12) as $b)
                                <option value="{{ sprintf('%02d', $b) }}" {{ $bulan == sprintf('%02d', $b) ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun:</label>
                    <div class="mt-1 w-full rounded-md border shadow-sm px-2 py-1">
                        <select name="tahun" id="tahun" class="w-full outline-none">
                        @for($t = date('Y') - 5; $t <= date('Y'); $t++)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    🔍 Tampilkan
                </button>

                @if(count($pendapatan) || count($pengeluaran))
                <a href="{{ route('cetak.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                   target="_blank"
                   class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                    🖨️ Cetak PDF
                </a>
                @endif
            </div>
        </form>

        <div class="mt-8">
            <h3 class="text-lg font-semibold flex items-center gap-2 mb-4">📋 <span>Data Terverifikasi</span></h3>

            @if(count($pendapatan) || count($pengeluaran))
                @php
                    $semuaData = [];

                    foreach ($pendapatan as $p) {
                        $semuaData[] = [
                            'tanggal' => $p->tanggal,
                            'keterangan' => $p->diagnose . ' (' . $p->jenisKunjungan . ')',
                            'debit' => $p->jasa,
                            'kredit' => null
                        ];
                    }

                    foreach ($pengeluaran as $e) {
                        $semuaData[] = [
                            'tanggal' => $e->tanggal,
                            'keterangan' => $e->keterangan,
                            'debit' => null,
                            'kredit' => $e->jumlahPengeluaran
                        ];
                    }

                    // Urutkan berdasarkan tanggal
                    usort($semuaData, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));
                @endphp

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-300 rounded shadow-sm">
                        <thead class="bg-gray-100 text-sm font-semibold text-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Tanggal</th>
                                <th class="px-4 py-2 border">Keterangan</th>
                                <th class="px-4 py-2 border">Debit (Rp)</th>
                                <th class="px-4 py-2 border">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm bg-white">
                            @foreach($semuaData as $data)
                                <tr>
                                    <td class="px-4 py-2 border text-center">{{ \Carbon\Carbon::parse($data['tanggal'])->format('d/m/y') }}</td>
                                    <td class="px-4 py-2 border text-center">{{ $data['keterangan'] }}</td>
                                    <td class="px-4 py-2 border text-center">
                                        {{ $data['debit'] ? number_format($data['debit'], 0, ',', '.') : '' }}
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        {{ $data['kredit'] ? number_format($data['kredit'], 0, ',', '.') : '' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 mt-4">🔕 Tidak ada data terverifikasi untuk bulan ini.</p>
            @endif
        </div>
    </div>

</body>
</html>
