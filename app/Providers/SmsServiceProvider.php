<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client(config('twilio.account_sid'), config('twilio.token'));
        });

        $this->app->alias(Client::class, 'sms');

    }
}
