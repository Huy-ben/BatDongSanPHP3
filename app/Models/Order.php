<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'package_name',
        'amount',
        'transaction_code',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'type' => 'string',
        'package_name' => 'string',
        'amount' => 'float',
        'transaction_code' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
