<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Controller de autenticação via redes sociais (OAuth).
 *
 * ATENÇÃO: Requer a instalação do pacote Socialite:
 *   composer require laravel/socialite
 *
 * E configurar em config/services.php:
 *   'google' => [
 *       'client_id'     => env('GOOGLE_CLIENT_ID'),
 *       'client_secret' => env('GOOGLE_CLIENT_SECRET'),
 *       'redirect'      => env('GOOGLE_REDIRECT_URI'),
 *   ],
 *   'facebook' => [
 *       'client_id'     => env('FACEBOOK_CLIENT_ID'),
 *       'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
 *       'redirect'      => env('FACEBOOK_REDIRECT_URI'),
 *   ],
 */
class SocialAuthController extends Controller
{
    // -------------------------------------------------------------------------
    // Google
    // -------------------------------------------------------------------------

    /** Redireciona para a tela de autenticação do Google. */
    public function redirectGoogle(): RedirectResponse
    {
        // composer require laravel/socialite
        try {
            return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Login com Google não está disponível no momento.');
        }
    }

    /** Processa o callback do Google, busca ou cria o usuário. */
    public function handleGoogle(): RedirectResponse
    {
        try {
            $socialUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name'             => $socialUser->getName(),
                    'google_id'        => $socialUser->getId(),
                    'avatar'           => null, // URL externa, não salva localmente
                    'role'             => 'visitante',
                    'lgpd_accepted_at' => now(),
                    'password'         => bcrypt(\Illuminate\Support\Str::random(32)),
                ]
            );

            // Atualiza google_id se ainda não estiver salvo
            if (! $user->google_id) {
                $user->update(['google_id' => $socialUser->getId()]);
            }

            Auth::login($user, true);

            return $this->redirectByRole($user->role);
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Não foi possível autenticar com o Google. Tente novamente.');
        }
    }

    // -------------------------------------------------------------------------
    // Facebook
    // -------------------------------------------------------------------------

    /** Redireciona para a tela de autenticação do Facebook. */
    public function redirectFacebook(): RedirectResponse
    {
        try {
            return \Laravel\Socialite\Facades\Socialite::driver('facebook')->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Login com Facebook não está disponível no momento.');
        }
    }

    /** Processa o callback do Facebook, busca ou cria o usuário. */
    public function handleFacebook(): RedirectResponse
    {
        try {
            $socialUser = \Laravel\Socialite\Facades\Socialite::driver('facebook')->user();

            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name'             => $socialUser->getName(),
                    'facebook_id'      => $socialUser->getId(),
                    'role'             => 'visitante',
                    'lgpd_accepted_at' => now(),
                    'password'         => bcrypt(\Illuminate\Support\Str::random(32)),
                ]
            );

            if (! $user->facebook_id) {
                $user->update(['facebook_id' => $socialUser->getId()]);
            }

            Auth::login($user, true);

            return $this->redirectByRole($user->role);
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Não foi possível autenticar com o Facebook. Tente novamente.');
        }
    }

    // -------------------------------------------------------------------------
    // Privado
    // -------------------------------------------------------------------------

    private function redirectByRole(string $role): RedirectResponse
    {
        return match (true) {
            in_array($role, ['admin', 'moderador'])      => redirect('/admin'),
            in_array($role, ['dirigente', 'assistente']) => redirect('/my-house'),
            in_array($role, ['loja', 'loja_master'])     => redirect('/seller'),
            default                                       => redirect('/'),  // visitante, membro
        };
    }
}
