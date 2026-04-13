<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCasaRequest;
use App\Http\Requests\Auth\RegisterDevotoRequest;
use App\Http\Requests\Auth\RegisterLojaRequest;
use App\Models\House;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /** Exibe o formulário de cadastro com as 3 abas. */
    public function showForm(): View
    {
        return view('auth.register');
    }

    // -------------------------------------------------------------------------
    // Cadastro de Visitante
    // -------------------------------------------------------------------------

    public function storeDevoto(RegisterDevotoRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => $request->password,
            'phone'            => $request->phone,
            'cpf'              => $request->cpf,
            'birth_date'       => $request->birth_date,
            'role'             => 'visitante',
            'lgpd_accepted_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Bem-vindo(a) ao Aruanda Digital!');
    }

    // -------------------------------------------------------------------------
    // Cadastro de Casa / Templo
    // -------------------------------------------------------------------------

    public function storeCasa(RegisterCasaRequest $request): RedirectResponse
    {
        // Cria o dirigente
        $user = User::create([
            'name'             => $request->dirigente_name,
            'email'            => $request->email,
            'password'         => $request->password,
            'phone'            => $request->phone,
            'cpf'              => $request->cpf,
            'birth_date'       => $request->birth_date,
            'role'             => 'dirigente',
            'lgpd_accepted_at' => now(),
        ]);

        // Cria a Casa com status 'pending' aguardando aprovação
        $house = House::create([
            'owner_id' => $user->id,
            'name'     => $request->house_name,
            'cnpj'     => $request->cnpj,
            'type'     => $request->type,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'street'   => $request->street,
            'city'     => $request->city,
            'state'    => $request->state,
            'status'   => 'pending',
        ]);

        // Associa o dirigente à casa
        $house->members()->attach($user->id, [
            'role'      => 'dirigente',
            'status'    => 'active',
            'joined_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/')
            ->with('success', 'Cadastro realizado! Sua casa está aguardando aprovação pela equipe Aruanda Digital.');
    }

    // -------------------------------------------------------------------------
    // Cadastro de Loja
    // -------------------------------------------------------------------------

    public function storeLoja(RegisterLojaRequest $request): RedirectResponse
    {
        $role = $request->store_type === 'atacado' ? 'loja_master' : 'loja';

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => $request->password,
            'phone'            => $request->phone,
            'cpf'              => $request->cpf,
            'birth_date'       => $request->birth_date,
            'role'             => $role,
            'lgpd_accepted_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/seller')->with('success', 'Bem-vindo(a) à área de vendas Aruanda Digital!');
    }
}
