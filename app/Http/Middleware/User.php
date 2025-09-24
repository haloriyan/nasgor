<?php

namespace App\Http\Middleware;

use App\Models\User as UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $me = me();
        if ($me == null) {
            Log::info($me);
            return redirect()->route('login')->withErrors(['Mohon Login dulu sebelum melanjutkan']);
        }
        
        $route = Route::currentRouteName();
        $routes = explode(".", $route);
        $isStaging = array_search('staging', $routes);
        if ($isStaging !== false) {
            array_splice($routes, $isStaging, 1);
        }

        $exclusion = [
            'dashboard'
        ];

        if (!in_array($routes[0], $exclusion) && !in_array($routes[0], $me->permissions)) {
            return redirect()->route('errorPage', 403);
        }

        return $next($request);
    }
}
