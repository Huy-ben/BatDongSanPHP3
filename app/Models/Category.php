<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
        'parent_id',
        'image',
        'status',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'status' => 'integer',
    ];

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ? '1' : '0';
    }

    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }
}
