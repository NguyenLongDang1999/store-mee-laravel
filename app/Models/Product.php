<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product';

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'category_id',
        'brand_id',
        'quantity',
        'price',
        'price_discount',
        'type_discount',
        'content',
        'video_url',
        'view',
        'description',
        'image_uri',
        'status',
        'popular',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
