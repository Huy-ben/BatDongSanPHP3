<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'post_id',
        'img_url',
        'is_thumbnail',
        'created_at',
        'updated_at',
    ];
}
