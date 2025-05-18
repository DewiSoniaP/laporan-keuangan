<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        return view('pendapatan', compact('bulan', 'tahun'));
    }

    public function inputForm(Request $request)
    {
        $tanggal = date('Y-m-d');
        return view('pendapatan_form', compact('tanggal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pasien' => 'required|string|max:255',
            'usia' => 'required|string|max:10',
            'nama_orangtua' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'diagnose' => 'required|string|max:255',
            'jenis_kunjungan' => 'required|string|max:255',
            'jasa' => 'required|string|max:100',
        ]);

        try {
            $pendapatan = new Pendapatan();
            $pendapatan->tanggal = $request->tanggal;
            $pendapatan->namaPasien = $request->nama_pasien;
            $pendapatan->usia = $request->usia;
            $pendapatan->namaKeluarga = $request->nama_orangtua;
            $pendapatan->alamat = $request->alamat;
            $pendapatan->diagnose = $request->diagnose;
            $pendapatan->jenisKunjungan = $request->jenis_kunjungan;
            $pendapatan->jasa = $request->jasa;
            $pendapatan->save();

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
            return redirect()->route('pendapatan.index')->with('error', 'Bulan dan tahun wajib dipilih!');
        }

        $query = Pendapatan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $pendapatan = $query->get();

        return view('pendapatan_data', compact('bulan', 'tahun', 'tanggal', 'pendapatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'diagnose' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jasa' => 'required|numeric|min:0',
        ]);

        $pendapatan = Pendapatan::findOrFail($id);
        $pendapatan->namaPasien = $request->nama_pasien;
        $pendapatan->diagnose = $request->diagnose;
        $pendapatan->tanggal = $request->tanggal;
        $pendapatan->jasa = $request->jasa;

        // Reset verifikasi jika data diubah
        $pendapatan->is_verified = false;

        $pendapatan->save();

        return response()->json([
            'success' => true,
            'updatedData' => $pendapatan
        ]);
    }

    public function destroy(Request $request, $id)
    {
        try {
            $pendapatan = Pendapatan::findOrFail($id);
            $pendapatan->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('pendapatan.show', [
                'bulan' => date('m', strtotime($request->tanggal)),
                'tahun' => date('Y', strtotime($request->tanggal))
            ])->with('success', 'Data pendapatan berhasil dihapus!');
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
            'id' => 'required|exists:pendapatan,idPendapatan',
            'bulan' => 'required',
            'tahun' => 'required',
            'tanggal' => 'required|date',
        ]);

        $pendapatan = Pendapatan::findOrFail($request->id);
        $pendapatan->is_verified = true;
        $pendapatan->save();

        return redirect()->route('pendapatan.show', [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal' => $request->tanggal,
        ])->with('success', 'Data berhasil diverifikasi!');
    }
}
