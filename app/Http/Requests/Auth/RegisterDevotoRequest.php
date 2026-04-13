<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDevotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'phone'         => ['required', 'string', 'max:20'],
            'cpf'           => ['required', 'string', 'max:14'],
            'birth_date'    => ['required', 'date', 'before:-18 years'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'lgpd_accepted' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'O nome é obrigatório.',
            'email.required'         => 'O e-mail é obrigatório.',
            'email.unique'           => 'Este e-mail já está em uso.',
            'phone.required'         => 'O telefone é obrigatório.',
            'password.required'      => 'A senha é obrigatória.',
            'password.min'           => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed'     => 'As senhas não conferem.',
            'cpf.required'           => 'O CPF é obrigatório.',
            'birth_date.required'    => 'A data de nascimento é obrigatória.',
            'birth_date.before'      => 'É necessário ter pelo menos 18 anos para se cadastrar.',
            'lgpd_accepted.required' => 'Você deve aceitar os Termos e a Política de Privacidade.',
            'lgpd_accepted.accepted' => 'Você deve aceitar os Termos e a Política de Privacidade.',
        ];
    }
}
