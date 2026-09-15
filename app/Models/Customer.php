<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;
use App\Models\Staff;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
    'user_id',
    'created_by_staff_id',
    'name',
    'email',
    'address',
    'registered_at',
    'maps_link',
];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    /**
     * Customer terhubung dengan satu akun User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
 * Staff yang membuat/menambahkan customer.
 */
public function createdByStaff()
{
    return $this->belongsTo(
        Staff::class,
        'created_by_staff_id'
    );
}
/**
 * Daftar order milik customer.
 */
public function orders(): HasMany
{
    return $this->hasMany(Order::class);
}


}