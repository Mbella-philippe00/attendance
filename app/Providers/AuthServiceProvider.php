<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Absence;
use App\Policies\AbsencePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Absence::class => AbsencePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
