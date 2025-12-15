<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Category;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_at_price',
        'cost_per_item',
        'sku',
        'barcode',
        'quantity',
        'stock_threshold',
        'stock_status',
        'is_available',
        'is_featured',
        'is_bestseller',
        'is_new',
        'is_digital',
        'weight',
        'length',
        'width',
        'height',
        'category_id',
        'producer_id',
        'tax_category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'bio',
        'stock',
        'image_url'
    ];

    /**
     * Les attributs qui doivent être transformés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost_per_item' => 'decimal:2',
        'quantity' => 'integer',
        'stock_threshold' => 'integer',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_new' => 'boolean',
        'is_digital' => 'boolean',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les valeurs par défaut des attributs du modèle.
     *
     * @var array
     */
    protected $attributes = [
        'is_available' => true,
        'is_featured' => false,
        'is_bestseller' => false,
        'is_new' => true,
        'is_digital' => false,
        'quantity' => 0,
        'stock_threshold' => 5,
        'stock_status' => 'out_of_stock',
    ];

    /**
     * Le "booting" du modèle.
     */
    protected static function boot()
    {
        parent::boot();

        // Générer un slug à partir du nom lors de la création
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
            
            // Vérifier si le slug existe déjà
            $originalSlug = $slug = $product->slug;
            $count = 1;
            
            while (static::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            $product->slug = $slug;
        });

        // Mettre à jour le statut du stock en fonction de la quantité
        static::saving(function ($product) {
            if ($product->quantity <= 0) {
                $product->stock_status = 'out_of_stock';
                $product->is_available = false;
            } elseif ($product->quantity <= $product->stock_threshold) {
                $product->stock_status = 'low_stock';
                $product->is_available = true;
            } else {
                $product->stock_status = 'in_stock';
                $product->is_available = true;
            }
        });
    }

    /**
     * Relation avec la catégorie du produit.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation avec le producteur du produit.
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producer_id')->withTrashed();
    }

    /**
     * Relation avec les commandes contenant ce produit.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->using(OrderItem::class)
            ->withPivot([
                'product_name',
                'product_sku',
                'product_description',
                'unit_price',
                'quantity',
                'tax_rate',
                'tax_amount',
                'discount_amount',
                'weight',
                'status',
            ])
            ->withTimestamps();
    }

    /**
     * Relation avec les articles de commande.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relation avec les images du produit.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Vérifie si le produit est en stock.
     */
    public function inStock(): bool
    {
        return $this->stock_status === 'in_stock' && $this->is_available;
    }

    /**
     * Vérifie si le stock est faible.
     */
    public function isLowStock(): bool
    {
        return $this->stock_status === 'low_stock';
    }

    /**
     * Vérifie si le produit est en rupture de stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->stock_status === 'out_of_stock';
    }

    /**
     * Vérifie si le produit est en promotion.
     */
    public function isOnSale(): bool
    {
        return $this->compare_at_price > $this->price;
    }

    /**
     * Récupère le pourcentage de réduction.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->compare_at_price > $this->price) {
            return (int) round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
        }

        return null;
    }

    /**
     * Récupère l'URL de l'image principale.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder-product.jpg');
    }

    /**
     * Récupère le prix formaté.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2, ',', ' ') . ' €';
    }

    /**
     * Récupère le prix de comparaison formaté.
     */
    public function getFormattedCompareAtPriceAttribute(): ?string
    {
        return $this->compare_at_price ? number_format($this->compare_at_price, 2, ',', ' ') . ' €' : null;
    }

    /**
     * Récupère le statut du stock formaté.
     */
    public function getStockStatusTextAttribute(): string
    {
        return [
            'in_stock' => 'En stock',
            'low_stock' => 'Stock faible',
            'out_of_stock' => 'Rupture de stock',
        ][$this->stock_status] ?? 'Inconnu';
    }

    /**
     * Récupère la classe CSS pour le badge de statut de stock.
     */
    public function getStockStatusBadgeClassAttribute(): string
    {
        return [
            'in_stock' => 'bg-green-100 text-green-800',
            'low_stock' => 'bg-yellow-100 text-yellow-800',
            'out_of_stock' => 'bg-red-100 text-red-800',
        ][$this->stock_status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Vérifie la fraîcheur du produit.
     */
    public function getFreshnessAttribute()
    {
        if (!$this->harvested_at) return 'Fraîcheur inconnue';
        $diff = now()->diffInHours($this->harvested_at);
        return $diff < 24 ? "Récolté aujourd'hui" : "Récolté il y a ".now()->diffInDays($this->harvested_at)." jours";
    }

}