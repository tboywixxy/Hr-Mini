<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\EnsureAdmin;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(Router $router): void
    {
        $router->aliasMiddleware('admin', EnsureAdmin::class);
    }
}
