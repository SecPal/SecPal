<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use App\Models\TimeTracker;
use App\Policies\CompanyPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\LocationPolicy;
use App\Policies\TimeTrackerPolicy;
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
        Company::class => CompanyPolicy::class,
        Customer::class => CustomerPolicy::class,
        Location::class => LocationPolicy::class,
        TimeTracker::class => TimeTrackerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
