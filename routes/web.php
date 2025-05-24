<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\CetakController;

// ===========================
// Halaman Login
// ===========================
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->with('error', 'Email atau password salah');
})->name('login.post');

// ===========================
// Lupa Password
// ===========================
Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendWhatsapp'])->name('password.whatsapp');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update.custom');

// ===========================
// Semua route harus login
// ===========================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cetak
    Route::get('/cetak', [CetakController::class, 'index'])->name('cetak.index');
    Route::get('/cetak/pdf', [CetakController::class, 'cetakPDF'])->name('cetak.pdf');

    // Pendapatan
    Route::get('/pendapatan', [PendapatanController::class, 'index'])->name('pendapatan.index');
    
    // Validasi data pendapatan (admin + user)
    Route::post('/pendapatan/{id}/validate', [PendapatanController::class, 'validateData'])
        ->middleware('role:admin,user')
        ->name('pendapatan.validate');

    // Hanya admin boleh create, update, delete pendapatan
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/pendapatan', [PendapatanController::class, 'store'])->name('pendapatan.store');
        Route::get('/pendapatan/create', [PendapatanController::class, 'create'])->name('pendapatan.create');
        Route::get('/pendapatan/{id}/edit', [PendapatanController::class, 'edit'])->name('pendapatan.edit');
        Route::put('/pendapatan/{id}', [PendapatanController::class, 'update'])->name('pendapatan.update');
        Route::delete('/pendapatan/{id}', [PendapatanController::class, 'destroy'])->name('pendapatan.destroy');
    });

    // Pengeluaran
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');

    // Validasi data pengeluaran (admin + user)
    Route::post('/pengeluaran/{id}/validate', [PengeluaranController::class, 'validateData'])
        ->middleware('role:admin,user')
        ->name('pengeluaran.validate');

    // Hanya admin boleh create, update, delete pengeluaran
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::get('/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
        Route::get('/pengeluaran/{id}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
        Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
        Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    });

    // Data Karyawan
    Route::get('/datakaryawan', [DataKaryawanController::class, 'index'])->name('datakaryawan.index');

    // Hanya admin boleh create, update, delete karyawan
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/datakaryawan/input', [DataKaryawanController::class, 'create'])->name('datakaryawan.create');
        Route::post('/datakaryawan', [DataKaryawanController::class, 'store'])->name('datakaryawan.store');
        Route::get('/datakaryawan/{id}/edit', [DataKaryawanController::class, 'edit'])->name('datakaryawan.edit');
        Route::put('/datakaryawan/{id}', [DataKaryawanController::class, 'update'])->name('datakaryawan.update');
        Route::delete('/datakaryawan/{id}', [DataKaryawanController::class, 'destroy'])->name('datakaryawan.destroy');
    });

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

});

// ===========================
// Redirect Default
// ===========================
Route::get('/', function () {
    return redirect()->route('login');
});
