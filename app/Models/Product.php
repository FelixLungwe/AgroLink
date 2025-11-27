<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'bio' => 'boolean',
        'available' => 'boolean',
        'harvested_at' => 'datetime',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producer_id');
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : 'https://via.placeholder.com/400x300/10b981/ffffff?text=Produit';
    }

    public function getFreshnessAttribute()
    {
        if (!$this->harvested_at) return 'Fraîcheur inconnue';
        $diff = now()->diffInHours($this->harvested_at);
        return $diff < 24 ? "Récolté aujourd'hui" : "Récolté il y a ".now()->diffInDays($this->harvested_at)." jours";
    }
}