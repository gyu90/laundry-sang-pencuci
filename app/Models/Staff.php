<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;


class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'role',
    ];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
 * Order yang dibuat atau diterima oleh staff.
 */
public function orders(): HasMany
{
    return $this->hasMany(Order::class, 'created_by_staff_id');
}
}