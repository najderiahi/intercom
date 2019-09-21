<?php

namespace App\Providers;

use App\Policies\AnnoncePolicy;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Annonce::class => AnnoncePolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::cookie('intercom_token');

        Gate::define('is-admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('is-active', function ($user) : bool {
            return $user->active == 1;
        });
    }
}
