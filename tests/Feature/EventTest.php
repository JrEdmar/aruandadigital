<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\House;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    private function makeHouseWithDirigente(): array
    {
        $dirigente = User::factory()->dirigente()->create();
        $house = House::create([
            'owner_id' => $dirigente->id,
            'name'     => 'Tenda Oxalá',
            'status'   => 'active',
        ]);
        $house->members()->attach($dirigente->id, [
            'role'      => 'dirigente',
            'status'    => 'active',
            'joined_at' => now(),
        ]);
        return [$dirigente, $house];
    }

    private function makeEvent(House $house, User $creator, array $attrs = []): Event
    {
        return Event::create(array_merge([
            'house_id'   => $house->id,
            'created_by' => $creator->id,
            'name'       => 'Gira de Oxalá',
            'starts_at'  => now()->addDays(3),
            'status'     => 'open',
            'visibility' => 'public',
        ], $attrs));
    }

    // -------------------------------------------------------------------------
    // Listagem de eventos
    // -------------------------------------------------------------------------

    public function test_lista_de_eventos_acessivel_para_autenticados(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/events')->assertStatus(200);
    }

    public function test_lista_de_eventos_redireciona_guest(): void
    {
        $this->get('/events')->assertRedirect('/login');
    }

    public function test_eventos_open_aparecem_na_listagem(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open', 'starts_at' => now()->addDay()]);

        $user = User::factory()->visitante()->create();
        $response = $this->actingAs($user)->get('/events');

        $response->assertStatus(200)->assertSee($evento->name);
    }

    public function test_eventos_draft_nao_aparecem_na_listagem(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'draft']);

        $user = User::factory()->visitante()->create();
        $response = $this->actingAs($user)->get('/events');

        $response->assertStatus(200)->assertDontSee($evento->name);
    }

    // -------------------------------------------------------------------------
    // Página de detalhe do evento
    // -------------------------------------------------------------------------

    public function test_detalhe_de_evento_acessivel(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente);

        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get("/events/{$evento->id}")->assertStatus(200);
    }

    public function test_detalhe_de_evento_inexistente_retorna_404(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/events/99999')->assertStatus(404);
    }

    // -------------------------------------------------------------------------
    // Inscrição no evento
    // -------------------------------------------------------------------------

    public function test_usuario_pode_se_inscrever_em_evento_aberto(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open']);

        $user = User::factory()->visitante()->create();
        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/subscribe");

        $response->assertStatus(200)->assertJson(['subscribed' => true]);
        $this->assertDatabaseHas('event_user', [
            'event_id' => $evento->id,
            'user_id'  => $user->id,
            'status'   => 'registered',
        ]);
    }

    public function test_usuario_nao_pode_se_inscrever_duas_vezes(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open']);

        $user = User::factory()->visitante()->create();
        $evento->attendees()->attach($user->id, ['status' => 'registered', 'registered_at' => now()]);

        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/subscribe");

        $response->assertStatus(422);
    }

    public function test_usuario_nao_pode_se_inscrever_em_evento_cancelado(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'cancelled']);

        $user = User::factory()->visitante()->create();
        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/subscribe");

        $response->assertStatus(422);
    }

    public function test_inscricao_respeita_capacidade_maxima(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open', 'capacity' => 1]);

        // Ocupa a única vaga
        $outro = User::factory()->create();
        $evento->attendees()->attach($outro->id, ['status' => 'registered', 'registered_at' => now()]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/subscribe");

        $response->assertStatus(422);
    }

    // -------------------------------------------------------------------------
    // Cancelamento de inscrição
    // -------------------------------------------------------------------------

    public function test_usuario_pode_cancelar_inscricao(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente);

        $user = User::factory()->create();
        $evento->attendees()->attach($user->id, ['status' => 'registered', 'registered_at' => now()]);

        $response = $this->actingAs($user)
            ->deleteJson("/events/{$evento->id}/subscribe");

        $response->assertStatus(200)->assertJson(['subscribed' => false]);
        $this->assertDatabaseMissing('event_user', [
            'event_id' => $evento->id,
            'user_id'  => $user->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Intent (✅ Vou / 🤔 Talvez / ❌ Não vou)
    // -------------------------------------------------------------------------

    public function test_usuario_pode_marcar_intencao_going(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open']);

        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/intent", ['intent' => 'going']);

        $response->assertStatus(200)->assertJson(['intent' => 'going']);
        $this->assertDatabaseHas('event_user', [
            'event_id' => $evento->id,
            'user_id'  => $user->id,
        ]);
    }

    public function test_usuario_pode_marcar_intencao_maybe(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open']);

        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/intent", ['intent' => 'maybe']);

        $response->assertStatus(200)->assertJson(['intent' => 'maybe']);
    }

    public function test_not_going_remove_inscricao(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open']);

        $user = User::factory()->create();
        $evento->attendees()->attach($user->id, ['status' => 'registered', 'registered_at' => now()]);

        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/intent", ['intent' => 'not_going']);

        $response->assertStatus(200)->assertJson(['intent' => null]);
        $this->assertDatabaseMissing('event_user', [
            'event_id' => $evento->id,
            'user_id'  => $user->id,
        ]);
    }

    public function test_intent_invalido_retorna_422(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, ['status' => 'open']);

        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->postJson("/events/{$evento->id}/intent", ['intent' => 'talvez_nao_sei']);

        $response->assertStatus(422);
    }

    // -------------------------------------------------------------------------
    // Check-in automático (Cheguei!)
    // -------------------------------------------------------------------------

    public function test_usuario_pode_fazer_checkin_no_dia_do_evento(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, [
            'status'    => 'open',
            'starts_at' => now(), // hoje
        ]);

        $user = User::factory()->create();
        $evento->attendees()->attach($user->id, ['status' => 'registered', 'registered_at' => now()]);

        $response = $this->actingAs($user)
            ->post("/events/{$evento->id}/checkin");

        $response->assertRedirect('/my-house');
        $this->assertDatabaseHas('event_user', [
            'event_id' => $evento->id,
            'user_id'  => $user->id,
            'status'   => 'checked_in',
        ]);
    }

    public function test_checkin_nao_permitido_em_dia_diferente(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $evento = $this->makeEvent($house, $dirigente, [
            'status'    => 'open',
            'starts_at' => now()->addDays(5),
        ]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post("/events/{$evento->id}/checkin");

        $response->assertRedirect('/my-house');
        $response->assertSessionHas('error');
    }

    // -------------------------------------------------------------------------
    // Minha lista de eventos
    // -------------------------------------------------------------------------

    public function test_minha_lista_de_eventos_acessivel(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/my-list')->assertStatus(200);
    }
}
