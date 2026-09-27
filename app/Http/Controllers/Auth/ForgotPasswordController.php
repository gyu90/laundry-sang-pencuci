<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan halaman lupa password.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Memproses permintaan OTP.
     */
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
        ], [
            'phone.required' => 'Nomor HP wajib diisi.',
        ]);

        // Cari akun berdasarkan nomor HP
        $user = User::where('phone', $validated['phone'])->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'phone' => 'Nomor HP tidak terdaftar.',
                ])
                ->withInput();
        }

        // Generate OTP 6 digit
        $otp = (string) random_int(100000, 999999);

        // Hapus OTP lama milik user
        PasswordResetOtp::where('user_id', $user->id)->delete();

        // Simpan OTP baru
        PasswordResetOtp::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Simpan data reset password ke session
        session([
            'reset_user_id' => $user->id,
            'reset_phone' => $user->phone,
        ]);

        // Kirim OTP melalui Fonnte
        $fonnte = new FonnteService();

        $message = "🧺 Sang Pencuci\n\n"
            . "Kode OTP untuk reset password kamu adalah:\n\n"
            . "{$otp}\n\n"
            . "Kode ini berlaku selama 5 menit.\n"
            . "Jangan berikan kode ini kepada siapa pun.";

        $result = $fonnte->sendMessage(
            $user->phone,
            $message
        );

        // Jika Fonnte gagal mengirim OTP
        if (!($result['status'] ?? false)) {
            PasswordResetOtp::where('user_id', $user->id)->delete();

            session()->forget([
                'reset_user_id',
                'reset_phone',
            ]);

            return back()
                ->withErrors([
                    'phone' => 'OTP gagal dikirim. Silakan coba lagi.',
                ])
                ->withInput();
        }

        // Jika OTP berhasil dikirim
        return redirect()
            ->route('password.otp')
            ->with('success', 'Kode OTP telah dikirim ke WhatsApp kamu.');
    }

    /**
     * Menampilkan halaman verifikasi OTP.
     */
    public function showOtp()
    {
        if (!session('reset_user_id')) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'phone' => 'Silakan masukkan nomor HP terlebih dahulu.',
                ]);
        }

        return view('auth.verify-otp');
    }

    /**
     * Memverifikasi OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 digit.',
        ]);

        $userId = session('reset_user_id');

        if (!$userId) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'phone' => 'Sesi reset password sudah berakhir. Silakan ulangi.',
                ]);
        }

        $otpData = PasswordResetOtp::where('user_id', $userId)
            ->where('otp', $request->otp)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$otpData) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid.',
            ]);
        }

        if (now()->greaterThan($otpData->expires_at)) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kedaluwarsa.',
            ]);
        }

        $otpData->verified_at = now();
        $otpData->save();

        session([
            'reset_otp_verified' => true,
        ]);

        return redirect()
            ->route('password.reset')
            ->with('success', 'Kode OTP berhasil diverifikasi.');
    }

    /**
     * Menampilkan halaman password baru.
     */
    public function showResetPassword()
    {
        if (
            !session('reset_user_id') ||
            !session('reset_otp_verified')
        ) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'phone' => 'Silakan lakukan verifikasi OTP terlebih dahulu.',
                ]);
        }

        return view('auth.reset-password');
    }

    /**
     * Menyimpan password baru.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Pastikan user sudah melewati verifikasi OTP
        if (
            !session('reset_user_id') ||
            !session('reset_otp_verified')
        ) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'phone' => 'Sesi reset password tidak valid. Silakan ulangi.',
                ]);
        }

        $user = User::find(session('reset_user_id'));

        if (!$user) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'phone' => 'Akun tidak ditemukan.',
                ]);
        }

        // Simpan password baru
        $user->password = $request->password;
        $user->save();

        // Hapus OTP yang sudah digunakan
        PasswordResetOtp::where('user_id', $user->id)->delete();

        // Hapus session reset password
        session()->forget([
            'reset_user_id',
            'reset_phone',
            'reset_otp_verified',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login kembali.');
    }
}