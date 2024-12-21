<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // for system-admin role
        Gate::define('system-admin', function ($user) {
            return ($user->role == 9);
        });

        // for manager role
        Gate::define('manager', function ($user) {
            return ($user->role >= 5);
        });

        // for member role
        Gate::define('member', function ($user) {
            return ($user->role >= 1);
        });
    }
}
