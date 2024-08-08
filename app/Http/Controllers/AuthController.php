<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->hasRole('administrateurs')) {
            return redirect('/admin/dashboard');
        }
    
        if ($user->hasRole('manager')) {
            return redirect('/manager/dashboard');
        }
    
        if ($user->hasRole('responsables RH')) {
            return redirect('/rh/dashboard');
        }
    
        return redirect('/dashboard');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

   
}
