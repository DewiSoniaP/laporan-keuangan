<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
    
        $namaBulan = \Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->translatedFormat('F');
        $tanggalCetak = \Carbon\Carbon::now()->format('d/m/Y');
        $namaUser = auth()->user()->name ?? 'Administrator';
    
        $pdf = PDF::loadView('laporan_pdf', compact(
            'semuaData', 'bulan', 'tahun', 'totalDebit', 'totalKredit', 'saldoAkhir', 'namaBulan', 'tanggalCetak', 'namaUser'
        ))->setPaper('a4', 'portrait');
        
        // Tambahkan page number lewat page_script
        $pdf->getDomPDF()->getCanvas()->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Halaman: $pageNumber dari $pageCount";
            $font = $fontMetrics->getFont('helvetica', 'normal');
            $canvas->text(500, 820, $text, $font, 10);
        });
        
        return $pdf->stream("Laporan-Keuangan-{$bulan}-{$tahun}.pdf");
        
    }
    
}