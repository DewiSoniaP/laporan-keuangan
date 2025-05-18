<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 220px;
            background-color: rgb(74, 116, 201);
            padding: 20px;
            height: 100vh;
        }
        .sidebar h4 {
            font-size: 16px;
            font-weight: bold;
        }
        .sidebar a {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgb(110, 168, 254);
            color: white;
            font-weight: bold;
        }
        .topbar {
            background-color: white;
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
        }
        .content {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row no-gutters">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="sidebar">
                <h4>Laporan Keuangan</h4>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('pendapatan.index') }}" class="{{ request()->routeIs('pendapatan.index') ? 'active' : '' }}">Pendapatan</a>
                <a href="{{ route('pengeluaran.index') }}" class="{{ request()->routeIs('pengeluaran.index') ? 'active' : '' }}">Pengeluaran</a>
                <a href="{{ route('datakaryawan.index') }}" class="{{ request()->routeIs('datakaryawan.index') ? 'active' : '' }}">Data Karyawan</a>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-10">
            <div class="topbar d-flex justify-content-between align-items-center">
                <div><strong>Hallo, Admin</strong></div>
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

                @if(session('success'))
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
                        <img src="{{ asset('storage/foto_karyawan/' . $karyawan->foto) }}" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>{{ $karyawan->nama_karyawan }}</td>
                <td>{{ $karyawan->jabatan }}</td>
                <td>
                    <a href="{{ route('datakaryawan.edit', $karyawan->id) }}" class="btn btn-success btn-sm">Edit</a>
                    <form action="{{ route('datakaryawan.destroy', $karyawan->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
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