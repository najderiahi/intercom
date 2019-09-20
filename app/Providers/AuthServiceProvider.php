<?php

namespace App\Providers;

use App\Policies\AnnoncePolicy;
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
        Annonce::class => AnnoncePolicy::class
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

        Gate::define('update-user', function ($user, $other) {
            return $user->id == $other->id;
        });

        Gate::define('is-admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('is-active', function ($user) : bool {
            return $user->active == 1;
        });
    }
}
