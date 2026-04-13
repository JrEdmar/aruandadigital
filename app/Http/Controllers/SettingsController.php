<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email_notifications' => ['nullable', 'boolean'],
        ]);

        // Aqui serão salvas preferências futuras (notificações, privacidade etc.)

        return back()->with('success', 'Configurações salvas!');
    }
}
