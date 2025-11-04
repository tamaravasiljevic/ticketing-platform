<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        // Map Web Routes
        $this->mapWebRoutes();

        // Map API Routes
        $this->mapApiRoutes();
    }

    /**
     * Map the "web" routes.
     *
     * These routes receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Map the "api" routes.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api') // Adds 'api/' prefix to all routes in api.php
        ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
