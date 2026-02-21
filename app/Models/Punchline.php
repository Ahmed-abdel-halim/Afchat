<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Punchline extends Model
{
    use HasFactory;
        protected $fillable = ['setup_id','text','media_type','media_url','views','laughs'];

    public function setup()
    {
        return $this->belongsTo(Setup::class);
    }

    public function getStrengthAttribute(): float
    {
        // laughs/views (تفادي القسمة على صفر)
        return $this->views > 0 ? ($this->laughs / $this->views) : 0.0;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
