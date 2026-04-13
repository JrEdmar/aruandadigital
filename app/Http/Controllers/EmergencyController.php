<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyController extends Controller
{
    /**
     * Dispara um alerta de emergência espiritual.
     * Notifica o dirigente da casa do usuário.
     *
     * TODO: implementar notificação push / e-mail para o dirigente.
     */
    public function trigger(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();

        // Busca a casa ativa do usuário
        $house = $user->houses()->where('status', 'active')->first();

        if ($house) {
            // Cria notificação para o dirigente da casa
            $dirigentes = $house->members()
                ->wherePivot('role', 'dirigente')
                ->wherePivot('status', 'active')
                ->get();

            foreach ($dirigentes as $dirigente) {
                \App\Models\Notification::create([
                    'user_id' => $dirigente->id,
                    'title'   => '🚨 Alerta de Emergência',
                    'body'    => $user->name . ' enviou um alerta espiritual: ' . ($request->message ?? 'Preciso de ajuda urgente.'),
                    'type'    => 'emergency',
                ]);
            }
        }

        // Cria registro para o próprio usuário
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title'   => 'Alerta enviado',
            'body'    => 'Seu pedido de ajuda foi enviado ao dirigente.',
            'type'    => 'emergency',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alerta enviado. O dirigente será notificado.',
        ]);
    }
}
