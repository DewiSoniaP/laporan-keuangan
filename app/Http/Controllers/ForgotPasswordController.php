<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

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
            'email' => $user->email,
        ]);

        // Kirim email
        Mail::send('emails.reset-password', ['resetLink' => $resetLink, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Link Reset Password')
                ->from('pmbniningkeuangan@gmail.com', 'Laporan Keuangan');
        });

        return back()->with('status', 'Link reset password sudah dikirim ke email Anda.');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Email tidak valid.']);
        }

        return view('reset-password', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
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

        $expired = Carbon::parse($reset->created_at)->addMinutes(60)->isPast();
        if ($expired) {
            return back()->withErrors(['token' => 'Token sudah kedaluwarsa. Silakan minta reset ulang.']);
        }

        $user = User::where('email', $request->email)->first();

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
}
