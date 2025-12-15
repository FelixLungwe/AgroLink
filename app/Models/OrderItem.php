<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_description',
        'unit_price',
        'quantity',
        'options',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'weight',
        'width',
        'height',
        'depth',
        'status',
        'shipped_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'weight' => 'decimal:3',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'depth' => 'decimal:2',
        'options' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Relation avec la commande parente
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relation avec le produit
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Calcul du montant total pour cette ligne de commande
     */
    public function getTotalAttribute(): float
    {
        return ($this->unit_price * $this->quantity) + $this->tax_amount - $this->discount_amount;
    }

    /**
     * Calcul du sous-total (avant taxes et réductions)
     */
    public function getSubtotalAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * Vérifie si l'article est expédié
     */
    public function isShipped(): bool
    {
        return $this->status === 'shipped' && $this->shipped_at !== null;
    }

    /**
     * Vérifie si l'article est livré
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered' && $this->delivered_at !== null;
    }

    /**
     * Vérifie si l'article est retourné
     */
    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    /**
     * Vérifie si l'article est remboursé
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Retourne la classe CSS pour le badge de statut
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'shipped' => 'bg-blue-100 text-blue-800',
            'delivered' => 'bg-green-100 text-green-800',
            'returned' => 'bg-purple-100 text-purple-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Retourne le libellé du statut
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'shipped' => 'Expédié',
            'delivered' => 'Livré',
            'returned' => 'Retourné',
            'refunded' => 'Remboursé',
            'cancelled' => 'Annulé',
            default => ucfirst($this->status),
        };
    }
}
