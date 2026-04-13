<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de verificação de role.
 *
 * Uso nas rotas:
 *   middleware('role:admin')
 *   middleware('role:dirigente,admin')
 *
 * Aceita múltiplos roles separados por vírgula (OR lógico).
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Usuário não autenticado → redireciona para login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Verifica se o role do usuário está entre os permitidos
        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
