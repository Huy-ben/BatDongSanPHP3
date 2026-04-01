<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        created_at,
        updated_at,
    ];
}
