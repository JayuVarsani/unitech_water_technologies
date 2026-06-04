<?php

declare(strict_types=1);

namespace App\Src\Api\Infrastructure;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
