<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function showInfo()
    {
        return view('editInfo');
    }

    public function updateInfo(Request $request)
    {
        $user = User::find(\auth()->user()->id);
        if ($request->change_password == 'on') {
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
        } else {
            $user->name = $request->name;
        }
        $user->save();

        return redirect()->route('home')->with('status', 'Cập nhật thành công!');
    }

    public function showComment()
    {
        $comments = Comment::all();
        return view('comments.show', compact('comments'));
    }

    public function createComment()
    {
        return view('comments.create');
    }

    public function storeComment(Request $request)
    {
        Comment::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content
        ]);

        return redirect()->route('home')->with('status', 'Tạo comment thành công!');
    }

    public function editComment(Request $request, $comment_id)
    {


        $comment = Comment::find($comment_id);

        // Dung gate
        // if (Gate::allows('edit-comment', $comment)) {
        //     return "Ban co quyen";
        // } else {
        //     echo "Ban ko co quyen";
        // }

        // if (Gate::denies('edit-comment', $comment)) {
        //     return 'Ban ko co quyen';
        // } else {
        //     return "Ban co quyen";
        // }


        // Dung policy
        if ($request->user()->can('update', $comment)) {
            return "Ban co quyen";
        } else {
            echo "Ban ko co quyen";
        }

        // if ($this->authorize('update', $comment)) {
        //     return "Ban co quyen";
        // } else {
        //     echo "Ban ko co quyen";
        // }
    }
}