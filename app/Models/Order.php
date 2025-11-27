<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'paid'       => 'bg-yellow-100 text-yellow-800',
            'preparing'  => 'bg-blue-100 text-blue-800',
            'ready'      => 'bg-green-100 text-green-800',
            'delivered'  => 'bg-gray-100 text-gray-800',
            'cancelled'  => 'bg-red-100 text-red-800',
            default      => 'bg-gray-100 text-gray-600',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending'    => 'En attente',
            'paid'       => 'Payée',
            'preparing'  => 'En préparation',
            'ready'      => 'Prête à retirer',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
        };
    }
}