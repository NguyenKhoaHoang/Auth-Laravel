<?php

namespace App\Providers;

use App\Events\CommentCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\DemoEvent;
use App\Events\PodcastProcessed;
use App\Listeners\CommentCacheListener;
use App\Listeners\DemoListener;
use App\Listeners\SendPodcastNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

use function Illuminate\Events\queueable;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        DemoEvent::class => [
            DemoListener::class
        ],

        CommentCreated::class => [
            CommentCacheListener::class
        ]

        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Event::listen(
        //     PodcastProcessed::class,
        //     [SendPodcastNotification::class, 'handle']
        // );

        Event::listen(PodcastProcessed::class, SendPodcastNotification::class);
        // Event::listen(function (PodcastProcessed $event) {
        //     Log::info('Hello Hoang');
        // });

        // Event::listen(queueable(function (PodcastProcessed $event) {
        //     Log::info("Hello Hoang ne 4");
        // })->onConnection('redis')
        //     ->onQueue("podcast")
        //     ->delay(now()->addSecond(10)));

        // Event::listen(queueable(function (PodcastProcessed $event) {
        //     Log::info("Hello Hoang ne 4");
        // })->catch(function (PodcastProcessed $event, Throwable $e) {
        // }));
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
