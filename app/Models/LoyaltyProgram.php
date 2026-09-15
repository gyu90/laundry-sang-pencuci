<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyProgram extends Model
{
    protected $fillable = [
        'name',
        'description',
        'min_transaction_amount',
        'start_date',
        'end_date',
        'reward_type',
        'reward_value',
        'applicable_service_id',
        'free_service_package_id',
        'is_active',
    ];

    public $timestamps = false;

    protected $casts = [
        'min_transaction_amount' => 'decimal:2',
        'reward_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Layanan yang mendapatkan reward.
     */
    public function applicableService(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'applicable_service_id');
    }

    public function freeServicePackage(): BelongsTo
{
    return $this->belongsTo(
        ServicePackage::class,
        'free_service_package_id'
    );
}

    /**
     * Tracking loyalty customer.
     */
    public function customerLoyaltyTrackings(): HasMany
    {
        return $this->hasMany(
            CustomerLoyaltyTracking::class,
            'program_id'
        );
    }

    /**
     * Voucher yang diterbitkan dari program ini.
     */
    public function vouchers(): HasMany
    {
        return $this->hasMany(
            Voucher::class,
            'program_id'
        );
    }
}