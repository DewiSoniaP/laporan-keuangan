<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $status = $request->query('status');

        $query = pengeluaran::query();

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status === 'verified') {
            $query->where('is_verified', true);
        } elseif ($status === 'unverified') {
            $query->where('is_verified', false);
        }

        $pengeluaran = $query->orderBy('tanggal', 'desc')->get();

        return view('pengeluaran', compact('pengeluaran', 'tanggal', 'status'));
    }

    public function store(Request $request)
    {
        Pengeluaran::create($request->all());
        return redirect()->route('pengeluaran.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = Pengeluaran::findOrFail($id);
        $data->update($request->all());
        return redirect()->route('pengeluaran.index')->with('success', 'Data berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Data berhasil dihapus.');
    }


}