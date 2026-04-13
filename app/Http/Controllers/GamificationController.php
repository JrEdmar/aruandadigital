<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GamificationController extends Controller
{
    /** Dashboard de gamificação — pontos e nível dentro da casa do usuário. */
    public function dashboard(): View|RedirectResponse
    {
        $user  = Auth::user();
        $house = $user->activeHouse();

        if (! $house) {
            return redirect()->route('houses')
                ->with('error', 'Você precisa ser membro ativo de uma casa para acessar a gamificação.');
        }

        // Pontos e nível do usuário NESTA casa (pivot)
        $pivot       = $house->pivot;
        $housePoints = $pivot->house_points ?? 0;
        $houseLevel  = $pivot->house_level  ?? 1;

        // Posição no ranking da casa
        $houseRank = $house->members()
            ->wherePivot('status', 'active')
            ->wherePivot('house_points', '>', $housePoints)
            ->count() + 1;

        $achievements = $user->achievements()->withPivot('earned_at')->get();

        return view('gamification.dashboard', compact(
            'user', 'house', 'housePoints', 'houseLevel', 'houseRank', 'achievements'
        ));
    }

    /** Lista todas as conquistas disponíveis. */
    public function achievements(): View|RedirectResponse
    {
        $user  = Auth::user();

        if (! $user->activeHouse()) {
            return redirect()->route('houses')
                ->with('error', 'Você precisa ser membro ativo de uma casa para acessar as conquistas.');
        }

        $all    = Achievement::orderBy('points_required')->get();
        $earned = $user->achievements->pluck('id');

        return view('gamification.achievements', compact('all', 'earned'));
    }

    /** Ranking dos membros da casa do usuário, ordenado por house_points. */
    public function ranking(): View|RedirectResponse
    {
        $user  = Auth::user();
        $house = $user->activeHouse();

        if (! $house) {
            return redirect()->route('houses')
                ->with('error', 'Você precisa ser membro ativo de uma casa para ver o ranking.');
        }

        $ranking = $house->members()
            ->wherePivot('status', 'active')
            ->orderByPivot('house_points', 'desc')
            ->orderByPivot('house_level', 'desc')
            ->get();

        return view('gamification.ranking', compact('ranking', 'house'));
    }
}
