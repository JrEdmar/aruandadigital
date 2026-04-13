<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CardController extends Controller
{
    /** Exibe a carteirinha digital do membro. */
    public function index(): View
    {
        $user  = Auth::user()->load('houses');
        $house = $user->houses()->wherePivot('status', 'active')
                    ->withPivot(['role', 'role_membro', 'entities', 'joined_at'])
                    ->first();

        return view('card.index', compact('user', 'house'));
    }
}
