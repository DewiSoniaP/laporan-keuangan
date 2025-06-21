<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Halaman Pendapatan</title>
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
                    <h4>Data Pendapatan</h4>

                    {{-- Form Filter Tanggal dan Status --}}
                    <form method="GET" action="{{ route('pendapatan.index') }}" class="row align-items-end mb-4">
                        <div class="col-3">
                            <label for="tanggal">Filter Tanggal:</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control"
                                value="{{ request('tanggal') }}">
                        </div>

                        <div class="col-3">
                            <label for="status">Status Verifikasi:</label>
                            <div class="px-2 form-control">
                                <select name="status" id="status" class="w-100 border-0" style="outline: none">
                                    <option value="">-- Semua --</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Belum Terverifikasi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6 d-flex justify-content-between mt-4">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('pendapatan.index') }}" class="btn btn-danger">Reset</a>
                            </div>
                            @if(Auth::user()->role === 'admin')
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreate">Input Pendapatan</button>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th class="text-nowrap">Nama Pasien</th>
                                    <th>Usia</th>
                                    <th class="text-nowrap">Nama Keluarga</th>
                                    <th>Alamat</th>
                                    <th>Diagnosa</th>
                                    <th class="text-nowrap">Jenis Kunjungan</th>
                                    <th>Jasa</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendapatan as $item)
                                    <tr>
                                        <td class="text-nowrap">{{ $loop->iteration }}</td>
                                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td class="text-nowrap">{{ $item->namaPasien }}</td>
                                        <td class="text-nowrap">{{ $item->usia }}</td>
                                        <td class="text-nowrap">{{ $item->namaKeluarga }}</td>
                                        <td class="text-nowrap">{{ $item->alamat }}</td>
                                        <td class="text-nowrap">{{ $item->diagnose }}</td>
                                        <td class="text-nowrap">{{ $item->jenisKunjungan }}</td>
                                        <td class="text-nowrap">Rp {{ number_format($item->jasa, 0, ',', '.') }}</td>
                                        <td class="text-nowrap text-white">
                                            @if ($item->is_verified)
                                                <span class="badge bg-success">Terverifikasi</span>
                                            @else
                                                @if (Auth::user()->role === 'validator')
                                                    @if ($item->idPendapatan == $earliestUnverifiedId)
                                                        <form action="{{ route('pendapatan.validate', $item->idPendapatan) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Yakin ingin memverifikasi data ini?')">Belum Diverifikasi</button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-secondary">Tunggu data sebelumnya diverifikasi</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-warning">Belum Diverifikasi</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            @if(Auth::user()->role === 'admin')
                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->idPendapatan }}">Edit</button>
                                            <!-- Tombol Hapus -->
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $item->idPendapatan }}">Hapus</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">Belum ada data pendapatan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    @if(Auth::user()->role === 'admin')
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('pendapatan.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Tambah Pendapatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nama Pasien</label>
                            <input type="text" name="namaPasien" class="form-control" placeholder="masukan nama pasien" required>
                        </div>
                        <div class="col-md-3">
                            <label>Usia</label>
                            <input type="number" name="usia" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col-md-9">
                            <label>Nama Keluarga</label>
                            <input type="text" name="namaKeluarga" class="form-control" placeholder="masukan nama keluarga" required>
                        </div>
                        <div class="col-md-12">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" placeholder="masukan nama alamat" required>
                        </div>
                        <div class="col-md-6">
                            <label>Diagnosa</label>
                            <input type="text" name="diagnose" class="form-control" placeholder="masukan hasil diagnose" required>
                        </div>
                        <div class="col-md-6">
                            <label>Jenis Kunjungan</label>
                            <input type="text" name="jenisKunjungan" class="form-control" placeholder="masukan jenis kunjungan" required>
                        </div>
                        <div class="col-md-6">
                            <label>Jasa</label>
                            <input type="number" name="jasa" class="form-control" placeholder="masukan harga jasa" required>
                        </div>
                        <!-- Tidak ada input status verifikasi di form input -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL EDIT --}}
    @foreach ($pendapatan as $item)
        @if(Auth::user()->role === 'admin')
        <div class="modal fade" id="modalEdit{{ $item->idPendapatan }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $item->idPendapatan }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('pendapatan.update', $item->idPendapatan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditLabel{{ $item->idPendapatan }}">Edit Pendapatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Nama Pasien</label>
                                <input type="text" name="namaPasien" class="form-control" placeholder="masukan nama pasien" value="{{ $item->namaPasien }}" required>
                            </div>
                            <div class="col-md-3">
                                <label>Usia</label>
                                <input type="number" name="usia" class="form-control" placeholder="0" value="{{ $item->usia }}" required>
                            </div>
                            <div class="col-md-9">
                                <label>Nama Keluarga</label>
                                <input type="text" name="namaKeluarga" class="form-control" placeholder="masukan nama keluarga" value="{{ $item->namaKeluarga }}" required>
                            </div>
                            <div class="col-md-12">
                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" placeholder="masukan nama alamat" value="{{ $item->alamat }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Diagnosa</label>
                                <input type="text" name="diagnose" class="form-control" placeholder="masukan hasil diagnose" value="{{ $item->diagnose }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Jenis Kunjungan</label>
                                <input type="text" name="jenisKunjungan" class="form-control" placeholder="masukan jenis kunjungan" value="{{ $item->jenisKunjungan }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Jasa</label>
                                <input type="number" name="jasa" class="form-control" placeholder="masukan harga jasa" value="{{ $item->jasa }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Status Verifikasi</label>
                                <!-- Disabled select for showing status -->
                                <select name="is_verified_disabled" class="form-control" disabled>
                                    <option value="1" {{ $item->is_verified ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="0" {{ !$item->is_verified ? 'selected' : '' }}>Belum Terverifikasi</option>
                                </select>
                                <!-- Hidden input to submit value -->
                                <input type="hidden" name="is_verified" value="{{ $item->is_verified ? '1' : '0' }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    @endforeach

    {{-- MODAL DELETE --}}
    @foreach ($pendapatan as $item)
        @if(Auth::user()->role === 'admin')
        <div class="modal fade" id="modalDelete{{ $item->idPendapatan }}" tabindex="-1" aria-labelledby="modalDeleteLabel{{ $item->idPendapatan }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-danger">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalDeleteLabel{{ $item->idPendapatan }}">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data pendapatan atas nama
                        <strong>{{ $item->namaPasien }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('pendapatan.destroy', $item->idPendapatan) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

</body>

</html>
