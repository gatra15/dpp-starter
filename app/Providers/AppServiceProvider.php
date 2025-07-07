<?php

namespace App\Providers;

use App\Contracts\UrusanRepositoryInterface;
use App\Repositories\UrusanRepository;

use App\Contracts\RoleRepositoryInterface;
use App\Repositories\RoleRepository;

use App\Contracts\DepartmentRepositoryInterface;
use App\Repositories\DepartmentRepository;

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
        $this->app->bind(LogRepositoryInterface::class, LogRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(UrusanRepositoryInterface::class, UrusanRepository::class);
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
