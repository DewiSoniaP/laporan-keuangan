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

        // Cari data paling awal yang belum diverifikasi
        $earliestUnverified = Pendapatan::where('is_verified', false)
            ->orderBy('tanggal', 'asc')
            ->orderBy('idPendapatan', 'asc')
            ->first();

        $earliestUnverifiedId = $earliestUnverified ? $earliestUnverified->idPendapatan : null;

        return view('pendapatan', compact('pendapatan', 'tanggal', 'status', 'earliestUnverifiedId'));
    }

    public function store(Request $request)
    {
        // Set is_verified ke 0 otomatis saat input baru
        $data = $request->all();
        $data['is_verified'] = 0;

        Pendapatan::create($data);

        return redirect()->route('pendapatan.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = Pendapatan::findOrFail($id);

        // Simpan data lama untuk dibandingkan
        $oldData = $data->toArray();

        // Update data dengan input baru
        $data->update($request->all());

        // Cek perubahan atribut selain 'is_verified' dan 'updated_at'
        $changedAttributes = $data->getChanges();
        unset($changedAttributes['is_verified'], $changedAttributes['updated_at']);

        // Jika sebelumnya sudah terverifikasi dan ada perubahan data, reset verifikasi
        if ($oldData['is_verified'] && count($changedAttributes) > 0) {
            $data->is_verified = false;

            // Jika kolom verified_by dan verified_at ada, reset juga:
            if (isset($data->verified_by)) {
                $data->verified_by = null;
            }
            if (isset($data->verified_at)) {
                $data->verified_at = null;
            }

            $data->save();
        }

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

        if (!in_array($user->role, ['admin', 'validator'])) {
            abort(403, 'Unauthorized');
        }

        $data = Pendapatan::findOrFail($id);

        // Cari data pendapatan belum diverifikasi yang paling awal (tanggal asc, id asc)
        $earliestUnverified = Pendapatan::where('is_verified', false)
            ->orderBy('tanggal', 'asc')
            ->orderBy('idPendapatan', 'asc') // pakai idPendapatan karena primary key custom
            ->first();

        if (!$earliestUnverified) {
            // Semua sudah diverifikasi, boleh lanjut
        } else {
            // Jika data yang akan diverifikasi bukan data paling awal
            if ($earliestUnverified->idPendapatan != $data->idPendapatan) {
                return redirect()->route('pendapatan.index')
                    ->with('error', 'Validasi harus dilakukan secara berurutan, ada data sebelumnya yang belum divalidasi.');
            }
        }

        // Lakukan validasi
        $data->is_verified = true;
        $data->save();

        return redirect()->route('pendapatan.index')->with('success', 'Data berhasil divalidasi.');
    }
}
