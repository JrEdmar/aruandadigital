<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id', 'name', 'slug', 'description',
        'price', 'wholesale_price', 'stock', 'category',
        'images', 'is_wholesale', 'status',
    ];

    protected $casts = [
        'images'          => 'array',
        'price'           => 'float',
        'wholesale_price' => 'float',
        'stock'           => 'integer',
        'is_wholesale'    => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Boot — gera slug automaticamente
    // -------------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relacionamentos
    // -------------------------------------------------------------------------

    /** Lojista dono do produto. */
    public function store(): BelongsTo
    {
        return $this->belongsTo(User::class, 'store_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /** Produtos disponíveis para atacado. */
    public function scopeWholesale($query)
    {
        return $query->where('is_wholesale', true);
    }

    /** Produtos apenas varejo. */
    public function scopeRetail($query)
    {
        return $query->where('is_wholesale', false);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /** Primeira imagem do produto ou placeholder. */
    public function getFirstImageUrlAttribute(): string
    {
        if (!empty($this->images) && is_array($this->images)) {
            return asset('storage/' . $this->images[0]);
        }

        return 'https://placehold.co/200x200/dcfce7/166534?text=' . urlencode($this->name ?? 'P');
    }

    /** Alias para compatibilidade com views que usam main_image_url. */
    public function getMainImageUrlAttribute(): string
    {
        return $this->first_image_url;
    }
}
