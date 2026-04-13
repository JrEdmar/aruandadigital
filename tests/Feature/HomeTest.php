<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\House;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_acessivel_para_usuario_autenticado(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/')->assertStatus(200);
    }

    public function test_home_redireciona_guest_para_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_home_exibe_eventos_publicos_futuros(): void
    {
        $owner = User::factory()->dirigente()->create();
        $house = House::create(['owner_id' => $owner->id, 'name' => 'Tenda Oxalá', 'status' => 'active']);
        Event::create([
            'house_id'   => $house->id,
            'created_by' => $owner->id,
            'name'       => 'Gira da Semana',
            'starts_at'  => now()->addDays(3),
            'status'     => 'open',
            'visibility' => 'public',
        ]);

        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/')->assertSee('Gira da Semana');
    }

    public function test_home_nao_exibe_eventos_passados(): void
    {
        $owner = User::factory()->dirigente()->create();
        $house = House::create(['owner_id' => $owner->id, 'name' => 'Tenda Oxalá', 'status' => 'active']);
        Event::create([
            'house_id'   => $house->id,
            'created_by' => $owner->id,
            'name'       => 'Gira Passada',
            'starts_at'  => now()->subDays(3),
            'status'     => 'finished',
            'visibility' => 'public',
        ]);

        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/')->assertDontSee('Gira Passada');
    }

    public function test_mapa_acessivel(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/map')->assertStatus(200);
    }

    public function test_perfil_acessivel(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/profile')->assertStatus(200);
    }

    public function test_notificacoes_acessivel(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/notifications')->assertStatus(200);
    }

    public function test_carteirinha_acessivel(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/card')->assertStatus(200);
    }
}
