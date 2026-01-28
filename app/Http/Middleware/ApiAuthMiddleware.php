<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in via API
        if (!session('logged_in') || !session('access_token')) {
            return redirect('/login-admin')->with('error', 'Silakan login terlebih dahulu');
        }

        // Optional: Validate token with external API
        // This could be implemented if the external API has a token validation endpoint
        
        return $next($request);
    }
}
