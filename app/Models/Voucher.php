<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $fillable = [
        'customer_id',
        'program_id',
        'code',
        'reward_type',
        'reward_value',
        'status',
        'issued_at',
        'expiry_at',
        'applied_order_id',
        'applied_at',
        'applied_by_staff_id',
    ];

    public $timestamps = false;

    protected $casts = [
        'reward_value' => 'decimal:2',
        'issued_at' => 'datetime',
        'expiry_at' => 'datetime',
        'applied_at' => 'datetime',
    ];

    /**
     * Customer pemilik voucher.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Program loyalty yang menghasilkan voucher.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(
            LoyaltyProgram::class,
            'program_id'
        );
    }

    /**
     * Staff yang menerapkan voucher.
     */
    public function appliedByStaff(): BelongsTo
    {
        return $this->belongsTo(
            Staff::class,
            'applied_by_staff_id'
        );
    }

    /**
     * Order tempat voucher diterapkan.
     */
    public function appliedOrder(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'applied_order_id'
        );
    }
}