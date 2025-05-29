<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\IAuthRepository;
use App\Repositories\Transfer\ExternalAuthRepository;
use App\Repositories\Transfer\IExternalAuthRepository;
use App\Repositories\Transfer\ITransferRepository;
use App\Repositories\Transfer\TransferRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            IExternalAuthRepository::class,
            ExternalAuthRepository::class
        );
        $this->app->bind(
            ITransferRepository::class,
            TransferRepository::class
        );
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );
        $this->app->bind(
            IAuthRepository::class,
            AuthRepository::class
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
