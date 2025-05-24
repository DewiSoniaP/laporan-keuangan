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

    /**
     * Validasi data pendapatan secara berurutan.
     */
    public function validateData($id)
    {
        $user = auth()->user();

        // Cek apakah user memiliki role admin atau user
        if (!in_array($user->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized');
        }

        $data = Pendapatan::findOrFail($id);

        // Cek apakah ada data pendapatan sebelumnya yang belum divalidasi
        $pendingBefore = Pendapatan::where('id', '<', $id)
            ->where('is_verified', false)
            ->count();

        if ($pendingBefore > 0) {
            return redirect()->route('pendapatan.index')
                ->with('error', 'Validasi harus dilakukan secara berurutan, ada data sebelumnya yang belum divalidasi.');
        }

        // Validasi data
        $data->is_verified = true;
        $data->verified_by = $user->id; // pastikan ada kolom verified_by di tabel pendapatan
        $data->verified_at = now();      // dan kolom verified_at
        $data->save();

        return redirect()->route('pendapatan.index')->with('success', 'Data berhasil divalidasi.');
    }
}
