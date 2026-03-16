<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'tenant_id',
        'sale_id',
        'customer_id',
        'delivery_person_id',
        'delivery_address',
        'status',
        'scheduled_at',
        'dispatched_at',
        'delivered_at',
        'delivery_notes',
        'delivery_fee',
        'tracking_number',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
        'delivery_fee' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function deliveryPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    public function getFormattedDeliveryFeeAttribute(): string
    {
        return '₱' . number_format($this->delivery_fee, 2);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInTransit(): bool
    {
        return $this->status === 'in_transit';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function getEstimatedDeliveryTimeAttribute(): string
    {
        if ($this->delivered_at) {
            return $this->delivered_at->format('g:i A');
        }
        
        if ($this->dispatched_at) {
            return $this->dispatched_at->addHours(2)->format('g:i A') . ' (estimated)';
        }
        
        return $this->scheduled_at->format('g:i A') . ' (scheduled)';
    }
}
