<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HouseTest extends TestCase
{
    use RefreshDatabase;

    private function makeActiveHouse(?User $owner = null): House
    {
        $owner ??= User::factory()->dirigente()->create();
        return House::create([
            'owner_id' => $owner->id,
            'name'     => 'Tenda São Jorge',
            'status'   => 'active',
        ]);
    }

    // -------------------------------------------------------------------------
    // Listagem de casas
    // -------------------------------------------------------------------------

    public function test_lista_de_casas_acessivel(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/houses')->assertStatus(200);
    }

    public function test_casas_ativas_aparecem_na_listagem(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->visitante()->create();

        $response = $this->actingAs($user)->get('/houses');
        $response->assertStatus(200)->assertSee($house->name);
    }

    public function test_casas_pendentes_nao_aparecem_na_listagem(): void
    {
        $owner = User::factory()->dirigente()->create();
        House::create(['owner_id' => $owner->id, 'name' => 'Casa Pendente', 'status' => 'pending']);

        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/houses')->assertDontSee('Casa Pendente');
    }

    // -------------------------------------------------------------------------
    // Detalhe da casa
    // -------------------------------------------------------------------------

    public function test_detalhe_de_casa_ativa_acessivel(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->visitante()->create();

        $this->actingAs($user)->get("/houses/{$house->id}")->assertStatus(200);
    }

    public function test_detalhe_de_casa_inexistente_retorna_404(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/houses/99999')->assertStatus(404);
    }

    // -------------------------------------------------------------------------
    // Solicitação de filiação (join)
    // -------------------------------------------------------------------------

    public function test_usuario_pode_solicitar_filiacao(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->visitante()->create();

        $response = $this->actingAs($user)->post("/houses/{$house->id}/join", [
            'message'     => 'Tenho interesse em me filiar.',
            'role_membro' => 'médium',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('house_user', [
            'house_id' => $house->id,
            'user_id'  => $user->id,
            'status'   => 'pending',
            'role'     => 'membro',
        ]);
    }

    public function test_usuario_nao_pode_solicitar_filiacao_duas_vezes(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->visitante()->create();

        // Primeira solicitação
        $house->members()->attach($user->id, ['role' => 'membro', 'status' => 'pending']);

        $response = $this->actingAs($user)->post("/houses/{$house->id}/join");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_usuario_pode_reenviar_apos_rejeicao(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->visitante()->create();

        $house->members()->attach($user->id, ['role' => 'membro', 'status' => 'rejected']);

        $response = $this->actingAs($user)->post("/houses/{$house->id}/join", [
            'message' => 'Gostaria de tentar novamente.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('house_user', [
            'house_id' => $house->id,
            'user_id'  => $user->id,
            'status'   => 'pending',
        ]);
    }

    public function test_membro_ativo_nao_pode_reenviar_solicitacao(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->membro()->create();
        $house->members()->attach($user->id, ['role' => 'membro', 'status' => 'active', 'joined_at' => now()]);

        $response = $this->actingAs($user)->post("/houses/{$house->id}/join");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // -------------------------------------------------------------------------
    // Cancelamento de solicitação
    // -------------------------------------------------------------------------

    public function test_usuario_pode_cancelar_solicitacao_pendente(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->visitante()->create();
        $house->members()->attach($user->id, ['role' => 'membro', 'status' => 'pending']);

        $response = $this->actingAs($user)->post("/houses/{$house->id}/cancel-request");

        $response->assertRedirect();
        $this->assertDatabaseHas('house_user', [
            'house_id' => $house->id,
            'user_id'  => $user->id,
            'status'   => 'cancelled',
        ]);
    }

    public function test_cancelamento_falha_sem_solicitacao_pendente(): void
    {
        $house = $this->makeActiveHouse();
        $user  = User::factory()->visitante()->create();

        $response = $this->actingAs($user)->post("/houses/{$house->id}/cancel-request");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // -------------------------------------------------------------------------
    // Edição da casa
    // -------------------------------------------------------------------------

    public function test_dirigente_pode_editar_propria_casa(): void
    {
        $owner = User::factory()->dirigente()->create();
        $house = $this->makeActiveHouse($owner);

        $response = $this->actingAs($owner)->post("/houses/{$house->id}/update", [
            'name' => 'Tenda São Jorge Atualizada',
            'type' => 'umbanda',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('houses', [
            'id'   => $house->id,
            'name' => 'Tenda São Jorge Atualizada',
        ]);
    }

    public function test_outro_usuario_nao_pode_editar_casa_alheia(): void
    {
        $house = $this->makeActiveHouse();
        $outro = User::factory()->dirigente()->create();

        $response = $this->actingAs($outro)->post("/houses/{$house->id}/update", [
            'name' => 'Tentativa de Hackear',
            'type' => 'umbanda',
        ]);

        $response->assertStatus(403);
    }
}
