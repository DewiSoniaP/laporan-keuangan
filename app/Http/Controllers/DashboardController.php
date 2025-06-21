<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $pendapatan = 0;
        $pengeluaran = 0;
        $chartData = [];
        $trendDiagnose = [];
        $trendPengeluaran = [];

        if ($bulan && $tahun) {
            // Hitung total pendapatan dari kolom `jasa`
            $pendapatan = Pendapatan::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jasa');

            // Hitung total pengeluaran dari kolom `jumlahPengeluaran`
            $pengeluaran = Pengeluaran::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlahPengeluaran');

            // Data chart mingguan (contoh)
            $chartData = [
                Pendapatan::whereBetween('tanggal', [
                    sprintf('%04d-%02d-01', $tahun, $bulan),
                    sprintf('%04d-%02d-07', $tahun, $bulan)
                ])->sum('jasa'),
                Pendapatan::whereBetween('tanggal', [
                    sprintf('%04d-%02d-08', $tahun, $bulan),
                    sprintf('%04d-%02d-15', $tahun, $bulan)
                ])->sum('jasa'),
                Pendapatan::whereBetween('tanggal', [
                    sprintf('%04d-%02d-16', $tahun, $bulan),
                    sprintf('%04d-%02d-23', $tahun, $bulan)
                ])->sum('jasa'),
                Pendapatan::whereBetween('tanggal', [
                    sprintf('%04d-%02d-24', $tahun, $bulan),
                    sprintf('%04d-%02d-31', $tahun, $bulan)
                ])->sum('jasa'),
            ];

            // Ambil trend diagnose (jenis kunjungan yang paling sering)
            $trendDiagnose = DB::table('pendapatan')
                ->select('diagnose', DB::raw('count(*) as total'))
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->groupBy('diagnose')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // Ambil trend pengeluaran terbesar berdasarkan keterangan
            $trendPengeluaran = DB::table('pengeluaran')
                ->select('keterangan', DB::raw('SUM(jumlahPengeluaran) as total'))
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->groupBy('keterangan')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact(
            'pendapatan',
            'pengeluaran',
            'chartData',
            'trendDiagnose',
            'trendPengeluaran'
        ));
    }

    public function statusVerifikasi()
    {
        $laporan = Pengeluaran::where('is_verified', 1)->get(['bulan', 'tahun', 'tanggal', 'is_verified']);
        return view('status_verifikasi', compact('laporan'));
    }
}
