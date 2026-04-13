<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCasaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'house_name'     => ['required', 'string', 'max:255'],
            'cnpj'           => ['required', 'string', 'max:18'],
            'type'           => ['required', 'in:umbanda,candomble,misto,outro'],
            'street'         => ['required', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'state'          => ['required', 'string', 'max:2'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'phone'          => ['required', 'string', 'max:20'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'dirigente_name' => ['required', 'string', 'max:255'],
            'cpf'            => ['required', 'string', 'max:14'],
            'birth_date'     => ['required', 'date', 'before:-18 years'],
            'lgpd_accepted'  => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'house_name.required'     => 'O nome da casa é obrigatório.',
            'cnpj.required'           => 'O CNPJ é obrigatório.',
            'type.required'           => 'Selecione o tipo de casa.',
            'type.in'                 => 'Tipo inválido. Escolha: Umbanda, Candomblé, Misto ou Outro.',
            'street.required'         => 'O endereço é obrigatório.',
            'city.required'           => 'A cidade é obrigatória.',
            'state.required'          => 'O estado é obrigatório.',
            'email.required'          => 'O e-mail é obrigatório.',
            'email.unique'            => 'Este e-mail já está em uso.',
            'phone.required'          => 'O telefone é obrigatório.',
            'password.required'       => 'A senha é obrigatória.',
            'password.min'            => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed'      => 'As senhas não conferem.',
            'dirigente_name.required' => 'O nome do dirigente é obrigatório.',
            'cpf.required'            => 'O CPF do dirigente é obrigatório.',
            'birth_date.required'     => 'A data de nascimento do dirigente é obrigatória.',
            'birth_date.before'       => 'O dirigente deve ter pelo menos 18 anos.',
            'lgpd_accepted.required'  => 'Você deve aceitar os Termos e a Política de Privacidade.',
            'lgpd_accepted.accepted'  => 'Você deve aceitar os Termos e a Política de Privacidade.',
        ];
    }
}
