<?php

namespace App\Providers;

use App\Models\Hydrator;
use App\Models\repositories\CategoryRepository;
use App\Models\repositories\FeedRepository;
use App\Models\repositories\NewsRepository;
use App\Models\repositories\NewsSiteRepository;
use App\Models\repositories\SourceRepository;
use App\Models\services\FeedService;
use App\Models\Rss;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Hydrator::class);

        $this->app->singleton( CategoryRepository::class, function ($app) {
            return new CategoryRepository(app(Hydrator::class));
        });


        $this->app->singleton( FeedRepository::class, function ($app) {
            return new FeedRepository(app(Hydrator::class), app(CategoryRepository::class), app(NewsSiteRepository::class));
        });

        $this->app->singleton( NewsRepository::class);



    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        date_default_timezone_set('Europe/Moscow');
    }
}
