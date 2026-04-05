<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
       'user_id',
       'title',
       'content',
       'image',
       'excerpt',
       'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
