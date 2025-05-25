<!DOCTYPE html>
<html>
<head>
    <title>{{ isset($karyawan) ? 'Edit Data Karyawan' : 'Input Data Karyawan' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            background: white;
            padding: 30px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        .form-buttons {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-submit {
            background-color: #007bff;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ isset($karyawan) ? 'Form Edit Karyawan' : 'Form Tambah Karyawan' }}</h2>

        <form method="POST" action="{{ isset($karyawan) ? route('datakaryawan.update', $karyawan->id) : route('datakaryawan.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($karyawan))
                @method('PUT')
            @endif

            <label>Nama Karyawan</label>
            <input type="text" name="nama_karyawan" value="{{ old('nama_karyawan', $karyawan->nama_karyawan ?? '') }}" required>

            <label>NIK</label>
            <input type="text" name="nik" value="{{ old('nik', $karyawan->nik ?? '') }}" required>

            <label>Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan', $karyawan->jabatan ?? '') }}" required>

            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" required>
                <option value="">Pilih</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>

            </select>

            <label>Tempat Lahir</label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir ?? '') }}" required>

            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir ?? '') }}" required>

            <label>Alamat</label>
            <textarea name="alamat" rows="2" required>{{ old('alamat', $karyawan->alamat ?? '') }}</textarea>

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $karyawan->email ?? '') }}" required>

            <label>Nomor Telepon</label>
            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $karyawan->nomor_telepon ?? '') }}" required>

            <label>Gaji</label>
            <input type="number" name="gaji" value="{{ old('gaji', $karyawan->gaji ?? '') }}" required>

            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk ?? '') }}" required>

            <label>Foto</label>
            <input type="file" name="foto" accept="image/*">

            @if(isset($karyawan) && $karyawan->foto)
                <div style="margin-top: 10px;">
                    <img src="{{ asset('storage/foto_karyawan/' . $karyawan->foto) }}" width="100" height="100" style="object-fit: cover; border-radius: 5px;">
                </div>
            @endif

            <div class="form-buttons">
                <a href="{{ route('datakaryawan.index') }}" class="btn btn-back">Kembali</a>
                <button type="submit" class="btn btn-submit">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
