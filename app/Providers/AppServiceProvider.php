<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'course' => \App\Models\Course::class,
            'subject' => \App\Models\Subject::class,
        ]);

        if (env('APP_ENV') === 'production') {
            URL::forceRootUrl(config('app.url'));
            if (str_contains(config('app.url'), 'https://')) {
                URL::forceScheme('https');
            }
        }
    }
}
