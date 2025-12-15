<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'amount',
        'shipping_cost',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_company',
        'billing_address',
        'billing_city',
        'billing_postal_code',
        'billing_country',
        'shipping_name',
        'shipping_phone',
        'shipping_company',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
        'payment_method',
        'payment_status',
        'payment_id',
        'payment_gateway',
        'shipping_method',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur qui a passé la commande
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les articles de la commande
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Vérifie si la commande est payée
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Vérifie si la commande est en cours de traitement
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Vérifie si la commande est expédiée
     */
    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    /**
     * Vérifie si la commande est livrée
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Vérifie si la commande est annulée
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Retourne la classe CSS pour le badge de statut
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
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
            'processing' => 'En préparation',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            default => ucfirst($this->status),
        };
    }

    /**
     * Retourne la classe CSS pour le badge de statut de paiement
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Retourne le libellé du statut de paiement
     */
    public function getPaymentStatusTextAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'En attente',
            'paid' => 'Payé',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé',
            default => ucfirst($this->payment_status),
        };
    }

    /**
     * Générateur de numéro de commande
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'CMD-' . now()->format('Ymd');
        $lastOrder = self::where('order_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $number = (int) str_replace($prefix . '-', '', $lastOrder->order_number) + 1;
        } else {
            $number = 1;
        }

        return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}