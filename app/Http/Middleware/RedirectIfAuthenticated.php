<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Проверяем, является ли запрос из админки
                $isDashboard = $request->is('dashboard*');
                
                // Логируем информацию для отладки
                Log::info('RedirectIfAuthenticated info:', [
                    'isDashboard' => $isDashboard,
                    'path' => $request->path(),
                    'url' => $request->url(),
                    'userRole' => $user->roles->pluck('name')->toArray(),
                    'hasEmployee' => $user->employee !== null
                ]);
                
                // Проверка, имеет ли пользователь доступ к админ-панели
                $hasAdminAccess = $user->hasRole(['admin', 'moderator', 'senior-manager', 'manager', 'junior-manager', 
                                      'senior-mechanic', 'mechanic', 'junior-mechanic', 
                                      'senior-accountant', 'accountant', 'junior-accountant']) || $user->employee !== null;
                
                if ($isDashboard) {
                    // Если запрос из админки, проверяем доступ
                    if (!$hasAdminAccess) {
                        Auth::logout();
                        return redirect()->route('login')->with('error', 'У вас нет доступа к административной панели.');
                    }
                    return redirect('/dashboard/home');
                } else {
                    // Обычный вход
                    if ($user->hasRole('client') || !$hasAdminAccess) {
                        // Если пользователь клиент или не имеет доступа к админке,
                        // перенаправляем на клиентскую часть
                        return redirect()->route('home');
                    } else {
                        // Для админов и сотрудников перенаправляем на админ-панель
                        return redirect('/dashboard/home');
                    }
                }
            }
        }

        return $next($request);
    }
}
