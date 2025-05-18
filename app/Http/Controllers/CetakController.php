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
        $endDate = date("Y-m-t", strtotime($startDate));

        $pendapatan = Pendapatan::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $pengeluaran = Pengeluaran::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        return view('cetak', compact('pendapatan', 'pengeluaran', 'bulan', 'tahun'));
    }

    public function exportPDF(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $startDate = "$tahun-$bulan-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $pendapatan = Pendapatan::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $pengeluaran = Pengeluaran::where('is_verified', 1)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $namaUser = auth()->user()->name ?? 'User';

        $pdf = Pdf::loadView('laporan_pdf', compact(
            'pendapatan',
            'pengeluaran',
            'bulan',
            'tahun',
            'namaUser'
        ));

        return $pdf->stream("laporan-keuangan-$bulan-$tahun.pdf");
    }
}
