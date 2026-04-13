<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /** Lista as notificações do usuário autenticado. */
    public function index(): View
    {
        $notifications = Auth::user()
            ->userNotifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /** Marca uma notificação específica como lida. */
    public function markAsRead(string $id): JsonResponse
    {
        Auth::user()->userNotifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    /** Marca todas as notificações do usuário como lidas. */
    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->userNotifications()->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }
}
