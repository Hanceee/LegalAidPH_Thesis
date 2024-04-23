<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('LegalAidPH Verify Email Address')
                ->greeting('Kumusta! Family law, dito tayo!')
                ->line('Please click the button below to verify your email address.')
                ->action('Verify Email Address', $url)
                ->line('If you did not create an account, no further action is required.')
                ->from('legalaidph_2024@gmail.com', 'LegalAidPH');

        });

        // ResetPassword::toMailUsing(function (object $notifiable, string $url) {
        //     return (new MailMessage)
        //         ->subject('LegalAidPH Reset Password')
        //         ->greeting('Kumusta! Family law, dito tayo!')
        //         ->line('You are receiving this email because we received a password reset request for your account')
        //         ->action('Reset Password', $url)
        //         ->line('This password reset link will expire in 60 minutes.')
        //         ->line('If you did not request a password reset, no further action is required.')
        //        ->from('legalaidph_2024@gmail.com', 'LegalAidPH');

        // });

    }


}
