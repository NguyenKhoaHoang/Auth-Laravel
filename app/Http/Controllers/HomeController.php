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
use App\Repositories\Criteria\PostCriteria;
use App\Repositories\Criteria\WithRelationsCriteria;
use App\Repositories\PostRepository;
use App\Services\CommentService;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
use League\Csv\CharsetConverter;
use Maknz\Slack\Client as SlackClient;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;
use League\Csv\Writer;

class HomeController extends Controller
{
    private $commentService;
    protected $repository;
    use AuthenticatesUsers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function login(Request $request)
    {
        $token = auth('user')->attempt(['email' => $request->email, 'password' => $request->password]);
        return $token;
    }
    public function __construct(CommentService $commentService, PostRepository $repository)
    {
        // $this->middleware('auth');
        $this->commentService = $commentService;
        $this->repository = $repository;
    }

    public function post()
    {
        $this->repository->popCriteria(PostCriteria::class);
        $this->repository->pushCriteria(new WithRelationsCriteria('comments', $this->repository->allowRelation()));
        $posts = $this->repository->all();
        return response()->json($posts);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // throw new Exception('Error r???i');
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

        return redirect()->route('home')->with('status', 'C???p nh???t th??nh c??ng!');
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

    public function storeCSV()
    {
        $converter = (new CharsetConverter())->inputEncoding('UTF-8')->outputEncoding('SJIS');
        // $header = ['first name', 'last name', 'email'];
        // $records = [
        //     [1, 2, 3],
        //     ['foo', 'bar', 'baz'],
        //     ['john', 'doe', 'john.doe@example.com'],
        // ];

        // //load the CSV document from a string
        // $csv = Writer::createFromString();

        // //insert the header
        // $csv->insertOne($header);

        // //insert all the records
        // $csv->insertAll($records);

        // $csvContent = $csv->toString();
        // Storage::put('public/csv/test.csv', $csv);
        // dd($path);
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
            ->with('status', 'T???o comment th??nh c??ng!')
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

    public function smtpEmail(Request $request)
    {
        // require base_path("vendor/autoload.php");
        // $mail = new PHPMailer(true);
        // try {
        //     // Email server settings
        //     $mail->SMTPDebug = 0;
        //     $mail->isSMTP();
        //     $mail->Host = config('mail.mailers.smtp.host');             //  smtp host
        //     $mail->SMTPAuth = true;
        //     $mail->Username = config('mail.mailers.smtp.username');   //  sender username
        //     $mail->Password = config('mail.mailers.smtp.password');       // sender password
        //     $mail->SMTPSecure = config('mail.mailers.smtp.encryption');                  // encryption - ssl/tls
        //     $mail->Port = config('mail.mailers.smtp.port');
        //     $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
        //     $mail->addAddress($request->emailRecipient);
        //     $mail->isHTML(true);                // Set email content format to HTML
        //     $mail->Subject = $request->emailSubject;
        //     $mail->Body    = $request->emailBody;
        //     if (!$mail->send()) {
        //         return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);
        //     } else {
        //         return back()->with("success", "Email has been sent.");
        //     }
        // } catch (MailException $e) {
        //     return back()->with('error', 'Message could not be sent.');
        // }

        $now = Carbon::now(config('common.timezone_jp'));
        // dd($now);
        $test2 = Carbon::parse('2022-11-01 03:46:37');
        // dd($test2);
        $test = $now->copy()->diffInDays($test2);
        dd($test);
    }

    public function email()
    {
        return view("mail.smtp");
    }
}
