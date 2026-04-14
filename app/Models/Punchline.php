<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Punchline extends Model
{
    use HasFactory;
    protected $fillable = ['setup_id', 'user_id', 'text', 'media_type', 'media_url', 'views', 'laughs'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function getMediaUrlAttribute($value)
    {
        if (!$value) return null;
        if (filter_var($value, FILTER_VALIDATE_URL)) {
             // For existing records that have full URLs including localhost
             if (str_contains($value, 'localhost') || str_contains($value, '127.0.0.1')) {
                 $path = last(explode('/storage/', $value));
                 return asset('storage/' . $path);
             }
             return $value;
        }
        return asset('storage/' . $value);
    }
}
