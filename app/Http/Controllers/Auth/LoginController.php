<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Memproses login Staff maupun Customer.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Username atau nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $login = $credentials['login'];

        /*
        |--------------------------------------------------------------------------
        | Tentukan apakah input merupakan username atau nomor HP
        |--------------------------------------------------------------------------
        */

        $field = is_numeric($login) ? 'phone' : 'username';

        /*
        |--------------------------------------------------------------------------
        | Coba autentikasi
        |--------------------------------------------------------------------------
        */

if (Auth::attempt(
    [
        $field => $login,
        'password' => $credentials['password'],
        'is_active' => true,
    ],
    $request->boolean('remember')
)) {

            $request->session()->regenerate();

            /*
            |--------------------------------------------------------------------------
            | Arahkan berdasarkan tipe user
            |--------------------------------------------------------------------------
            */

if (Auth::user()->user_type === 'customer') {
    return redirect()->route('customer.home');
}

if (Auth::user()->user_type === 'staff') {

    if (Auth::user()->staff?->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    return redirect()->route('staff.dashboard');
}

            /*
            | Jika user_type tidak dikenali
            */

            Auth::logout();

            return back()->withErrors([
                'login' => 'Tipe akun tidak dikenali.',
            ]);
        }

        return back()
            ->withErrors([
                'login' => 'Username/nomor HP atau password salah.',
            ])
            ->onlyInput('login');
    }

    /**
     * Logout.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}