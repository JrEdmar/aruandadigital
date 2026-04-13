<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /** Painel administrativo global. */
    public function index(): View
    {
        $stats = [
            'users'           => User::count(),
            'visitantes'      => User::visitante()->count(),
            'houses'          => House::count(),
            'houses_pending'  => House::where('status', 'pending')->count(),
            'lojas'           => User::loja()->count(),
        ];

        $recentUsers  = User::latest()->limit(10)->get();
        $pendingHouses = House::where('status', 'pending')->latest()->get();

        $pendingTransfers = \App\Models\House::with(['members' => function($q) {
            $q->wherePivot('status', 'pending_transfer')->withPivot('role', 'status', 'message', 'role_membro');
        }])->whereHas('members', fn($q) => $q->wherePivot('status','pending_transfer'))->get();

        return view('admin.index', compact('stats', 'recentUsers', 'pendingHouses', 'pendingTransfers'));
    }

    /** Aprova uma casa pendente. */
    public function approveHouse(string $id): RedirectResponse
    {
        $house = House::findOrFail($id);
        $house->update(['status' => 'active', 'approved_at' => now()]);

        return back()->with('success', "Casa \"{$house->name}\" aprovada!");
    }

    /** Rejeita/desativa uma casa. */
    public function rejectHouse(Request $request, string $id): RedirectResponse
    {
        $house = House::findOrFail($id);
        $house->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->input('reason'),
        ]);

        return back()->with('success', "Casa \"{$house->name}\" rejeitada.");
    }

    /** Atualiza o perfil (role) de um usuário. */
    public function updateUserRole(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:visitante,membro,assistente,dirigente,loja,loja_master,moderador,admin'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $data['role']]);

        return back()->with('success', "Perfil de {$user->name} atualizado para {$data['role']}.");
    }

    /** Aprova transferência de dirigência — admin. */
    public function approveTransfer(string $houseId, string $userId): \Illuminate\Http\RedirectResponse
    {
        $house = \App\Models\House::findOrFail($houseId);

        // Demote current dirigente to membro
        $house->members()->wherePivot('role', 'dirigente')->wherePivot('status', 'active')
            ->each(fn($m) => $house->members()->updateExistingPivot($m->id, ['role' => 'membro']));

        // Promote candidate
        $house->members()->updateExistingPivot($userId, [
            'role'    => 'dirigente',
            'status'  => 'active',
            'message' => null,
        ]);

        return back()->with('success', 'Transferência de dirigência aprovada!');
    }

    /** Rejeita transferência de dirigência — admin. */
    public function rejectTransfer(string $houseId, string $userId): \Illuminate\Http\RedirectResponse
    {
        $house = \App\Models\House::findOrFail($houseId);

        $house->members()->updateExistingPivot($userId, [
            'status'  => 'active',
            'message' => null,
        ]);

        return back()->with('success', 'Transferência de dirigência rejeitada.');
    }
}
