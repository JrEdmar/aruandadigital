<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $houses = House::where('status', 'active')->orderBy('name')->paginate(20);
        return view('houses.index', compact('houses'));
    }

    public function show(Request $request, string $id): \Illuminate\View\View
    {
        $house = House::where('status', 'active')
            ->with([
                'owner',
                'activeMembers',
                'upcomingEvents',
                'pastEvents' => fn ($q) => $q->limit(10),
            ])
            ->findOrFail($id);

        $tab = $request->get('tab', 'sobre');

        return view('houses.show', compact('house', 'tab'));
    }

    /**
     * Solicita entrada do usuário autenticado na casa.
     * Status inicial: pending — aguarda aprovação do dirigente.
     */
    public function join(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $house = House::where('status', 'active')->findOrFail($id);
        $user  = Auth::user();

        $existing = $house->members()->where('user_id', $user->id)->first();

        if ($existing) {
            $status = $existing->pivot->status;

            // Permite reenviar se foi rejeitado ou cancelado anteriormente
            if (in_array($status, ['rejected', 'cancelled'])) {
                $house->members()->updateExistingPivot($user->id, [
                    'status'       => 'pending',
                    'message'      => $request->input('message'),
                    'role_membro'  => $request->input('role_membro'),
                    'cancelled_at' => null,
                    'joined_at'    => now(),
                ]);

                if ($request->ajax()) {
                    return response()->json(['message' => 'Solicitação reenviada! Aguarde a aprovação.']);
                }
                return back()->with('success', 'Solicitação reenviada! Aguarde a aprovação do dirigente.');
            }

            $msg = match($status) {
                'pending' => 'Você já possui uma solicitação pendente nesta casa.',
                'active'  => 'Você já é membro desta casa.',
                default   => 'Você já possui um vínculo com esta casa.',
            };

            if ($request->ajax()) {
                return response()->json(['message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $house->members()->attach($user->id, [
            'role'        => 'membro',
            'status'      => 'pending',
            'message'     => $request->input('message'),
            'role_membro' => $request->input('role_membro'),
            'joined_at'   => now(),
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Solicitação enviada! Aguarde a aprovação do dirigente.']);
        }

        return back()->with('success', 'Solicitação enviada! Aguarde a aprovação do dirigente.');
    }

    public function edit(string $id): \Illuminate\View\View
    {
        $user  = Auth::user();
        $house = House::findOrFail($id);

        if (! $this->canManageHouse($user, $house)) {
            abort(403);
        }

        return view('houses.edit', compact('house'));
    }

    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $user  = Auth::user();
        $house = House::findOrFail($id);

        if (! $this->canManageHouse($user, $house)) {
            abort(403);
        }

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'type'            => ['required', 'in:umbanda,candomble,misto,outro'],
            'description'     => ['nullable', 'string', 'max:3000'],
            'spiritual_line'  => ['nullable', 'string', 'max:1000'],
            'history'         => ['nullable', 'string', 'max:3000'],
            'differentials'   => ['nullable', 'string', 'max:500'],
            'email'           => ['nullable', 'email', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'whatsapp'        => ['nullable', 'string', 'max:20'],
            'website'         => ['nullable', 'url', 'max:255'],
            'facebook'        => ['nullable', 'string', 'max:255'],
            'instagram'       => ['nullable', 'string', 'max:255'],
            'youtube'         => ['nullable', 'string', 'max:255'],
            'zip_code'        => ['nullable', 'string', 'max:10'],
            'street'          => ['nullable', 'string', 'max:255'],
            'number'          => ['nullable', 'string', 'max:20'],
            'complement'      => ['nullable', 'string', 'max:100'],
            'neighborhood'    => ['nullable', 'string', 'max:100'],
            'city'            => ['nullable', 'string', 'max:100'],
            'state'           => ['nullable', 'string', 'max:2'],
            'latitude'        => ['nullable', 'numeric'],
            'longitude'       => ['nullable', 'numeric'],
            'capacity'        => ['nullable', 'integer', 'min:1'],
            'schedule'        => ['nullable', 'string', 'max:500'],
            'foundation_date' => ['nullable', 'date'],
            'logo_image'      => ['nullable', 'image', 'max:2048'],
            'cover_image'     => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $request->file('logo_image')->store('houses/logos', 'public');
        } else {
            unset($data['logo_image']);
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('houses/covers', 'public');
        } else {
            unset($data['cover_image']);
        }

        $house->update($data);

        $back = $request->input('redirect_back');
        if ($back === 'profile') {
            return redirect()->route('profile.edit')->with('house_success', 'Página da casa atualizada com sucesso!');
        }

        return redirect()->route('houses.show', $house->id)->with('success', 'Casa atualizada com sucesso!');
    }

    /** Verifica se o usuário pode gerir a página pública da casa. */
    private function canManageHouse($user, House $house): bool
    {
        if ($user->isAdmin()) return true;
        if ($house->owner_id === $user->id) return true;

        // Dirigente ativo da casa também pode editar
        return $house->members()
            ->where('user_id', $user->id)
            ->wherePivot('status', 'active')
            ->wherePivotIn('role', ['dirigente'])
            ->exists();
    }

    /** Devoto cancela a própria solicitação pendente. */
    public function cancelRequest(string $id): RedirectResponse|JsonResponse
    {
        $house = House::findOrFail($id);
        $user  = Auth::user();

        $existing = $house->members()->where('user_id', $user->id)
            ->wherePivot('status', 'pending')
            ->first();

        if (! $existing) {
            return request()->ajax()
                ? response()->json(['message' => 'Solicitação não encontrada ou já processada.'], 422)
                : back()->with('error', 'Solicitação não encontrada ou já processada.');
        }

        $house->members()->updateExistingPivot($user->id, [
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return request()->ajax()
            ? response()->json(['message' => 'Solicitação cancelada.'])
            : back()->with('success', 'Solicitação cancelada.');
    }
}
