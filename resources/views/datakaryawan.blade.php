<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
        }

        .sidebar {
            width: 100%;
            background: linear-gradient(to bottom, #2f4cdd, #567df4);
            padding: 20px;
            height: 100vh;
            color: white;
            position: sticky;
            top: 0;
        }

        .sidebar h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            margin-bottom: 10px;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        .topbar {
            background-color: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .content {
            padding: 30px;
        }

        /* Sesuaikan ukuran foto agar rapi */
        .foto-karyawan {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Agar header tabel tidak terpecah baris */
        thead th {
            white-space: nowrap;
        }

        /* Contoh atur lebar minimum kolom yang biasanya panjang */
        th.nama-karyawan {
            min-width: 140px;
        }

        th.alamat {
            min-width: 180px;
        }

        th.email {
            min-width: 180px;
        }

        th.nomor-telepon {
            min-width: 140px;
        }

        /* Perlebar kolom Gaji dan buat teks tidak terpisah */
        th.gaji, td.gaji {
            min-width: 130px;
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <div class="container-fluid px-0">
        <div class="row m-0">
            <!-- Sidebar -->
            <div class="col-md-2 p-0">
                <div class="sidebar">
                    <h4>Laporan Keuangan</h4>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                    <a href="{{ route('pendapatan.index') }}" class="{{ request()->routeIs('pendapatan.index') ? 'active' : '' }}">📈 Pendapatan</a>
                    <a href="{{ route('pengeluaran.index') }}" class="{{ request()->routeIs('pengeluaran.index') ? 'active' : '' }}">📉 Pengeluaran</a>
                    <a href="{{ route('datakaryawan.index') }}" class="{{ request()->routeIs('datakaryawan.index') ? 'active' : '' }}">📋 Data Karyawan</a>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-10 p-0">
                <div class="topbar d-flex justify-content-between align-items-center">
                    <div><strong>Hallo, {{ Auth::user()->name }}</strong></div>
                    <div>
                        <a href="{{ route('logout') }}" class="btn btn-outline-primary btn-sm"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

                <div class="content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-secondary">Data Karyawan BPS Bidan Nining</h4>

                        @if(Auth::user()->role === 'validator')
                            <a href="{{ route('datakaryawan.create') }}" class="btn btn-primary">+ Tambah Karyawan</a>
                        @endif
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th class="nama-karyawan">Nama Karyawan</th>
                                    <th>NIK</th>
                                    <th>Jabatan</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th class="alamat">Alamat</th>
                                    <th class="email">Email</th>
                                    <th class="nomor-telepon">Nomor Telepon</th>
                                    <th class="gaji">Gaji</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($karyawans as $index => $karyawan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if ($karyawan->foto)
                                                <img src="{{ asset('storage/foto_karyawan/' . $karyawan->foto) }}" alt="Foto {{ $karyawan->nama_karyawan }}" class="foto-karyawan">
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>{{ $karyawan->nama_karyawan }}</td>
                                        <td>{{ $karyawan->nik }}</td>
                                        <td>{{ $karyawan->jabatan }}</td>
                                        <td>{{ $karyawan->jenis_kelamin }}</td>
                                        <td>{{ $karyawan->tempat_lahir }}</td>
                                        <td>{{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->translatedFormat('d M Y') }}</td>
                                        <td>{{ $karyawan->alamat }}</td>
                                        <td>{{ $karyawan->email ?? '-' }}</td>
                                        <td>{{ $karyawan->nomor_telepon ?? '-' }}</td>
                                        <td class="gaji">Rp {{ number_format($karyawan->gaji, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->translatedFormat('d M Y') }}</td>
                                        <td>
                                            @if(Auth::user()->role === 'validator')
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('datakaryawan.edit', $karyawan->id) }}" class="btn btn-success btn-sm">Edit</a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $karyawan->id }}">
                                                        Hapus
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center">Belum ada data karyawan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal konfirmasi hapus per karyawan -->
    @foreach ($karyawans as $karyawan)
        <div class="modal fade" id="modalDelete{{ $karyawan->id }}" tabindex="-1" aria-labelledby="modalDeleteLabel{{ $karyawan->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-danger">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalDeleteLabel{{ $karyawan->id }}">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data karyawan <strong>{{ $karyawan->nama_karyawan }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('datakaryawan.destroy', $karyawan->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</body>

</html>
