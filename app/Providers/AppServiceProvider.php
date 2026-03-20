<?php

namespace App\Providers;

use App\Interfaces\SecretRepositoryInterface;
use App\Repositories\SecretRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(
            SecretRepositoryInterface::class,
            SecretRepository::class
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
