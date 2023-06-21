<?php

namespace App\Providers;

use App\Models\School;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        if (Schema::hasTable('schools')) View::share('school_selectors', School::get());

        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        Schema::defaultStringLength(191);
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
