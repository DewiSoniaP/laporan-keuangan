<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Laporan Keuangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.card-blue {
    background-color: #007bff;
    color: white;
}

.card-red {
    background-color: #dc3545;
    color: white;
}

.btn-yellow {
    background-color: #ffc107;
    color: black;
    border: none;
}

.alert {
    border-radius: 8px;
}

@media (max-width: 768px) {
    .sidebar {
        position: static;
        width: 100%;
        height: auto;
        text-align: center;
    }
    .topbar {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
</head>
<body>

<div class="container-fluid px-0">
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
