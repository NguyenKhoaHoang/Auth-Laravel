<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post', 'category_id', 'post_id', 'id', 'id')
            ->withPivot('value');
    }


    public function post()
    {
        return $this->hasOne(Post::class, 'category_id', 'id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
