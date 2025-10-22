<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{
    public function showRegister() {

        return view('auth.register');
    }

    public function showLogin() {

        return view('auth.login');
    }

     public function register(Request $request) 
     {
           $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Create user with default 'user' role
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'user'
        ]);

        Auth::login($user);
        return redirect()->route('books.index')->with('success', 'Welcome to TMC Library!');
    }

    public function login(Request $request)
     {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($validated)) {
                $request->session()->regenerate();
                
                // Redirect based on user role
                if (auth()->user()->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->route('user.dashboard');
            }

            throw ValidationException::withMessages([
                'credentials' => 'The provided credentials do not match our records.',
            ]);

       
    }
     public function logout(Request $request)
     {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('show.login');
       
    }
}
