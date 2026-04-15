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

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_WAITING = 'waiting';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
        self::STATUS_REJECTED,
        self::STATUS_WAITING,
    ];
    protected $fillable = [
        'title',
        'seller_id',
        'category_id',
        'slug',
        'price',
        'area',
        'address',
        'location',
        'description',
        'status',
    ];

    protected $casts = [
        'seller_id' => 'integer',
        'category_id' => 'integer',
        'price' => 'integer',
        'area' => 'float',
        'location' => 'string',
        'description' => 'string',
        'status' => 'string',
    ];

    public function setStatusAttribute($value): void
    {
        $status = in_array($value, self::STATUSES, true) ? $value : self::STATUS_DRAFT;

        $this->attributes['status'] = $status;
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function thumbnailImage(): HasOne
    {
        return $this->hasOne(Image::class, 'product_id')->where('is_thumbnail', true);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'product_id');
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
