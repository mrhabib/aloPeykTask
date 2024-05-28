<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Notifications\NotificationService;
use App\Notifications\Providers\SendGridEmailProvider;
use App\Notifications\Providers\SesEmailProvider;
use App\Notifications\Providers\TwilioSmsProvider;
use App\Notifications\Providers\PlivoSmsProvider;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService([
                'email' => [new SendGridEmailProvider(), new SesEmailProvider()],
                'sms' => [new TwilioSmsProvider(), new PlivoSmsProvider()],
            ]);
        });
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //class_alias(\Illuminate\Support\Facades\Log::class, 'Log');
    }
}
