<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Client;
use App\Models\Article;
use App\Policies\UserPolicy;
use App\Policies\ClientPolicy;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Client::class => ClientPolicy::class,
        Article::class => ArticlePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
