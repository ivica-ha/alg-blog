<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\services\RecentPostsService;
use Illuminate\Foundation\AliasLoader;

class RecentPostProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias('RecentPosts', 'App\Facades\RecentPosts');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('recentposts', function($app){
          return new RecentPostsService($app->view);
        });
    }
}
