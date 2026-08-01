<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Form;
use App\Policies\FormPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Form::class => FormPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
