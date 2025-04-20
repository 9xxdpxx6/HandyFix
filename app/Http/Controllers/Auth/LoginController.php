<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        // Проверяем, является ли запрос из админки
        $isDashboard = $request->is('dashboard*');
        
        if ($isDashboard) {
            return view('auth.login', ['isDashboard' => true]);
        }
        
        return view('auth.login', ['isDashboard' => false]);
    }
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Проверяем, является ли запрос из админки
        $isDashboard = $request->is('dashboard*');
        
        // Записываем отладочную информацию
        Log::info('Auth info:', [
            'isDashboard' => $isDashboard,
            'path' => $request->path(),
            'url' => $request->url(),
            'fullUrl' => $request->fullUrl(),
            'userRole' => $user->roles->pluck('name')->toArray(),
            'hasEmployee' => $user->employee !== null
        ]);
        
        // Проверяем referer для определения источника запроса
        $referer = $request->header('referer');
        $isDashboardReferer = $referer && strpos($referer, '/dashboard/') !== false;
        
        Log::info('Referer info:', [
            'referer' => $referer,
            'isDashboardReferer' => $isDashboardReferer
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
            return redirect()->intended('/dashboard/home');
        } else {
            // Обычный вход
            if ($user->hasRole('client') || !$hasAdminAccess) {
                // Если пользователь клиент или не имеет доступа к админке,
                // перенаправляем на клиентскую часть
                return redirect()->intended(route('home'));
            } else {
                // Для админов и сотрудников перенаправляем на админ-панель
                return redirect('/dashboard/home');
            }
        }
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $isDashboard = $request->is('dashboard*');
        
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        if ($isDashboard) {
            return redirect('/dashboard/login');
        }
        
        return redirect('/login');
    }
}
