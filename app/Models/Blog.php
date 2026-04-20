<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'image',
        'excerpt',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'category_id' => 'integer',
        'status' => 'string',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
