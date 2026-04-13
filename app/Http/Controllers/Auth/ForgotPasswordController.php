<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /** Exibe o formulário de recuperação de senha. */
    public function showForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Envia o link de redefinição de senha.
     *
     * Por segurança, sempre exibe mensagem de sucesso, independente
     * de o e-mail existir ou não (proteção contra enumeração de usuários).
     */
    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Tenta enviar o link; ignora o resultado para não vazar informação
        Password::sendResetLink($request->only('email'));

        return back()->with(
            'success',
            'Se este e-mail estiver cadastrado, você receberá um link de redefinição em breve.'
        );
    }
}
