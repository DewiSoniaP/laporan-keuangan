<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Input Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        #overlay .box {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h4>Input Pengeluaran - Tanggal {{ $tanggal }}</h4>

    <form id="formPengeluaran" method="POST" action="{{ route('pengeluaran.store') }}">
        @csrf
        <!-- Menyembunyikan input tanggal dengan tanggal yang dipilih -->
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <!-- Input pemilihan tanggal -->
        <div class="mb-3">
            <label for="tanggalInput">Pilih Tanggal</label>
            <input type="date" name="tanggal" class="form-control" id="tanggalInput" value="{{ $tanggal }}" required>
        </div>

        <div class="mb-3">
            <label>Keperluan Pengeluaran</label>
            <input type="text" name="keperluan_pengeluaran" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Pengeluaran (Rp)</label>
            <input type="text" name="jumlah_pengeluaran" class="form-control only-number" required>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('pengeluaran.index', ['bulan' => date('m', strtotime($tanggal)), 'tahun' => date('Y', strtotime($tanggal))]) }}" class="btn btn-secondary">
                Kembali
            </a>
            <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
        </div>
    </form>
</div>

<!-- Overlay -->
<div id="overlay">
    <div class="box">
        <h5>Data sudah tersimpan</h5>
        <a href="{{ route('pengeluaran.index', ['bulan' => date('m', strtotime($tanggal)), 'tahun' => date('Y', strtotime($tanggal))]) }}" class="btn btn-success mt-3">
            Kembali ke Halaman Input
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formPengeluaran');  // Menggunakan ID formPengeluaran
        const submitButton = document.getElementById('btnSubmit');  // Tombol submit

        form.addEventListener('submit', function (e) {
            e.preventDefault();  // Menghentikan form submit default

            const formData = new FormData(form);  // Mengambil data dari form

            // Kirim data form menggunakan fetch (AJAX)
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
            .then(response => response.json())  // Pastikan server mengirimkan JSON
            .then(data => {
                if (data.success) {
                    // Menampilkan overlay atau pesan sukses
                    document.getElementById('overlay').style.display = 'flex';
                    form.reset();  // Reset form setelah sukses
                } else {
                    alert('Gagal menyimpan data.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data.');
            });
        });
    });
</script>
</body>
</html>