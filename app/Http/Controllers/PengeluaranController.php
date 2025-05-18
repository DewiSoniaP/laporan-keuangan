<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        return view('pengeluaran', compact('bulan', 'tahun'));
    }

    public function inputForm(Request $request)
    {
        $tanggal = date('Y-m-d');
        return view('pengeluaran_form', compact('tanggal'));
    }

    public function store(Request $request)
    {
        // Validasi input data
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keperluan_pengeluaran' => 'required|string|max:255',
            'jumlah_pengeluaran' => 'required|numeric',
            'keterangan' => 'required|string|max:255',
        ]);
    
        try {
            // Membuat objek Pengeluaran baru dan menyimpannya ke database
            $pengeluaran = new Pengeluaran();
            $pengeluaran->tanggal = $request->tanggal;
            $pengeluaran->keperluanPengeluaran = $request->keperluan_pengeluaran;
            $pengeluaran->jumlahPengeluaran = $request->jumlah_pengeluaran;
            $pengeluaran->keterangan = $request->keterangan;
            $pengeluaran->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function showData(Request $request)
    {
        $bulan = str_pad($request->query('bulan'), 2, '0', STR_PAD_LEFT);
        $tahun = $request->query('tahun');
        $tanggal = $request->query('tanggal');
    
        if (!$bulan || !$tahun) {
            return redirect()->route('pengeluaran.index')->with('error', 'Bulan dan tahun wajib dipilih!');
        }
    
        // Ambil data pengeluaran berdasarkan bulan dan tahun
        $query = Pengeluaran::whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun);
    
        // Jika ada tanggal yang dipilih, filter berdasarkan tanggal tersebut
        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);  // Filter berdasarkan tanggal
        }
    
        // Ambil data yang telah difilter
        $pengeluaran = $query->get();

        // Kirim data ke view pengeluaran_data
        return view('pengeluaran_data', compact('bulan', 'tahun', 'tanggal', 'pengeluaran'));
    }
    
    public function update(Request $request, $id)
{
    $request->validate([
        'keperluan_pengeluaran' => 'required|string|max:255',
        'jumlah_pengeluaran' => 'required|numeric',
        'tanggal' => 'required|date',
        'keterangan' => 'required|string|max:255',
    ]);
    
    $pengeluaran = Pengeluaran::findOrFail($id);
    $pengeluaran->keperluanPengeluaran = $request->input('keperluan_pengeluaran');
    $pengeluaran->jumlahPengeluaran = $request->input('jumlah_pengeluaran');
    $pengeluaran->tanggal = $request->input('tanggal');
    $pengeluaran->keterangan = $request->input('keterangan');

    // FIX: reset verifikasi jika data diubah
    $pengeluaran->is_verified = false;

    $pengeluaran->save();
    
    return response()->json([
        'success' => true,
        'updatedData' => $pengeluaran
    ]);
}

    public function destroy(Request $request, $id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->delete();
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            // fallback jika bukan ajax (misal akses biasa)
            return redirect()->route('pengeluaran.show', [
                'bulan' => date('m', strtotime($request->tanggal)),
                'tahun' => date('Y', strtotime($request->tanggal))
                ])->with('success', 'Data pengeluaran berhasil dihapus!');
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()]);
                }
                return back()->with('error', 'Gagal menghapus data.');
            }
        }

    public function verifikasi(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pengeluaran,idPengeluaran',
            'bulan' => 'required',
            'tahun' => 'required',
            'tanggal' => 'required|date',
        ]);

        $pengeluaran = Pengeluaran::findOrFail($request->id);
        $pengeluaran->is_verified = true;
        $pengeluaran->save();

        return redirect()->route('pengeluaran.show', [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal' => $request->tanggal,
        ])->with('success', 'Data berhasil diverifikasi!');
    }
}