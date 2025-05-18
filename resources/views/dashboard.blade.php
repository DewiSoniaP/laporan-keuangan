<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Laporan Keuangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .card-blue {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        .card-red {
            background-color: #dc3545;
            color: white;
            border-radius: 5px;
        }
        .btn-yellow {
            background-color: #ffc107;
            color: #000;
        }
        .row.no-gutters {
            margin-right: 0;
            margin-left: 0;
        }
        .row.no-gutters > [class^="col-"],
        .row.no-gutters > [class*=" col-"] {
            padding-right: 0;
            padding-left: 0;
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
                <h4>Laporan Keuangan</h4>
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="form-row align-items-end">
                        <div class="col-md-3">
                            <label for="bulan">Pilih Bulan:</label>
                            <select class="form-control" name="bulan" id="bulan">
                                <option value="">--Pilih Bulan--</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tahun">Pilih Tahun:</label>
                            <select class="form-control" name="tahun" id="tahun">
                                <option value="">--Pilih Tahun--</option>
                                @for ($y = 2022; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success">Proses</button>
                            <a href="{{ route('cetak.index') }}" class="btn btn-yellow">Cetak</a>
                        </div>
                    </div>
                </form>

                @if(request('bulan') && request('tahun'))
                    <div class="row my-4">
                        <div class="col-md-6">
                            <div class="card card-blue p-3">
                                <h5>Pendapatan (Per Bulan)</h5>
                                <h3>Rp. {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-red p-3">
                                <h5>Pengeluaran (Per Bulan)</h5>
                                <h3>Rp. {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>

                    @if(isset($trendDiagnose) && count($trendDiagnose) > 0)
                        <div class="mt-5">
                            <h5>Trend Jenis Kunjungan Terbanyak</h5>
                            <canvas id="trendChart" height="120"></canvas>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info mt-4">
                        Silakan pilih bulan dan tahun terlebih dahulu untuk melihat data pendapatan dan pengeluaran.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(isset($trendDiagnose) && count($trendDiagnose) > 0)
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($trendDiagnose->pluck('diagnose')) !!},
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: {!! json_encode($trendDiagnose->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
@endif
</script>

</body>
</html>
