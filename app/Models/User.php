<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Customer;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'username',
        'phone',
        'password',
        'user_type',
        'is_active',
    ];

    /**
     * Field yang disembunyikan ketika model diserialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi ke tabel staff.
     */
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Relasi ke tabel customers.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}