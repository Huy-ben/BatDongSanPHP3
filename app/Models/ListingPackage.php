<?php

namespace App\Models;

use Database\Factories\ListingPackageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'price',
        'expiry_date',
        'package_type',
        'package_name',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_featured' => 'boolean',
        'price' => 'float',
    ];

    protected static function newFactory(): ListingPackageFactory
    {
        return ListingPackageFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
