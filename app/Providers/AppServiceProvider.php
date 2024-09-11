<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryImpl;
use App\Services\ArticleService;
use App\Services\ArticleServiceImpl;
use App\Repositories\ClientRepository;
use App\Repositories\ClientRepositoryImpl;
use App\Services\ClientService;
use App\Services\ClientServiceImpl;
use App\Services\CustomTokenService;
use App\Repositories\DetteRepositoryInterface;
use App\Repositories\DetteRepository;
use App\Services\DetteServiceInterface;
use App\Services\DetteService;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ArticleRepository::class, ArticleRepositoryImpl::class);
        $this->app->bind(ArticleService::class, ArticleServiceImpl::class);

        $this->app->bind(ClientRepository::class, ClientRepositoryImpl::class);
        $this->app->bind(ClientService::class, ClientServiceImpl::class);

        $this->app->bind('clientRepository', function ($app) {
            return $app->make(ClientRepository::class);
        });

        $this->app->bind('clientService', function ($app) {
            return $app->make(ClientService::class);
        });

        $this->app->singleton(CustomTokenService::class, function ($app) {
            return new CustomTokenService($app->make(PersonalAccessTokenFactory::class));
        });


        $this->app->bind(DetteRepositoryInterface::class, DetteRepository::class);
        $this->app->bind(DetteServiceInterface::class, DetteService::class);
    }

    public function boot()
    {
        //
    }
}
