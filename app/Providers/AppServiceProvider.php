<?php

namespace App\Providers;

use App\Support\Auth\BinaryDatabaseSessionHandler;
use App\Support\Auth\BinaryUuidUserProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
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
        Auth::provider('binary-uuid', fn ($app, array $config) => new BinaryUuidUserProvider($app['hash'], $config['model']));
        Session::extend('binary-database', fn ($app) => new BinaryDatabaseSessionHandler(
            $app['db']->connection(config('session.connection')),
            config('session.table'),
            config('session.lifetime'),
            $app,
        ));

        VerifyEmail::createUrlUsing(fn ($user) => URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60),
            ['id' => $user->uuid(), 'hash' => sha1($user->getEmailForVerification())],
        ));

        RateLimiter::for('public-auth', function (Request $request): array {
            $email = is_string($request->input('email')) ? mb_strtolower(trim($request->input('email'))) : '';

            return [
                Limit::perMinute(20)->by('ip:'.$request->ip()),
                Limit::perMinute(5)->by('account:'.hash('sha256', $email).'|'.$request->ip()),
            ];
        });
        RateLimiter::for('oauth', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
    }
}
