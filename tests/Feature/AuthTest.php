<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Exibição dos formulários (guest)
    // -------------------------------------------------------------------------

    public function test_pagina_de_login_acessivel_para_guest(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_pagina_de_cadastro_acessivel_para_guest(): void
    {
        $this->get('/register')->assertStatus(200);
    }

    public function test_usuario_autenticado_e_redirecionado_do_login(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/login')->assertRedirect();
    }

    // -------------------------------------------------------------------------
    // Cadastro — Visitante
    // -------------------------------------------------------------------------

    public function test_cadastro_visitante_com_dados_validos(): void
    {
        $response = $this->post('/register/visitante', [
            'name'                  => 'Joana Silva',
            'email'                 => 'joana@teste.com',
            'phone'                 => '(11) 99999-9999',
            'cpf'                   => '000.000.000-00',
            'birth_date'            => '1990-05-15',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'joana@teste.com', 'role' => 'visitante']);
        $this->assertAuthenticated();
    }

    public function test_cadastro_visitante_falha_com_email_duplicado(): void
    {
        User::factory()->create(['email' => 'joana@teste.com']);

        $response = $this->post('/register/visitante', [
            'name'                  => 'Joana Silva',
            'email'                 => 'joana@teste.com',
            'phone'                 => '(11) 99999-9999',
            'cpf'                   => '000.000.000-00',
            'birth_date'            => '1990-05-15',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_cadastro_visitante_falha_sem_lgpd(): void
    {
        $response = $this->post('/register/visitante', [
            'name'                  => 'Joana Silva',
            'email'                 => 'joana@teste.com',
            'phone'                 => '(11) 99999-9999',
            'cpf'                   => '000.000.000-00',
            'birth_date'            => '1990-05-15',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            // lgpd_accepted ausente
        ]);

        $response->assertSessionHasErrors('lgpd_accepted');
    }

    public function test_cadastro_visitante_falha_com_senhas_diferentes(): void
    {
        $response = $this->post('/register/visitante', [
            'name'                  => 'Joana Silva',
            'email'                 => 'joana@teste.com',
            'phone'                 => '(11) 99999-9999',
            'cpf'                   => '000.000.000-00',
            'birth_date'            => '1990-05-15',
            'password'              => 'senha12345',
            'password_confirmation' => 'diferente99',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_cadastro_visitante_falha_com_menor_de_18_anos(): void
    {
        $response = $this->post('/register/visitante', [
            'name'                  => 'Criança Silva',
            'email'                 => 'crianca@teste.com',
            'phone'                 => '(11) 99999-9999',
            'cpf'                   => '000.000.000-00',
            'birth_date'            => now()->subYears(17)->format('Y-m-d'),
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertSessionHasErrors('birth_date');
    }

    // -------------------------------------------------------------------------
    // Cadastro — Casa
    // -------------------------------------------------------------------------

    public function test_cadastro_casa_com_dados_validos(): void
    {
        $response = $this->post('/register/casa', [
            'house_name'            => 'Tenda Espírita Oxalá',
            'cnpj'                  => '12.345.678/0001-99',
            'type'                  => 'umbanda',
            'street'                => 'Rua das Flores, 123',
            'city'                  => 'São Paulo',
            'state'                 => 'SP',
            'email'                 => 'tenda@oxala.com',
            'phone'                 => '(11) 99999-0000',
            'dirigente_name'        => 'Pai João de Oxalá',
            'cpf'                   => '111.111.111-11',
            'birth_date'            => '1975-03-20',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'tenda@oxala.com', 'role' => 'dirigente']);
        $this->assertDatabaseHas('houses', ['name' => 'Tenda Espírita Oxalá', 'status' => 'pending']);
        $this->assertAuthenticated();
    }

    public function test_cadastro_casa_tipo_outro_valido(): void
    {
        $response = $this->post('/register/casa', [
            'house_name'            => 'Centro Espírita Luz',
            'cnpj'                  => '99.888.777/0001-66',
            'type'                  => 'outro',
            'street'                => 'Av. Brasil, 500',
            'city'                  => 'Rio de Janeiro',
            'state'                 => 'RJ',
            'email'                 => 'centro@luz.com',
            'phone'                 => '(21) 98888-0000',
            'dirigente_name'        => 'Mãe Maria da Luz',
            'cpf'                   => '222.222.222-22',
            'birth_date'            => '1968-07-10',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('houses', ['name' => 'Centro Espírita Luz', 'status' => 'pending']);
    }

    public function test_cadastro_casa_falha_com_tipo_invalido(): void
    {
        $response = $this->post('/register/casa', [
            'house_name'            => 'Casa Inválida',
            'cnpj'                  => '12.345.678/0001-99',
            'type'                  => 'invalido',
            'street'                => 'Rua X',
            'city'                  => 'São Paulo',
            'state'                 => 'SP',
            'email'                 => 'invalida@teste.com',
            'phone'                 => '(11) 99999-0000',
            'dirigente_name'        => 'Dirigente Teste',
            'cpf'                   => '000.000.000-00',
            'birth_date'            => '1980-01-01',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertSessionHasErrors('type');
    }

    // -------------------------------------------------------------------------
    // Cadastro — Loja
    // -------------------------------------------------------------------------

    public function test_cadastro_loja_varejo_com_dados_validos(): void
    {
        $response = $this->post('/register/loja', [
            'store_name'            => 'Loja do Axé',
            'cnpj'                  => '55.666.777/0001-88',
            'store_type'            => 'varejo',
            'email'                 => 'loja@axe.com',
            'phone'                 => '(11) 97777-0000',
            'name'                  => 'Carlos Vendedor',
            'cpf'                   => '333.333.333-33',
            'birth_date'            => '1985-11-25',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertRedirect('/seller');
        $this->assertDatabaseHas('users', ['email' => 'loja@axe.com', 'role' => 'loja']);
    }

    public function test_cadastro_loja_atacado_cria_loja_master(): void
    {
        $response = $this->post('/register/loja', [
            'store_name'            => 'Distribuidora Axé',
            'cnpj'                  => '11.222.333/0001-44',
            'store_type'            => 'atacado',
            'email'                 => 'dist@axe.com',
            'phone'                 => '(11) 96666-0000',
            'name'                  => 'Maria Distribuidora',
            'cpf'                   => '444.444.444-44',
            'birth_date'            => '1978-02-14',
            'password'              => 'senha12345',
            'password_confirmation' => 'senha12345',
            'lgpd_accepted'         => '1',
        ]);

        $response->assertRedirect('/seller');
        $this->assertDatabaseHas('users', ['email' => 'dist@axe.com', 'role' => 'loja_master']);
    }

    // -------------------------------------------------------------------------
    // Login
    // -------------------------------------------------------------------------

    public function test_login_com_credenciais_validas_redireciona_para_home(): void
    {
        $user = User::factory()->membro()->create(['email' => 'membro@teste.com']);

        $response = $this->post('/login', [
            'email'    => 'membro@teste.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_login_dirigente_redireciona_para_my_house(): void
    {
        User::factory()->dirigente()->create(['email' => 'dirigente@teste.com']);

        $response = $this->post('/login', [
            'email'    => 'dirigente@teste.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/my-house');
    }

    public function test_login_admin_redireciona_para_admin(): void
    {
        User::factory()->admin()->create(['email' => 'admin@teste.com']);

        $response = $this->post('/login', [
            'email'    => 'admin@teste.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
    }

    public function test_login_loja_redireciona_para_seller(): void
    {
        User::factory()->loja()->create(['email' => 'loja@teste.com']);

        $response = $this->post('/login', [
            'email'    => 'loja@teste.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/seller');
    }

    public function test_login_com_credenciais_invalidas_retorna_erro(): void
    {
        User::factory()->create(['email' => 'user@teste.com']);

        $response = $this->post('/login', [
            'email'    => 'user@teste.com',
            'password' => 'senha_errada',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    // -------------------------------------------------------------------------
    // Logout
    // -------------------------------------------------------------------------

    public function test_logout_encerra_sessao_e_redireciona_para_login(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // -------------------------------------------------------------------------
    // Proteção de rotas
    // -------------------------------------------------------------------------

    public function test_guest_e_redirecionado_para_login_ao_acessar_home(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_guest_e_redirecionado_para_login_ao_acessar_my_house(): void
    {
        $this->get('/my-house')->assertRedirect('/login');
    }
}
