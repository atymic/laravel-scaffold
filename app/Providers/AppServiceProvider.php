<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Date::use(CarbonImmutable::class);
    }

    public function register()
    {
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });

        Inertia::share('app.name', Config::get('app.name'));

        Inertia::share('errors', function () {
            return Session::get('errors') ? Session::get('errors')->getBag('default')->getMessages() : (object) [];
        });

        Inertia::share('auth.user', function () {
            if ($user = Auth::user()) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }

            return null;
        });

        Inertia::share('app', function () {
            return [
                'name' => config('app.name'),
            ];
        });
    }
}
