<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class CommentCacheListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CommentCreated  $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {
        
        Cache::forget('comments');
        Cache::forever('comments', Comment::all());
    }
}
