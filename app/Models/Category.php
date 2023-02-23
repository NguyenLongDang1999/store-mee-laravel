<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'category';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image_uri',
        'status',
        'popular',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', config('constant.status.active'));
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->where('popular', config('constant.popular.active'));
    }
}
