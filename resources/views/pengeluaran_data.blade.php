<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pengeluaran {{ $bulan }}/{{ $tahun }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .table thead th { background-color: #666; color: white; }
        .btn-edit { background-color: #28a745; color: white; }
        .btn-delete { background-color: #dc3545; color: white; }
        .topbar { background-color: white; padding: 10px 20px; border-bottom: 1px solid #ccc; }
    </style>
</head>
<body>

<div class="topbar d-flex justify-content-between align-items-center">
    <div><strong>Hallo, Admin</strong></div>
    <div>
        <a href="{{ route('logout') }}" class="btn btn-outline-primary btn-sm"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="row g-3 mb-4 mt-3">
        <input type="hidden" name="bulan" value="{{ $bulan }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <div class="col-auto">
            <label for="tanggal" class="col-form-label">Pilih Tanggal:</label>
        </div>
        <div class="col-auto">
            <select name="tanggal" id="tanggal" class="form-select" required>
                <option value="">-- Pilih Tanggal --</option>
                @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); $i++)
                    @php
                        $tgl = str_pad($i, 2, '0', STR_PAD_LEFT);
                        $tanggalValue = "{$tahun}-{$bulan}-{$tgl}";
                    @endphp
                    <option value="{{ $tanggalValue }}" {{ request('tanggal') === $tanggalValue ? 'selected' : '' }}>
                        {{ $tanggalValue }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    @php
        $tanggalDipilih = request('tanggal');
        $filtered = $pengeluaran->where('tanggal', $tanggalDipilih);
    @endphp

    @if($tanggalDipilih)
        <h5 class="mb-3">Data untuk tanggal: <strong>{{ $tanggalDipilih }}</strong></h5>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Keperluan Pengeluaran</th>
                    <th>Jumlah Pengeluaran</th>
                    <th>Keterangan</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluaran as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->keperluanPengeluaran }}</td>
            <td>Rp. {{ number_format($item->jumlahPengeluaran, 0, ',', '.') }}</td>
            <td>{{ $item->keterangan }}</td>
            
            <td class="d-flex gap-1">
                <button type="button"
                        class="btn btn-edit btn-sm"
                        onclick="openEditModal({{ $index }})"
                        data-item='@json($item)'
                        data-index="{{ $index }}">
                    Ubah
                </button>

                <form action="{{ route('pengeluaran.destroy', ['id' => $item->idPengeluaran]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <button type="submit" class="btn btn-delete btn-sm">Hapus</button>
                </form>

                @if($item->is_verified)
                                <button type="button" class="btn btn-secondary btn-sm" disabled>Terverifikasi</button>
                            @else
                                <form action="{{ route('pengeluaran.verifikasi') }}" method="POST" onsubmit="return confirm('Yakin ingin memverifikasi data ini?');">
                                    @csrf
                                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                    <input type="hidden" name="tanggal" value="{{ $tanggalDipilih }}">
                                   <input type="hidden" name="id" value="{{ $item->idPengeluaran }}">
                                    <button type="submit" class="btn btn-warning btn-sm">Verifikasi</button>
                                </form>
                            @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">Tidak ada data untuk tanggal ini.</td>
        </tr>
    @endforelse
</tbody>
        </table>

        @if(count($filtered) > 0)
            <form method="POST" action="{{ route('pengeluaran.verifikasi') }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggalDipilih }}">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-success mt-3">Verifikasi Laporan</button>
            </form>
        @endif

    @else
        <div class="alert alert-info">Silakan pilih tanggal terlebih dahulu untuk melihat data pengeluaran.</div>
    @endif

    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div class="bg-white p-4 rounded" style="min-width: 500px; max-width:90%;">
        <h5>Ubah Data Pengeluaran</h5>
        <form id="editForm" method="POST" action="">
    @csrf
    @method('PUT')
    <input type="hidden" name="tanggal" value="{{ $tanggalDipilih }}">
    <input type="hidden" name="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">

    <div class="mb-2">
        <label>Keperluan Pengeluaran</label>
        <input type="text" class="form-control" name="keperluan_pengeluaran" id="edit-keperluan_pengeluaran" required>
    </div>
    
    <div class="mb-3">
        <label>Jumlah Pengeluaran</label>
        <input type="number" class="form-control" name="jumlah_pengeluaran" id="edit-jumlah_pengeluaran" required>
    </div>
    
    <div class="mb-2">
        <label>Keterangan</label>
        <input type="text" class="form-control" name="keterangan" id="edit-keterangan" required>
    </div>

    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Kembali</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();  // cegah reload

        const form = e.target;
        const actionUrl = form.action;
        const formData = new FormData(form);

        fetch(actionUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update data tabel sesuai index yang sedang diedit
                const index = form.getAttribute('data-edit-index');
                updateTableRow(index, data.updatedData);

                alert('Data berhasil diubah');
                closeEditModal();
            } else {
                alert('Gagal mengubah data: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    });

    function updateTableRow(index, updatedData) {
        const table = document.querySelector('table tbody');
        const row = table.rows[index];

        row.cells[1].textContent = updatedData.keperluanPengeluaran;
        row.cells[2].textContent = 'Rp. ' + Number(updatedData.jumlahPengeluaran).toLocaleString('id-ID');
        row.cells[3].textContent = updatedData.keterangan;
    }

    function openEditModal(index) {
        const button = event.currentTarget;
        const data = JSON.parse(button.getAttribute('data-item'));

        document.getElementById('edit-keperluan_pengeluaran').value = data.keperluanPengeluaran || '';
        document.getElementById('edit-jumlah_pengeluaran').value = data.jumlahPengeluaran || '';
        document.getElementById('edit-keterangan').value = data.keterangan || '';

        const form = document.getElementById('editForm');
        form.action = `/pengeluaran/update/${data.idPengeluaran}`;
        form.setAttribute('data-edit-index', index);

        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
</body>
</html>