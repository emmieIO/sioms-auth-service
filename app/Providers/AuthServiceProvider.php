<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        VerifyEmail::toMailUsing(function($notifiable, $url){
            return (new MailMessage)
            ->subject("Verify your email address.")
            ->greeting("Hello, user")
            ->line("welcome to smart inventory & order management system")
            ->line('Click the button below to verify your email address.')
            ->action('Verify Email', $url);
        });

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return url("http://localhost:5000/api/auth/reset-password?token={$token}&email={$notifiable->email}");
        });
    }
}
