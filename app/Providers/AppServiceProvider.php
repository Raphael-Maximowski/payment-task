<?php

namespace App\Providers;

use App\Repositories\Contracts\FraudeServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Eloquent\PaymentRepository;
use App\Services\FraudeDetectionService;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class,
        );

        $this->app->bind(
            FraudeServiceInterface::class,
            FraudeDetectionService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
