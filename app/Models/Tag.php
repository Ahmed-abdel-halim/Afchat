<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Tag extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['name', 'slug'];

    public function setups()
    {
        return $this->belongsToMany(Setup::class, 'setup_tags')->withTimestamps();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'method' => function ($string, $separator) {
                    return str_replace(' ', $separator, trim($string));
                }
            ]
        ];
    }
}

