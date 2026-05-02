<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    protected $fillable = [
        'movie_id', 'name', 'actor_name', 'role', 'bio', 'photo', 'sort_order'
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}