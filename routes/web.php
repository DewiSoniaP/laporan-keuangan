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
// Dashboard
// ===========================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ===========================
// Cetak
// ===========================
Route::get('/cetak', [CetakController::class, 'index'])->name('cetak.index');
Route::get('/cetak/pdf', [CetakController::class, 'exportPDF'])->name('cetak.pdf');

// ===========================
// Pendapatan
// ===========================
Route::get('/pendapatan', [PendapatanController::class, 'index'])->name('pendapatan.index');
Route::get('/pendapatan/input', [PendapatanController::class, 'inputForm'])->name('pendapatan.input');
Route::get('/pendapatan/form', [PendapatanController::class, 'form'])->name('pendapatan.form');
Route::post('/pendapatan/store', [PendapatanController::class, 'store'])->name('pendapatan.store');
Route::get('/pendapatan/show', [PendapatanController::class, 'showData'])->name('pendapatan.show');
Route::delete('/pendapatan/delete/{id}', [PendapatanController::class, 'destroy'])->name('pendapatan.destroy');  
Route::get('/pendapatan/edit/{id}', [PendapatanController::class, 'edit'])->name('pendapatan.edit'); 
Route::put('/pendapatan/update/{id}', [PendapatanController::class, 'update'])->name('pendapatan.update');
Route::post('/pendapatan/verifikasi', [PendapatanController::class, 'verifikasi'])->name('pendapatan.verifikasi');

// ===========================
// Pengeluaran
// ===========================
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::get('/pengeluaran/input', [PengeluaranController::class, 'inputForm'])->name('pengeluaran.input');
Route::get('/pengeluaran/form', [PengeluaranController::class, 'form'])->name('pengeluaran.form');
Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
Route::get('/pengeluaran/show', [PengeluaranController::class, 'showData'])->name('pengeluaran.show');
Route::delete('/pengeluaran/delete/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');  
Route::get('/pengeluaran/edit/{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit'); 
Route::put('/pengeluaran/update/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
Route::post('/pengeluaran/verifikasi', [PengeluaranController::class, 'verifikasi'])->name('pengeluaran.verifikasi');

// ===========================
// Data Karyawan
// ===========================
Route::get('/datakaryawan', [DataKaryawanController::class, 'index'])->name('datakaryawan.index');
Route::get('/datakaryawan/input', [DataKaryawanController::class, 'create'])->name('datakaryawan.create');
Route::post('/datakaryawan', [DataKaryawanController::class, 'store'])->name('datakaryawan.store');
Route::get('/datakaryawan/{id}/edit', [DataKaryawanController::class, 'edit'])->name('datakaryawan.edit');
Route::delete('/datakaryawan/{id}', [DataKaryawanController::class, 'destroy'])->name('datakaryawan.destroy'); 
Route::put('/datakaryawan/{id}', [DataKaryawanController::class, 'update'])->name('datakaryawan.update');

// ===========================
// Logout
// ===========================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ===========================
// Redirect Default
// ===========================
Route::get('/', function () {
    return redirect()->route('login');
});
