<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Setup extends Model
{
    use HasFactory, Sluggable;
        protected $fillable = ['text','slug','media_type','media_url','user_id'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function punchlines()
    {
        return $this->hasMany(Punchline::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'setup_tags')->withTimestamps();
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'text',
                'method' => function ($string, $separator) {
                    $string = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $string);
                    $words = explode(' ', trim($string));
                    $base = implode(' ', array_slice($words, 0, 15)); // Extend to 15 words for better SEO and matching
                    return str_replace(' ', $separator, $base);
                }
            ]
        ];
    }
}
