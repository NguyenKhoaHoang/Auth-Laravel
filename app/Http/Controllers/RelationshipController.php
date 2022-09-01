<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    /**
     * All relationship
     */
    public function avatar()
    {
        $user = User::find(11);

        $avatar = Avatar::find(1);
        echo $avatar->user->name;

        dd($user->avatar);
    }

    public function posts()
    {
        $user = User::find(11);


        dd($user->orderByDescPost);
    }

    public function categories()
    {
        $category = Category::find(1);

        $post = Post::find(1);
        dd($post->categories);
        // dd($category->posts);
    }

    public function categoryAttach()
    {
        $post = Post::find(1);
        $post->categories()->attach([2, 3]);
        return redirect()->route('relationship.categories');
    }

    public function categoryDetach()
    {
        $post = Post::find(1);
        $post->categories()->detach([2, 3]);
        return redirect()->route('relationship.categories');
    }

    public function categorySync()
    {
        $post = Post::find(1);
        // $post->categories()->sync([2, 3]);
        $post->categories()->sync([
            2 => ['value' => "Nguyen"],
            3 => [
                'value' => "Hi",
                // 'value1' => "Hi2",
            ]
        ]);
        return redirect()->route('relationship.categories');
    }

    public function categoryPivot()
    {
        $post = Post::find(1);
        foreach ($post->categories as $category) {
            echo $category->pivot->value . "<br>";
        }

        dd($post);
        // return redirect()->route('relationship.categories');
    }

    public function categoryPost()
    {
        $user = User::find(11);

        // dd($user->category->post);
        // dd($user->categoryPost);
        dd($user->categoryPosts);

        // $posts = Post::with('user')->get();
        // // $posts = Post::get();
        // foreach ($posts as $post) {
        //     echo $post->user->name;
        // }
    }

    public function polyOneOne()
    {
        // $user = User::find(11);
        // dd($user->image);

        $post = Post::find(1);
        dd($post->image);

        $image = Image::find(2);
        dd($image->imageable);
    }

    public function polyOneMany()
    {
        $post = Post::find(2);
        return response()->json($post->comments);
        // dd($post->comments);

        // $image = Image::find(1);
        // return response()->json($image->comments);
        // echo $post->comments;
    }

    public function polyOneCreate()
    {
        $post = Post::find(2);

        $image = new Image([
            'url' => 'url post'
        ]);
        $post->image()->save($image);

        $comment = new Comment([
            'content' => 'content 3',
            'user_id' => 11
        ]);
        $post->comments()->save($comment);

        // $post->comments()->createMany([
        //     [
        //         'content'=>'content 3',
        //         'user_id'=>11
        //     ],
        //     [
        //         'content'=>'content 4',
        //         'user_id'=>11
        //     ]
        // ]);
        return true;
    }

    public function polyManyCreate()
    {
        // cach 1
        // $tag1 = new Tag([
        //     'name' => 'tag1'
        // ]);

        // $tag2 = new Tag([
        //     'name' => 'tag2'
        // ]);
        // $post = Post::find(2);
        // $post->tags()->saveMany([$tag1, $tag2]);

        // cach 2
        $tag1 = Tag::find(1);
        $tag2 = Tag::find(2);

        $post = Post::find(1);

        $category = Category::find(3);

        // attach/detach/sync
        $post->tags()->sync([
            $tag1->id,
            $tag2->id
        ]);

        $category->tags()->sync([
            $tag1->id
        ]);
        return true;
    }

    public function polyManyMany()
    {
        $post = Post::find(2);
        $tag = Tag::find(1);
        dd($tag->categories);

        // dd($post->tags);
    }

    /**
     * Eager Loading
     */

    public function allPost()
    {
        // $posts = Post::all();
        $posts = Post::with([
            'user.image',
            'categories' => function ($query) {
                $query->whereNull('created_at')->with(['posts', 'tags']);
            }
        ])->get();


        // $posts = Post::with('user')->get();
        // Chi lay so luong cua category
        // $posts = Post::with('user:id,name')->withCount('categories')->get();

        // load user.image tu cac doi tuong Post co tu truoc
        // $posts = $this->getAllPost()->load('user.image');
        // dd($posts);
        return view('relationship.allPost', compact('posts'));
    }

    private function getAllPost()
    {
        return Post::all();
    }

    public function imageEagerMorph()
    {
        $comments = Comment::with(['commentable' => function (MorphTo $morphTo) {
            $morphTo->constrain([
                Post::class => function (Builder $query) {
                    $query->where('category_id', 1)->where('name');
                }
            ])->morphWith([
                Post::class => ['user'],
                Image::class
            ]);
        }])->get();

        // $comments = Comment::with('commentable');

        // $comments = Comment::all();
        // dd($comments);

        return view('relationship.allComments', compact('comments'));
    }

    /**
     * Condition Relationship
     */
    public function conditionRelationship()
    {
        // Lấy ra những User nào có bài viết Post
        // $user = User::has('posts')->get();
        // dd($user);

        // Lấy ra các User có Post thuộc category_id = 1
        // $user = User::whereHas('posts', function ($query) {
        //     $query->where('category_id', 1);
        // })->get();
        // dd($user);

        // Dùng biến ở ngoài truyền vào bên trong điều kiện quan hệ
        // $post_name = 'name 1';
        // $post_name2 = 'name 2';
        // $user = User::whereHas('posts', function ($query) use ($post_name, $post_name2) {
        //     $query->where('category_id', 1)->where('name', $post_name2)->orWhere('name', $post_name);
        // })->get();
        // dd($user);

        // Where quan hệ lồng nhau
        // $user = User::whereHas('posts', function ($query) {
        //     $query->whereHas('category', function ($query1) {
        //         $query1->where('user_id', 11);
        //     });
        // })->get();
        // dd($user);

        // Lấy ra các User có Post thuộc category_id = 1
        // $user = User::whereRelation('posts', 'category_id', 1)->get();
        // dd($user);

        // // Lấy ra những User không có Post nào
        // $user = User::doesntHave('posts')->get();
        // dd($user);

        // Lấy ra những User ko có Post category_id = 1
        // $user = User::wheredoesntHave('posts', function (Builder $query) {
        //     $query->where('category_id', 1)->orWhere('category_id', 2);
        // })->get();
        // dd($user);

        // Lấy ra những User mà có Post ko thuộc về Category mà có user_id là 11
        // $user = User::query()->whereDoesntHave('posts.category', function (Builder $query) {
        //     $query->where('user_id', 11);
        // })->get();
        // dd($user);

            
        $comments = Comment::query()->whereHasMorph(
            'commentable',
            [Post::class, Image::class],
            // Image::class,
            // '*',
            // function (Builder $query) {
            //     $query->whereNot('created_at', null);
            // }
            function (Builder $query, $type) {
                $column = $type === Post::class ? 'name' : 'url';
         
                $query->where($column, 'name 1');
            }
        )->get();

        dd($comments);
    }
}
