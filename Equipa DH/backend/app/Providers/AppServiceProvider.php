<?php

namespace App\Providers;

use App\Service\Auth\Contract\AuthInterface as AuthServiceInterface;
use App\Service\Auth\Contract\ProfileInterface as ProfileServiceInterface;
use App\Service\Auth\Contract\UserInterface as UserServiceInterface;

use App\Service\Auth\Auth as AuthService;
use App\Service\Auth\Profile as ProfileService;
use App\Service\Auth\User as UserService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
