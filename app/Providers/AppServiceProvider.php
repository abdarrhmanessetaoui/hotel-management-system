<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Review;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {

        View::composer('admin.*', function ($view) {
            $view->with('AdminView', true);
        });

        View::composer('superadmin.*', function ($view) {
            $view->with('AdminView', true);
        });

        View::composer('sections.testimonial', function ($view) {
            $view->with('acceptedReviews', Review::accepted()->latest()->get());
        });

        if ($this->app->environment('production')) {
            $this->app['request']->server->set('HTTPS', 'on');
            URL::forceScheme('https');
        }
    }
}