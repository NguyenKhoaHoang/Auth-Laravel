<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected $with = ['posts','image'];

    public function avatar()
    {
        // return $this->hasOne('App\Models\Avatar');
        // return $this->hasOne(Avatar::class);
        return $this->hasOne(Avatar::class, 'user_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function newPost()
    {
        return $this->hasOne(Post::class, 'user_id', 'id')->latestOfMany();
    }

    public function orderByDescPost()
    {
        return $this->hasOne(Post::class, 'user_id', 'id')->orderByDesc('name');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'user_id', 'id');
    }

    public function categoryPost()
    {
        /**
         * Tham số truyền vào lần lượt là:
         * - Bảng con
         * - Bảng trung gian kết nối với bảng con
         * - Khóa ngoại của bảng cha ở bảng trung gian
         * - Khóa ngoại của bảng trung gian ở bảng con
         * - Khóa chinh của bảng cha
         * - Khóa chính của bang trung gian
         */
        return $this->hasOneThrough(Post::class, Category::class, 'user_id', 'category_id', 'id', 'id')
            ->orderByDesc('content');
    }

    public function categoryPosts()
    {
        return $this->hasManyThrough(Post::class, Category::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/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';
        // return ' https://pastebin.com/';
    }

    public function routeNotificationForVonage($notification)
    {
        return $this->phone_number;
    }
}
