<?php

namespace App\Http\Controllers\Query;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('query.dashboard');
        }
        return view('query.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->role !== 'query_user') {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'ليست لديك صلاحية الدخول لهذه المنطقة.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('query.dashboard'));
        }

        return back()->withErrors([
            'username' => 'اسم المستخدم أو كلمة المرور غير صحيحة.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('query.login');
    }
}
