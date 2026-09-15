<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'paid_at',
        'confirmed_by_staff_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function confirmedByStaff(): BelongsTo
    {
        return $this->belongsTo(
            Staff::class,
            'confirmed_by_staff_id'
        );
    }
}