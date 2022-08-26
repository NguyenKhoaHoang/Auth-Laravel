<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    public function avatar()
    {
        $user = User::find(11);

        $avatar = Avatar::find(1);
        dd($avatar->user->name);

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
}
