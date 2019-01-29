<?php

namespace App\Providers\Front;

use App\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class IndexLayoutProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('front.layouts.notifications', function ($view) {
            $data['unReadNotifications'] = !auth()->check() ? :auth()->user()->allNotifications()->whereNull('read_at')->count() ;
            $data['allNotifications'] = !auth()->check() ? :auth()->user()->allNotifications ;
            $view->with($data);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
