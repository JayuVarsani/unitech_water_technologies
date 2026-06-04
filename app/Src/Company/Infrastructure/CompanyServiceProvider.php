<?php

declare(strict_types=1);

namespace App\Src\Company\Infrastructure;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Resources\Panel\Components\Notification;

class CompanyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../Modules', 'company');
        Livewire::component(Notification::class);
    }
}
