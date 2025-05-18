<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Pendapatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 220px;
            background-color:rgb(74, 116, 201);
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
            background-color:rgb(110, 168, 254);
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
        .floating-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
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
                    <h4>Data Pendapatan</h4>
                    <p>Silakan pilih bulan dan tahun untuk melihat data pendapatan atau menambah data baru.</p>

                    {{-- Form Filter Bulan dan Tahun --}}
                    <form method="GET" action="{{ route('pendapatan.show') }}" class="row g-3 align-items-center mb-4">
                        <div class="col-auto">
                            <label for="bulan">Pilih Bulan:</label>
                            <select name="bulan" id="bulan" class="form-control" required>
                                <option value="">-- Pilih Bulan --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-auto">
                            <label for="tahun">Pilih Tahun:</label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                <option value="">-- Pilih Tahun --</option>
                                @for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-auto mt-4">
                            <button type="submit" class="btn btn-primary">Proses</button>
                        </div>
                    </form>

                    <!-- Tombol Input Pendapatan -->
                    <a href="{{ route('pendapatan.input') }}" class="btn btn-success floating-button">
                        Input Pendapatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>