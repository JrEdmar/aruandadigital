<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /** Exibe o formulário de login. */
    public function showForm(): View
    {
        return view('auth.login');
    }

    /** Autentica o usuário e redireciona conforme o role. */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectByRole();
        }

        // Falha de autenticação — flash para SweetAlert2
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'E-mail ou senha incorretos. Verifique suas credenciais.');
    }

    /** Encerra a sessão e redireciona para /login. */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // -------------------------------------------------------------------------
    // Privado
    // -------------------------------------------------------------------------

    /** Retorna o redirect adequado ao role do usuário autenticado. */
    private function redirectByRole(): RedirectResponse
    {
        $role = Auth::user()->role;

        return match (true) {
            in_array($role, ['admin', 'moderador'])    => redirect('/admin'),
            in_array($role, ['dirigente', 'assistente']) => redirect('/my-house'),
            in_array($role, ['loja', 'loja_master'])   => redirect('/seller'),
            default                                     => redirect('/'),  // visitante, membro
        };
    }
}
