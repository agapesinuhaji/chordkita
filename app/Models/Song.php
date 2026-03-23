<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'lyrics',
        'key',
        'youtube_url',
        'status',
        'published_at',
        'views',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
