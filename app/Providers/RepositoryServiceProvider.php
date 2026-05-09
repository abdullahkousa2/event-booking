<?php

namespace App\Providers;

use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Eloquent\EventRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventRepositoryInterface::class,   EventRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(UserRepositoryInterface::class,    UserRepository::class);
    }
}
