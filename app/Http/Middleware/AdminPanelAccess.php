<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->hasRole('client')) {
            return $next($request);
        }
        
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'У вас нет доступа к административной панели.');
        }
        
        return redirect()->route('dashboard.login');
    }
} 