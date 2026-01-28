<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfMultiAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek API auth session
        if (session('logged_in') && session('access_token')) {
            return redirect()->route('dashboard.index');
        }
        
        // Cek admin user terlebih dahulu
        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            return redirect()->route('dashboard.index');
        }
        
        // Cek Google user
        if (\Illuminate\Support\Facades\Auth::guard('google')->check()) {
            return redirect()->route('google.dashboard');
        }
        
        // Cek web user (jika ada)
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            return redirect()->route('dashboard.index');
        }
        
        return $next($request);
    }
}
