<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Menampilkan daftar staff.
     */
    public function index()
    {
        $staffs = Staff::with('user')
            ->where('role', 'staff')
            ->latest()
            ->get();

        return view('owner.staff.index', compact('staffs'));
    }

    /**
     * Menyimpan akun staff baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',

            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama staff wajib diisi.',

            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',

            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        DB::transaction(function () use ($request) {

            /*
             * Buat akun User terlebih dahulu.
             *
             * Password akan otomatis di-hash
             * karena User model menggunakan:
             *
             * 'password' => 'hashed'
             */
            $user = User::create([
                'username' => $request->username,
                'phone' => $request->phone,
                'password' => $request->password,
                'user_type' => 'staff',
                'is_active' => true,
            ]);

            /*
             * Buat data Staff yang terhubung
             * dengan User tersebut.
             */
            Staff::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'role' => 'staff',
            ]);
        });

        return redirect()
            ->route('owner.staff.index')
            ->with('success', 'Akun staff berhasil ditambahkan.');
    }

/**
 * Mengaktifkan atau menonaktifkan akun staff.
 */
public function toggleStatus(Staff $staff)
{
    $user = $staff->user;

    $user->update([
        'is_active' => !$user->is_active,
    ]);

    return redirect()
        ->route('owner.staff.index')
        ->with(
            'success',
            $user->is_active
                ? 'Akun staff berhasil diaktifkan.'
                : 'Akun staff berhasil dinonaktifkan.'
        );
}

}