<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Staff;
use App\Models\JobCard;
use App\Src\Company\Modules\JobCard\Policies\JobCardPolicy;
use App\Utility\Enums\CompanyTypeEnum;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        JobCard::class => JobCardPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::after(function ($user, $ability) {
            return match ($user->getmorphClass()) {
                Staff::class => $user->type === CompanyTypeEnum::Admin->value,
                default => null,
            };
        });
    }
}
