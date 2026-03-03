<?php

namespace App\Models\Catalog;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasUuids, SoftDeletes, BelongsToTenant, InteractsWithMedia;

    protected $fillable = [
        'item_type',
        'name',
        'code',
        'sku',
        'slug',
        'category_id',
        'unit_id',
        'description',
        'selling_price',
        'purchase_price',
        'currency',
        'track_inventory',
        'quantity',
        'alert_quantity',
        'barcode',
        'discount_type',
        'discount_value',
        'tax_category_id',
        'is_active',
    ];

    protected $casts = [
        'selling_price'   => 'decimal:2',
        'purchase_price'  => 'decimal:2',
        'quantity'        => 'decimal:3',
        'alert_quantity'  => 'decimal:3',
        'discount_value'  => 'decimal:4',
        'track_inventory' => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function taxCategory(): BelongsTo
    {
        return $this->belongsTo(TaxCategory::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductStock::class);
    }
}
