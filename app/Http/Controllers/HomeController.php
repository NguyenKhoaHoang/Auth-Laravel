<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
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
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maknz\Slack\Client as SlackClient;

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
        // $this->middleware('auth');
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

    public function cache()
    {
        // Them Cache, neu da co cache co key ton tai thi xoa no va them moi
        // Cache::put('cacheKey', 'This should be a cache key', now()->addSecond(10));

        // Them Cache neu chua co key do
        // Cache::add('cacheKey', 'This should be a cache key 2', now()->addSecond(10));


        // Them cache forever, ko bao gio het han
        // Cache::forever('cacheKey2', 'nuoo');

        // Xoa tung cache
        // Cache::forget('cacheKey2');

        // Xoa het tat ca cac cache
        // Cache::flush();


        // Kiem tra xem cache co ton tai ko
        // if (Cache::has('cacheKey2')) {
        //     dd('Cache does exist');
        // }

        // DUng de tang nhung cache co value la integer, neu ko thi thay the no.
        // Cache::increment('cacheKey2', 4);
        // Cache::decrement('cacheKey2', 4);

        // dd(Cache::get('cacheKey2'));

        // $comments = cache('comments', function () {
        //     return Comment::get();
        // });

        // $comments = Cache::get('comments');

        // Neu ma co cache san roi thi lay cache do, ko thi them cache moi vao
        $comments = Cache::rememberForever('comments', function () {
            return Comment::get();
        });


        // Lay cache roi xoa cache do luon
        // $comments = Cache::pull('comments');


        return view('comments.show', compact('comments'));
        // return view('home');
    }


    public function httpClient()
    {
        // $response = Http::get('http://127.0.0.1:8001/api/user', [
        //     'name' => 'Taylor'
        // ]);

        // $response = Http::attach(
        //     'image',
        //     file_get_contents('storage/photo/test.png'),
        //     'test.png'
        // )->post('http://127.0.0.1:8001/api/user', [
        //     'name' => 'Taylor',
        //     'email' => 'nuooo'
        // ]);

        // $response = Http::withHeaders([
        //     'authorization'=>'token'
        // ])->post('http://127.0.0.1:8001/api/header');

        // dd($response->json());

        // return Http::get('http://127.0.0.1:8001/api/user')['email'];


        // -----------------------------

        // $response = Http::get('https://jsonplaceholder.typicode.com/posts');
        // $response = Http::get('https://jsonplaceholder.typicode.com/posts/5');

        // $response = Http::get('https://jsonplaceholder.typicode.com/posts', [
        //     'id' => 1
        // ]);

        // $response = Http::post('https://jsonplaceholder.typicode.com/posts', [
        //     'userId' => 1,
        //     'title'=>'nuooo',
        //     'body'=>'damn'
        // ]);

        // $response = Http::put('https://jsonplaceholder.typicode.com/posts/5', [
        //     'title' => 'Updated',
        //     'body' => 'damn bruh'
        // ]);


        // $response = Http::delete('https://jsonplaceholder.typicode.com/posts/5');



        $url = 'https://hooks.slack.com/services/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';

        // $client = new SlackClient($url);
        // $client->send('haha');

        $response = Http::withHeaders([
            'Content_type' => 'application/json'
        ])->post($url, [
            "text" => "Hello, world."
        ]);
        return redirect()->back();
        // return $response->json();
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
        $user = User::find(11);
        // $user->notifications()->delete();
        // Notification::send(Auth::user(), new WelcomNotification);

        // Auth::user()->notify(new WelcomNotification);

        Notification::send(Auth::user(), new UserFollowNotification($user));
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


    public function uploadAPI(Request $request)
    {
        // dd($request->file('file'));
        // $file = $request->file('file');
        // $filename = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
        // $result = $request->file->move('storage/APIFile', $filename);
        // return view('api.view', compact('result'));

        $rs = $request->file('file')->storeAs('public/apiDocs', $request->file('file')->getClientOriginalName());
        // $rs = Storage::putFile('apiDocs', $request->file('file')->getClientOriginalName());
        $result = Storage::url($rs);

        return view('api.view', compact('result'));
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

    protected function storeImage(Request $request)
    {
        // $path = $request->file('photo')->store('public/photo');
        // return substr($path, strlen('public/'));

        $rs = $request->file('photo')->storeAs('public/photo', $request->file('photo')->getClientOriginalName());
        $result = Storage::url($rs);
        return $result;
    }

    public function storeComment(Request $request)
    {
        $test = $request->test_cookie;
        $minutes = 0.5;
        $test_cookie = cookie('test_cookie', $test, $minutes);
        $imageUrl = $this->storeImage($request);

        Comment::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content,
            'photo' => $imageUrl
        ]);

        // Event::dispatch(new CommentCreated());
        // event(new CommentCreated());
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
