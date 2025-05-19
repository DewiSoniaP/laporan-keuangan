<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $status = $request->query('status');

        $query = Pendapatan::query();

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status === 'verified') {
            $query->where('is_verified', true);
        } elseif ($status === 'unverified') {
            $query->where('is_verified', false);
        }

        $pendapatan = $query->orderBy('tanggal', 'desc')->get();

        return view('pendapatan', compact('pendapatan', 'tanggal', 'status'));
    }

    public function store(Request $request)
    {
        Pendapatan::create($request->all());
        return redirect()->route('pendapatan.index')->with('success', 'Data berhasil ditambahkan.');
    }
    
    public function update(Request $request, $id)
    {
        $data = Pendapatan::findOrFail($id);
        $data->update($request->all());
        return redirect()->route('pendapatan.index')->with('success', 'Data berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        Pendapatan::findOrFail($id)->delete();
        return redirect()->route('pendapatan.index')->with('success', 'Data berhasil dihapus.');
    }
    
}
