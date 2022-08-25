<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function categories()
    {
        // return $this->belongsToMany(Category::class);
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id', 'id', 'id')
            ->withPivot('value');
    }
}
