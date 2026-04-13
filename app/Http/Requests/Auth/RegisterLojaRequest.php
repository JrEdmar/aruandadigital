<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterLojaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store_name'    => ['required', 'string', 'max:255'],
            'cnpj'          => ['required', 'string', 'max:18'],
            'store_type'    => ['required', 'in:varejo,atacado'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'phone'         => ['required', 'string', 'max:20'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'name'          => ['required', 'string', 'max:255'], // responsável
            'cpf'           => ['required', 'string', 'max:14'],
            'birth_date'    => ['required', 'date', 'before:-18 years'],
            'lgpd_accepted' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required'   => 'O nome da loja é obrigatório.',
            'cnpj.required'         => 'O CNPJ é obrigatório.',
            'store_type.required'   => 'Selecione o tipo de loja.',
            'store_type.in'         => 'Tipo inválido. Escolha: Varejo ou Atacado.',
            'email.required'        => 'O e-mail é obrigatório.',
            'email.unique'          => 'Este e-mail já está em uso.',
            'phone.required'        => 'O telefone é obrigatório.',
            'password.required'     => 'A senha é obrigatória.',
            'password.min'          => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed'    => 'As senhas não conferem.',
            'name.required'         => 'O nome do responsável é obrigatório.',
            'cpf.required'          => 'O CPF do responsável é obrigatório.',
            'birth_date.required'   => 'A data de nascimento é obrigatória.',
            'birth_date.before'     => 'O responsável deve ter pelo menos 18 anos.',
            'lgpd_accepted.required'=> 'Você deve aceitar os Termos e a Política de Privacidade.',
            'lgpd_accepted.accepted'=> 'Você deve aceitar os Termos e a Política de Privacidade.',
        ];
    }
}
