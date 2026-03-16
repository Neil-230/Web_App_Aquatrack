<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GallonContainer extends Model
{
    protected $fillable = [
        'tenant_id',
        'customer_id',
        'container_number',
        'status',
        'deposit_amount',
        'deposited_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'deposit_amount' => 'decimal:2',
        'deposited_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFormattedDepositAttribute(): string
    {
        return '₱' . number_format($this->deposit_amount, 2);
    }

    public function isWithCustomer(): bool
    {
        return $this->status === 'with_customer';
    }

    public function isAtStation(): bool
    {
        return $this->status === 'at_station';
    }

    public function getDaysWithCustomerAttribute(): int
    {
        if (!$this->deposited_at) return 0;
        return $this->deposited_at->diffInDays(now());
    }
}
