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
        /**
         * Tham số truyền vào lần lượt là:
         * - Model lớp cần kết nối
         * - Tên Bảng trung gian
         * - Khóa ngoại ở bảng trung gian của Model hiện tại (Model Category)
         * - Khóa ngoại ở bảng trung gian của Model lớp cần kết nối (Model Post)
         * - Khóa chính ở Model hiện tại
         * - Khóa chính ở Model cần kết nối
         */
        return $this->belongsToMany(Post::class, 'category_post', 'category_id', 'post_id', 'id', 'id')
            ->withPivot('value');
    }


    public function post()
    {
        return $this->hasOne(Post::class, 'category_id', 'id');
    }

    public function posts2()
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
