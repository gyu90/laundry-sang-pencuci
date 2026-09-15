<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Payment;


class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_date',
        'total_amount',
        'discount_amount',
        'final_amount',
        'status',
        'applied_voucher_id',
        'created_by_staff_id',
        'delivery_requested',
        'delivery_approved',
        'delivery_time',
    ];

    protected function casts(): array
{
    return [
        'order_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',

        'delivery_requested' => 'boolean',
        'delivery_approved' => 'boolean',
        'delivery_time' => 'datetime:H:i',
    ];
}

    /**
     * Customer yang memiliki order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Staff yang menerima / membuat order.
     */
    public function createdByStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by_staff_id');
    }

    /**
     * Voucher yang digunakan pada order.
     */
    public function appliedVoucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'applied_voucher_id');
    }

    /**
     * Daftar item dalam order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
{
    return $this->hasOne(Payment::class);
}

}