<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'type',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
