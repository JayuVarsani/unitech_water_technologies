<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Resources\Panel\Components\LogoutLink;

// use Spatie\Permission\Models\Role;
// use App\Models\Role as CustomRole;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // $this->app->bind(Role::class, CustomRole::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->isProduction()) {
            Model::preventLazyLoading();
            Mail::alwaysTo(config('mail.to'));
            Model::preventAccessingMissingAttributes();
        }
        Blade::componentNamespace('Resources\\Panel\\Components', 'panel');
        $this->loadViewsFrom(resource_path('panel/views'), 'panel');
        Livewire::component('panel.logout-link', LogoutLink::class);
    }
}
