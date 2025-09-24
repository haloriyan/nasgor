<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route as RouteFacade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $request = request();

        // If path is /staging/*
        if ($request->is('staging') || $request->is('staging/*')) {
            Config::set('database.default', 'mysql_staging');
            Config::set('session.connection', 'mysql_staging');
            Config::set('session.cookie', 'laravel_staging_session');
        } else {
            // Production defaults
            Config::set('database.default', 'mysql');
            Config::set('session.connection', 'mysql');
            Config::set('session.cookie', 'laravel_session');
        }

        $this->app->singleton('url', function ($app) {
            // Get existing route collection and request (or create a request fallback)
            $routes = $app['router']->getRoutes();
            $request = $app->bound('request') ? $app['request'] : Request::createFromGlobals();

            // Return an anonymous subclass of UrlGenerator that overrides route()
            return new class($routes, $request) extends BaseUrlGenerator {
                public function route($name, $parameters = [], $absolute = true)
                {
                    try {
                        // Only rewrite when there's an HTTP request and path is staging
                        if ($this->request && ($this->request->is('staging') || $this->request->is('staging/*'))) {
                            $stagingName = 'staging.' . $name;
                            if (\Illuminate\Support\Facades\Route::has($stagingName)) {
                                return parent::route($stagingName, $parameters, $absolute);
                            }
                        }
                    } catch (\Throwable $e) {
                        // If anything goes wrong, fall back to normal route resolution
                    }

                    return parent::route($name, $parameters, $absolute);
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 
    }
}
