<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EventController extends Controller
{
    /** Lista todos os eventos abertos/futuros. */
    public function index(): View
    {
        $events = Event::with('house')
            ->whereIn('status', ['open', 'full'])
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    /** Exibe os detalhes de um evento. */
    public function show(string $id): View
    {
        $event        = Event::with(['house', 'attendees'])->findOrFail($id);
        $userAttendee = $event->attendees->where('id', Auth::id())->first();
        $userIntent   = (Schema::hasColumn('event_user', 'intent'))
            ? $userAttendee?->pivot->intent
            : null; // going, maybe ou null
        $isToday      = $event->starts_at->isToday();
        $isCheckedIn  = $userAttendee?->pivot->status === 'checked_in';
        return view('events.show', compact('event', 'userIntent', 'isToday', 'isCheckedIn'));
    }

    /** Lista os eventos nos quais o usuário está inscrito. */
    public function myList(): View
    {
        $user = Auth::user();

        $upcoming = $user->events()
            ->where('starts_at', '>=', now())
            ->whereIn('events.status', ['open', 'full'])
            ->orderBy('starts_at')
            ->get();

        $past = $user->events()
            ->where('starts_at', '<', now())
            ->orderByDesc('starts_at')
            ->limit(20)
            ->get();

        return view('events.my-list', compact('upcoming', 'past'));
    }

    /** Inscreve o usuário autenticado no evento. */
    public function subscribe(string $id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $user  = Auth::user();

        if (!in_array($event->status, ['open'])) {
            return response()->json(['message' => 'Inscrições não disponíveis para este evento.'], 422);
        }

        if ($event->attendees()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Você já está inscrito neste evento.'], 422);
        }

        if ($event->capacity && $event->attendees()->count() >= $event->capacity) {
            return response()->json(['message' => 'Evento sem vagas disponíveis.'], 422);
        }

        $event->attendees()->attach($user->id, [
            'status'        => 'registered',
            'registered_at' => now(),
        ]);

        return response()->json(['message' => 'Inscrição realizada com sucesso!', 'subscribed' => true]);
    }

    /** Remove a inscrição do usuário autenticado no evento. */
    public function unsubscribe(string $id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $user  = Auth::user();

        $event->attendees()->detach($user->id);

        return response()->json(['message' => 'Inscrição cancelada.', 'subscribed' => false]);
    }

    /** Tela de check-in para dirigentes/assistentes. */
    public function checkin(Request $request): View
    {
        $eventId = $request->get('event_id');
        $event   = $eventId ? Event::findOrFail($eventId) : Event::where('status', 'open')
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->first();

        return view('events.checkin', compact('event'));
    }

    /** Registra intenção de presença (✅ Vou / 🤔 Talvez / ❌ Não vou). */
    public function setIntent(string $id, Request $request): JsonResponse
    {
        $event  = Event::findOrFail($id);
        $user   = Auth::user();
        $intent = $request->input('intent'); // going, maybe, not_going

        if ($intent === 'not_going') {
            $event->attendees()->detach($user->id);
            return response()->json(['message' => 'Inscrição cancelada.', 'intent' => null]);
        }

        if (! in_array($intent, ['going', 'maybe'])) {
            return response()->json(['message' => 'Intenção inválida.'], 422);
        }

        if (! in_array($event->status, ['open', 'full'])) {
            return response()->json(['message' => 'Evento não disponível para inscrição.'], 422);
        }

        if ($event->attendees()->where('user_id', $user->id)->exists()) {
            $event->attendees()->updateExistingPivot($user->id, ['intent' => $intent]);
        } else {
            if ($event->capacity && $event->attendees()->count() >= $event->capacity) {
                return response()->json(['message' => 'Evento sem vagas disponíveis.'], 422);
            }
            $event->attendees()->attach($user->id, [
                'status'        => 'registered',
                'intent'        => $intent,
                'registered_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Confirmação salva!', 'intent' => $intent]);
    }

    /** Check-in automático do próprio membro em um evento de hoje. */
    public function selfCheckin(string $id): \Illuminate\Http\RedirectResponse
    {
        $event = Event::findOrFail($id);
        $user  = Auth::user();

        // Verifica se o evento é de hoje
        if (! $event->starts_at->isToday()) {
            return redirect()->route('my-house')
                ->with('error', 'O check-in só pode ser feito no dia do evento.');
        }

        // Registra ou atualiza presença
        if ($event->attendees()->where('user_id', $user->id)->exists()) {
            $event->attendees()->updateExistingPivot($user->id, [
                'status'        => 'checked_in',
                'checked_in_at' => now(),
            ]);
        } else {
            $event->attendees()->attach($user->id, [
                'status'        => 'checked_in',
                'registered_at' => now(),
                'checked_in_at' => now(),
            ]);
        }

        return redirect()->route('my-house')
            ->with('success', 'Check-in realizado com sucesso! Axé!');
    }
}
