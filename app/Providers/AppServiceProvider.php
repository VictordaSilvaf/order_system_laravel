<?php

namespace App\Providers;

use App\Domain\Order\Repositories\OrderRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentOrderRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrderRepository::class,
            EloquentOrderRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
