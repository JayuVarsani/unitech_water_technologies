<?php

declare(strict_types=1);

namespace App\Src\Admin\Infrastructure;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../Modules', 'admin');
    }
}
