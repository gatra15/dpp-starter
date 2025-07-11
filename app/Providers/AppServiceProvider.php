<?php

namespace App\Providers;

use App\Models\Booking;
use App\Policies\BookingPolicy;

use App\Repositories\LogRepository;
use App\Repositories\RoleRepository;

use App\Repositories\RoomRepository;
use App\Repositories\UserRepository;

use App\Repositories\StatusRepository;
use App\Repositories\UrusanRepository;

use App\Repositories\BookingRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\FacilityRepository;
use App\Contracts\LogRepositoryInterface;

use App\Contracts\RoleRepositoryInterface;
use App\Contracts\RoomRepositoryInterface;

use App\Contracts\UserRepositoryInterface;
use App\Repositories\DepartmentRepository;

use App\Contracts\StatusRepositoryInterface;
use App\Contracts\UrusanRepositoryInterface;

use App\Contracts\BookingRepositoryInterface;
use App\Contracts\FacilityRepositoryInterface;
use App\Contracts\DepartmentRepositoryInterface;

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
        $this->app->bind(FacilityRepositoryInterface::class, FacilityRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
    }

    protected $policies = [
        Booking::class => BookingPolicy::class,
    ];

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
