<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    use HasFactory;
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
}
