<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'photo',
    ];

    public function songs()
    {
        return $this->belongsToMany(Song::class)
            ->withPivot('role')
            ->withTimestamps();
    }
}
