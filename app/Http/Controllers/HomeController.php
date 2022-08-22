<?php

namespace App\Http\Controllers;

use App\Events\PodcastProcessed;
use App\Exceptions\CommentException;
use App\Mail\HelloMail;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\InvoicePaid;
use App\Notifications\SmSNotification;
use App\Notifications\UserFollowNotification;
use App\Notifications\WelcomNotification;
use App\Services\CommentService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    private $commentService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommentService $commentService)
    {
        $this->middleware('auth');
        $this->commentService = $commentService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // throw new Exception('Error rồi');
        // Log::channel('testlog')->info('User to home.', [
        //     'id' => Auth::user()->id
        // ]);
        // event(new PodcastProcessed('nuoooo', Auth::user()));
        // PodcastProcessed::dispatch('nuoooo ga', Auth::user());

        return view('home');
    }

    public function sendMail()
    {

        // $mailable = new HelloMail($user);
        // Mail::to("blcm2486@gmail.com")->send($mailable);
        event(new PodcastProcessed('a', Auth::user()));
        return redirect()->back();
    }

    public function notification()
    {

        // Auth::user()->notifications()->delete();
        // $user = User::find(11);
        // $user->notifications()->delete();
        Notification::send(Auth::user(), new WelcomNotification);

        // Auth::user()->notify(new WelcomNotification);

        // Notification::send(Auth::user(), new UserFollowNotification($user));
        // Notification::send(Auth::user(), new SmSNotification);
        // Notification::send(Auth::user(), new InvoicePaid);

        // $basic  = new \Vonage\Client\Credentials\Basic("c62796d9", "us1thBPvEgeuxeWf");
        // $client = new \Vonage\Client($basic);
        // $response = $client->sms()->send(
        //     new \Vonage\SMS\Message\SMS("84773412924", 'BRAND_NAME', 'A text message sent using the Nexmo SMS API')
        // );
        
        // $message = $response->current();
        
        // if ($message->getStatus() == 0) {
        //     echo "The message was sent successfully\n";
        // } else {
        //     echo "The message failed with status: " . $message->getStatus() . "\n";
        // }
        return redirect()->back();
    }

    public function markAsRead($id)
    {
        if ($id) {
            Auth::user()->unreadNotifications->where('id', $id)->markAsRead();
        }
        return redirect()->back();
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

    public function searchComment(Request $request)
    {
        try {
            $commentSearch = $this->commentService->search($request->id);
        } catch (CommentException $exception) {
            // throw $exception;
            return back()->withError($exception->getMessage())->withInput();
        }
        // $commentSearch = Comment::find($request->id);
        return view('comments.show', compact('commentSearch'));
    }

    public function createComment(Request $request)
    {
        $test = $request->cookie('test_cookie');
        return view('comments.create', compact(['test']));
    }

    public function storeComment(Request $request)
    {
        $test = $request->test_cookie;
        $minutes = 0.5;
        $test_cookie = cookie('test_cookie', $test, $minutes);

        Comment::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content
        ]);

        return redirect()->route('home')
            ->with('status', 'Tạo comment thành công!')
            ->withCookie($test_cookie);
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
