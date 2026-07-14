<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LupaPasswordController extends Controller
{
    // fungsi kirim link reset
    public function kirimLinkReset(Request $request)
    {
        // validasi input email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem kami.', 
        ]);

        // membuat token unik
        $token = Str::random(64);

        // menyimpan token ke dalam tabel
        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $request->email,
        ], [
            'token' => bcrypt($token),
            'created_at' => Carbon::now()
        ]);

        // kirim email
        Mail::send('emails.forget-password', ['token' => $token, 'email' => $request->email], function ($message) use($request) {
            $message->to($request->email);
            $message->subject('Notifikasi Reset Password');
        });

        // respon ke user
        return back()->with('success', 'Kami telah mengirimkan link reset password ke email Anda.');
    }
}
