<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** Exibe o perfil do usuário autenticado. */
    public function show(): View
    {
        $user = Auth::user()->load('achievements');
        $user->houses = $user->houses()->withPivot(['role', 'role_membro', 'entities', 'status'])->get();
        return view('profile.show', compact('user'));
    }

    /** Exibe o formulário de edição de perfil. */
    public function edit(): View
    {
        $user  = Auth::user();
        $house = null;

        if ($user->hasRole('dirigente,admin')) {
            $house = $user->ownedHouses()->first()
                ?? $user->houses()->wherePivot('role', 'dirigente')->wherePivot('status', 'active')->first();
        }

        return view('profile.edit', compact('user', 'house'));
    }

    /** Atualiza os dados do perfil. */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar'     => ['nullable', 'image', 'max:2048'],
        ]);

        $user = Auth::user();
        $data = $request->only('name', 'phone', 'birth_date');

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('profile')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    public function changePasswordForm(): View
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile')->with('success', 'Senha alterada com sucesso!');
    }
}
