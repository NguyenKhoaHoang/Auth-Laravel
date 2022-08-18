<?php

namespace App\Listeners;

use App\Events\PodcastProcessed;
use App\Jobs\SendEmailJob;
use App\Mail\HelloMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPodcastNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PodcastProcessed  $event
     * @return void
     */
    public function handle(PodcastProcessed $event)
    {
        Log::info('Hello ' . $event->name, [
            'user' => $event->user,
        ]);
        // Mail::to("blcm2486@gmail.com")
        //     ->send(new HelloMail($event->user));
        SendEmailJob::dispatch($event->user);
    }

    public function failed(PodcastProcessed $event, $exc)
    {
    }
}
