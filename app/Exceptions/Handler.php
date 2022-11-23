<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use InvalidArgumentException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        InvalidArgumentException::class
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

     /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $statusCode  = 400;
        $errors      = [];
        $message     = __('messages.errors.unexpected');
        $messageCode = '';

        switch (true) {
            // case $exception instanceof JWTException:
            // case $exception instanceof TokenInvalidException:
            // case $exception instanceof TokenBlacklistedException:
            case $exception instanceof AuthenticationException:
                $message = __('messages.errors.session');
                $statusCode = 401;
                $messageCode = 'session.not_found';
                break;

            case $exception instanceof BaseException:
                // case $exception instanceof VoipException:
                // case $exception instanceof PaymentException:
                // case $exception instanceof CallingTalkException:
                $errors = $exception->getArgs();
                $message = $exception->getMessage();
                $messageCode = method_exists($exception, 'getMessageCode') ? $exception->getMessageCode() : null;
                $statusCode = $exception->getCode();
                break;

            default:
                break;
        }

        $data = [
            'success' => false,
            'data'   => $errors,
            'code'   => $messageCode,
            'message'=> $message
        ];
        return $request->is('api/*') ? response()->json($data, $statusCode) : parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // $this->renderable(function (CommentException $exception) {
        //     return response()->view('home');
        // });
    }
}
