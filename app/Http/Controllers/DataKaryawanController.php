<?php

namespace App\Http\Controllers;

use App\Models\DataKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataKaryawanController extends Controller
{
    public function index()
    {
        // Mengambil data karyawan
        $karyawans = DataKaryawan::orderBy('id', 'desc')->get();
    
        // Mengembalikan view dengan data karyawan
        return view('datakaryawan', compact('karyawans'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nik' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'nomor_telepon' => 'required|string|max:20',
            'gaji' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Mengupload foto
        $fotoName = null;
        if ($request->hasFile('foto')) {
            $fotoName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/foto_karyawan', $fotoName);
        }

        try {
            // Menyimpan data ke database
            DataKaryawan::create([
                'nama_karyawan'   => $request->nama_karyawan,
                'nik'             => $request->nik,
                'jabatan'         => $request->jabatan,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'tempat_lahir'    => $request->tempat_lahir,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'alamat'          => $request->alamat,
                'email'           => $request->email,
                'nomor_telepon'   => $request->nomor_telepon,
                'gaji'            => $request->gaji,
                'tanggal_masuk'   => $request->tanggal_masuk,
                'foto'            => $fotoName,
            ]);
            
            // Jika berhasil
            return redirect()->route('datakaryawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika ada error saat penyimpanan data
            return redirect()->route('datakaryawan.index')->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        //Menampilkan form untuk menambah data karyawan
        return view('datakaryawan_input');
    }

    public function edit($id)
    {
        $karyawan = DataKaryawan::findOrFail($id);
        return view('datakaryawan_input', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = DataKaryawan::findOrFail($id);

        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nik' => 'required|string|max:100|unique:datakaryawan,nik,' . $karyawan->id,
            'jabatan' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'email' => 'required|email|unique:datakaryawan,email,' . $karyawan->id,
            'nomor_telepon' => 'required|string|max:20',
            'gaji' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($karyawan->foto && Storage::exists('public/foto_karyawan/' . $karyawan->foto)) {
                Storage::delete('public/foto_karyawan/' . $karyawan->foto);
            }
            $fotoName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/foto_karyawan', $fotoName);
            $karyawan->foto = $fotoName;
        }

        try {
            // Update data
            $karyawan->update([
                'nama_karyawan'   => $request->nama_karyawan,
                'nik'             => $request->nik,
                'jabatan'         => $request->jabatan,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'tempat_lahir'    => $request->tempat_lahir,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'alamat'          => $request->alamat,
                'email'           => $request->email,
                'nomor_telepon'   => $request->nomor_telepon,
                'gaji'            => $request->gaji,
                'tanggal_masuk'   => $request->tanggal_masuk,
            ]);
            
            return redirect()->route('datakaryawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('datakaryawan.index')->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $karyawan = DataKaryawan::findOrFail($id);

        if ($karyawan->foto && Storage::exists('public/foto_karyawan/' . $karyawan->foto)) {
            Storage::delete('public/foto_karyawan/' . $karyawan->foto);
        }

        try {
            $karyawan->delete();
            return redirect()->route('datakaryawan.index')->with('success', 'Data karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('datakaryawan.index')->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}