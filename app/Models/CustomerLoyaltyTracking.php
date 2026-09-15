<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLoyaltyTracking extends Model
{

    protected $table = 'customer_loyalty_tracking';

    protected $fillable = [
        'customer_id',
        'program_id',
        'period_start_date',
        'period_end_date',
        'total_spent_in_period',
        'is_eligible',
        'last_updated_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'period_start_date' => 'date',
        'period_end_date' => 'date',
        'total_spent_in_period' => 'decimal:2',
        'is_eligible' => 'boolean',
        'last_updated_at' => 'datetime',
    ];

    /**
     * Customer yang sedang dilacak.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Program loyalty yang diikuti.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(
            LoyaltyProgram::class,
            'program_id'
        );
    }
}