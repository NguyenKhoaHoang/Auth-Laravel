<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use App\Models\Category;
use App\Models\Post;
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
}
