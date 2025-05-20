<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
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
    </style>
</head>

<body>

    <div class="container-fluid px-0">
        <div class="row m-0">
            <!-- Sidebar -->
            <div class="col-md-2 p-0">
                <div class="sidebar">
                    <h4>Laporan Keuangan</h4>
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('pendapatan.index') }}"
                        class="{{ request()->routeIs('pendapatan.index') ? 'active' : '' }}">Pendapatan</a>
                    <a href="{{ route('pengeluaran.index') }}"
                        class="{{ request()->routeIs('pengeluaran.index') ? 'active' : '' }}">Pengeluaran</a>
                    <a href="{{ route('datakaryawan.index') }}"
                        class="{{ request()->routeIs('datakaryawan.index') ? 'active' : '' }}">Data Karyawan</a>
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
                        <a href="{{ route('datakaryawan.create') }}" class="btn btn-primary">+ Tambah Karyawan</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-hover bg-white">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawans as $index => $karyawan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if ($karyawan->foto)
                                            <img src="{{ asset('storage/foto_karyawan/' . $karyawan->foto) }}"
                                                width="50" height="50"
                                                style="object-fit: cover; border-radius: 5px;">
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>{{ $karyawan->nama_karyawan }}</td>
                                    <td>{{ $karyawan->jabatan }}</td>
                                    <td>
                                        <a href="{{ route('datakaryawan.edit', $karyawan->id) }}"
                                            class="btn btn-success btn-sm">Edit</a>
                                        <form action="{{ route('datakaryawan.destroy', $karyawan->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data karyawan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</body>

</html>
