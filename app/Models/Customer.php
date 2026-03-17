<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'address',
        'delivery_address',
        'outstanding_balance',
        'gallons_on_hand',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'outstanding_balance' => 'decimal:2',
        'gallons_on_hand' => 'integer',
        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function gallonContainers(): HasMany
    {
        return $this->hasMany(GallonContainer::class);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return '₱' . number_format($this->outstanding_balance, 2);
    }

    public function getTotalPurchasesAttribute(): float
    {
        return $this->sales()->sum('total_amount');
    }
}
