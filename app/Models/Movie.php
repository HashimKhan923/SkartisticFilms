<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = [
        'title', 'genre', 'year', 'description',
        'poster', 'banner',
        'video_type', 'video_youtube', 'video_file',
        'rating', 'duration', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'rating'      => 'float',
    ];

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class)->orderBy('sort_order');
    }

    /**
     * Return the embed URL for YouTube links.
     */
    public function getYoutubeEmbedAttribute(): ?string
    {
        if (!$this->video_youtube) return null;

        preg_match('/(?:v=|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $this->video_youtube, $m);
        return isset($m[1]) ? "https://www.youtube.com/embed/{$m[1]}" : null;
    }
}