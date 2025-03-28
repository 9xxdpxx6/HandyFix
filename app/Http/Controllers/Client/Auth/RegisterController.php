<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LoyaltyLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function showRegistrationForm()
    {
        return view('client.auth.register');
    }
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        // Создаем пользователя
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);
        
        // Присваиваем роль "client"
        $user->assignRole('client');
        
        // Создаем клиента
        Customer::create([
            'user_id' => $user->id,
            'loyalty_points' => 0,
            'info' => $request->info ?? null,
        ]);
        
        // Авторизуем пользователя
        Auth::login($user);
        
        return redirect()->route('home');
    }
} 