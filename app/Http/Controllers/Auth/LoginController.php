<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status === 'inactive') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun belum diaktivasi. Silakan tunggu admin menyetujui pengajuan event Anda.',
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on role, NOT intended URL
            if ($user->hasAnyRole(['super_admin', 'editor'])) {
                return redirect('/admin');
            }

            return redirect('/panel');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
