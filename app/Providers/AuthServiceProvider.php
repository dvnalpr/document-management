<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\DocumentLoan;
use App\Policies\DocumentLoanPolicy;
use App\Policies\DocumentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Document::class => DocumentPolicy::class,
        DocumentLoan::class => DocumentLoanPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate::define('manage-users', function ($user) {
        //     return $user->hasRole('admin');
        // });
    }
}
