<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'seller_id',
        'category_id',
        'price',
        'area',
        'address',
        'status',
    ];

    protected $casts = [
        'seller_id' => 'integer',
        'category_id' => 'integer',
        'price' => 'integer',
        'area' => 'float',
        'status' => 'integer',
    ];

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ? '1' : '0';
    }

    public function seller(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function thumbnailImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Image::class, 'product_id')->where('is_thumbnail', true);
    }

    public function syncImages(array $imageUrls): void
    {
        $imageUrls = array_values(array_filter($imageUrls));

        $this->images()->delete();

        foreach ($imageUrls as $index => $imageUrl) {
            $this->images()->create([
                'image_url' => $imageUrl,
                'is_thumbnail' => $index === 0,
            ]);
        }
    }
}
