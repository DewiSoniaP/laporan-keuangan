<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendWhatsapp(Request $request)
    {
        $request->validate([
            'whatsapp' => 'required',
        ]);
    
        $user = User::where('whatsapp', $request->whatsapp)->first();
    
        if (!$user) {
            return back()->withErrors(['whatsapp' => 'Nomor WhatsApp tidak ditemukan.']);
        }
    
        // Hapus token sebelumnya (jika ada)
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
    
        // Generate token (plaintext untuk URL, hashed untuk database)
        $tokenPlain = Str::random(64);
        $tokenHashed = Hash::make($tokenPlain);
    
        // Simpan token ke database
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $tokenHashed,
            'created_at' => Carbon::now(),
        ]);
    
        // Buat link reset
        $resetLink = route('password.reset.form', [
            'token' => $tokenPlain,
            'whatsapp' => $user->whatsapp,
        ]);
    
        // Format nomor WA (hilangkan 0 di depan, ganti dengan 62)
        $whatsappNumber = preg_replace('/[^0-9]/', '', $user->whatsapp);
        if (substr($whatsappNumber, 0, 1) === '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }
    
        // Buat isi pesan dan encode ke URL
        $message = "Halo {$user->name}, klik link berikut untuk mengatur ulang password Anda:\n{$resetLink}";
$encodedMessage = rawurlencode($message);


    
        // Redirect ke WhatsApp
        return redirect()->away("https://api.whatsapp.com/send?phone={$whatsappNumber}&text={$encodedMessage}");
    }    

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'whatsapp' => 'required',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->latest()
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kedaluwarsa.']);
        }

        $user = User::where('email', $request->email)
                    ->where('whatsapp', $request->whatsapp)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Auth::login($user);

        return redirect('/dashboard')->with('status', 'Password berhasil direset!');
    }

    public function showResetForm(Request $request)
{
    $token = $request->token;
    $whatsapp = $request->whatsapp;

    // Cari user berdasarkan whatsapp
    $user = User::where('whatsapp', $whatsapp)->first();

    if (!$user) {
        return redirect()->route('password.request')->withErrors(['whatsapp' => 'Nomor WhatsApp tidak valid.']);
    }

    return view('reset-password', [
        'token' => $token,
        'whatsapp' => $user->whatsapp,
        'email' => $user->email,
    ]);
}

}
