<?php

namespace App\Providers;

use App\Support\Managers\AuthSupportManager;
use App\Support\Managers\CommonSupportManager;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('authSupport', function () {
            return new AuthSupportManager();
        });
        $this->app->bind('commonSupport', function () {
            return new CommonSupportManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
