<?php

namespace App\Providers;

use App\Contracts\LogRepositoryInterface;
use App\Repositories\LogRepository;

use App\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;

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
        // Binding
        $this->app->bind(\App\Contracts\BaseRepositoryInterface::class, \App\Repositories\BaseRepository::class);
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
