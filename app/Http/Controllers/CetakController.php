<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CetakController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        $startDate = "$tahun-$bulan-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $pendapatan = Pendapatan::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $pengeluaran = Pengeluaran::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        return view('cetak', compact('pendapatan', 'pengeluaran', 'bulan', 'tahun'));
    }

    public function cetakPDF(Request $request)
    {
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        $startDate = "$tahun-$bulan-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $pendapatan = Pendapatan::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $pengeluaran = Pengeluaran::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

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

        usort($semuaData, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

        $totalDebit = array_sum(array_column($semuaData, 'debit'));
        $totalKredit = array_sum(array_column($semuaData, 'kredit'));
        $saldoAkhir = $totalDebit - $totalKredit;

        $namaBulan = Carbon::createFromFormat('m', $bulan)->locale('id')->translatedFormat('F');
        $tanggalCetak = Carbon::now()->format('d/m/Y');
        $namaUser = auth()->user()->name ?? 'Administrator';

        $pdf = PDF::loadView('laporan_pdf', compact(
            'semuaData', 'bulan', 'tahun', 'totalDebit', 'totalKredit', 'saldoAkhir', 'namaBulan', 'tanggalCetak', 'namaUser'
        ))->setPaper('a4', 'portrait');

        $pdf->getDomPDF()->getCanvas()->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Halaman: $pageNumber dari $pageCount";
            $font = $fontMetrics->getFont('helvetica', 'normal');
            $canvas->text(500, 820, $text, $font, 10);
        });

        return $pdf->stream("Laporan-Keuangan-{$bulan}-{$tahun}.pdf");
    }

    public function detailData(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'pendapatan') {
            $data = Pendapatan::where('idPendapatan', $id)
                ->where('is_verified', 1)
                ->first();

            if (!$data) {
                return response()->json(['error' => 'Data pendapatan tidak ditemukan'], 404);
            }

            return response()->json([
                'tanggal' => $data->tanggal,
                'namaPasien' => $data->namaPasien,
                'diagnose' => $data->diagnose,
                'jenisKunjungan' => $data->jenisKunjungan,
                'jasa' => $data->jasa,
            ]);
        } elseif ($type === 'pengeluaran') {
            $data = Pengeluaran::where('idPengeluaran', $id)
                ->where('is_verified', 1)
                ->first();

            if (!$data) {
                return response()->json(['error' => 'Data pengeluaran tidak ditemukan'], 404);
            }

            return response()->json([
                'tanggal' => $data->tanggal,
                'keterangan' => $data->keterangan,
                'jumlahPengeluaran' => $data->jumlahPengeluaran,
            ]);
        }

        return response()->json(['error' => 'Tipe data tidak valid'], 400);
    }

public function cetakExcel(Request $request)
{
    $bulan = $request->input('bulan') ?? date('m');
    $tahun = $request->input('tahun') ?? date('Y');

    $startDate = "$tahun-$bulan-01";
    $endDate = date('Y-m-t', strtotime($startDate));

    $pendapatan = Pendapatan::where('is_verified', 1)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->get();

    $pengeluaran = Pengeluaran::where('is_verified', 1)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->get();

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

    usort($semuaData, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Nama bulan
$namaBulan = Carbon::createFromFormat('m', $bulan)->locale('id')->translatedFormat('F');

// Judul di baris 1
$sheet->mergeCells('A1:D1');
$sheet->setCellValue('A1', "Laporan Keuangan Bulanan - {$namaBulan} {$tahun}");
$sheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 16],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
]);

// Header kolom mulai di baris ke-3
$sheet->setCellValue('A3', 'Tanggal');
$sheet->setCellValue('B3', 'Keterangan');
$sheet->setCellValue('C3', 'Debit (Rp)');
$sheet->setCellValue('D3', 'Kredit (Rp)');

$sheet->getStyle('A3:D3')->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFCCE5FF']
    ],
    'borders' => [
        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    ],
]);

// Auto-size kolom
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Isi data mulai dari baris 4
$row = 4;

    $totalDebit = 0;
    $totalKredit = 0;

    foreach ($semuaData as $data) {
        $sheet->setCellValue("A$row", Carbon::parse($data['tanggal'])->format('d/m/Y'));
        $sheet->setCellValue("B$row", $data['keterangan']);
        $sheet->setCellValue("C$row", $data['debit']);
        $sheet->setCellValue("D$row", $data['kredit']);

        $totalDebit += $data['debit'] ?? 0;
        $totalKredit += $data['kredit'] ?? 0;
        $row++;
    }

    // Total dan saldo akhir
    $sheet->setCellValue("B$row", 'Total');
    $sheet->setCellValue("C$row", $totalDebit);
    $sheet->setCellValue("D$row", $totalKredit);
    $row++;
    $sheet->setCellValue("B$row", 'Saldo Akhir');
    $sheet->setCellValue("C$row", $totalDebit - $totalKredit);

    // Download ke browser
    $filename = "Laporan-Keuangan-{$bulan}-{$tahun}.xlsx";
    $writer = new Xlsx($spreadsheet);

    // Bersihkan semua output buffer yang mungkin aktif
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Kirim response download
    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
    ]);

    }
}
