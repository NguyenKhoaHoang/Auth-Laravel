<?php

namespace App\Providers;

use App\Models\Comment;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Comment::class => CommentPolicy::class
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::define('edit-comment', function ($user, $comment) {
        //     return $user->id == $comment->user_id;
        //     // return false;
        // });

        // Gate::define('update-comment',[CommentPolicy])

        /**
         * Kiem tra truoc khi kiem tra cac gate define khac
         * neu tra ve null thi kiem tra tiep cac gate khac
         * con neu tra ve true or false thi ko kiem tra gate khac nua
         */
        // Gate::before(function ($user, $ability) {
        //     // if($user->isSuperAdmin)
        //     if (false) {
        //         return true;
        //     } else {
        //         return null;
        //     }
        // });


        // // neu kiem tra cac gate define kia ma null thi kiem tra tiep after
        // Gate::after(function ($user, $ability, $result, $arguments) {
        //     return true;
        // });


        // Gate::resource('comments', 'CommentPolicy');
    }
}
