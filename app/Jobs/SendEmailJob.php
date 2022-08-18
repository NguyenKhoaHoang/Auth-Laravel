<?php

namespace App\Jobs;

use App\Mail\HelloMail;
use App\Notifications\SendNotificationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        // $this->delay = now()->addSecond(20);
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Mail::to("blcm2486@gmail.com")
        //     ->send(new HelloMail($this->user));

        Notification::route('mail', ['blcm2486@gmail.com'])
            ->notify(new SendNotificationEmail($this->user));
    }
}
