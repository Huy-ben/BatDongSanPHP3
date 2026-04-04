<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
        'product_id',
        'image_url',
        'is_thumbnail',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'is_thumbnail' => 'boolean',
    ];

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class, 'product_id');
    }
}
