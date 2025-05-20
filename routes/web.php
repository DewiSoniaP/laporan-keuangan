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
Route::resource('pendapatan', PendapatanController::class);

// ===========================
// Pengeluaran
// ===========================
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::resource('pengeluaran', PengeluaranController::class);

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
