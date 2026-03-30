<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.login.login');
    }

    public function login(Request $request)
    {

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'cpf_cnpj' => ['nullable', 'string'],
        ]);

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        if (!empty($data['cpf_cnpj']) && Schema::hasColumn('users', 'cpf_cnpj')) {
            $credentials['cpf_cnpj'] = $data['cpf_cnpj'];
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()
            ->withErrors(['email' => 'Credenciais invalidas.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
