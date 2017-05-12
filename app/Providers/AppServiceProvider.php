<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Contracts\UsersInterface',
            'App\Services\UsersService'
        );
        $this->app->bind(
            'App\Contracts\CallsInterface',
            'App\Services\CallsService'
        );
        $this->app->bind(
            'App\Contracts\ContactsInterface',
            'App\Services\ContactsService'
        );
        $this->app->bind(
            'App\Contracts\EmailsInterface',
            'App\Services\EmailsService'
        );
        $this->app->bind(
            'App\Contracts\PhoneNumbersInterface',
            'App\Services\PhoneNumbersService'
        );
        $this->app->bind(
            'App\Contracts\NumbersInterface',
            'App\Services\NumbersService'
        );
        $this->app->bind(
            'App\Contracts\UserNumbersInterface',
            'App\Services\UserNumbersService'
        );
    }
}
