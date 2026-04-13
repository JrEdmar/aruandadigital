<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\House;
use App\Models\Study;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyCasaTest extends TestCase
{
    use RefreshDatabase;

    /** Cria uma casa ativa com dono dirigente já vinculado. */
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

    /** Adiciona um membro ativo a uma casa. */
    private function addMembro(House $house, ?User $user = null): User
    {
        $user ??= User::factory()->membro()->create();
        $house->members()->attach($user->id, [
            'role'      => 'membro',
            'status'    => 'active',
            'joined_at' => now(),
        ]);
        return $user;
    }

    // -------------------------------------------------------------------------
    // Acesso por role
    // -------------------------------------------------------------------------

    public function test_visitante_ve_tela_de_filiacao(): void
    {
        $user = User::factory()->visitante()->create();
        $response = $this->actingAs($user)->get('/my-house');

        $response->assertStatus(200);
        $response->assertViewIs('my-house.visitante');
    }

    public function test_membro_sem_casa_ve_tela_de_filiacao(): void
    {
        $user = User::factory()->membro()->create();
        $response = $this->actingAs($user)->get('/my-house');

        // membro sem casa associada → visitante view
        $response->assertStatus(200);
        $response->assertViewIs('my-house.visitante');
    }

    public function test_membro_com_casa_ve_painel(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $response = $this->actingAs($membro)->get('/my-house');

        $response->assertStatus(200);
        $response->assertViewIs('my-house.index');
    }

    public function test_dirigente_ve_painel_com_aba_de_gestao(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $response = $this->actingAs($dirigente)->get('/my-house');

        $response->assertStatus(200);
        $response->assertViewIs('my-house.index');
        $response->assertViewHas('isManager', true);
    }

    // -------------------------------------------------------------------------
    // Criação de eventos
    // -------------------------------------------------------------------------

    public function test_dirigente_pode_criar_evento(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();

        $response = $this->actingAs($dirigente)->post('/my-house/events', [
            'name'       => 'Gira de Umbanda',
            'starts_at'  => now()->addDays(7)->format('Y-m-d H:i:s'),
            'visibility' => 'public',
        ]);

        $response->assertRedirect(route('my-house', ['tab' => 'eventos']));
        $this->assertDatabaseHas('events', [
            'house_id' => $house->id,
            'name'     => 'Gira de Umbanda',
        ]);
    }

    public function test_membro_nao_pode_criar_evento(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $response = $this->actingAs($membro)->post('/my-house/events', [
            'name'      => 'Evento Não Autorizado',
            'starts_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(403);
    }

    public function test_evento_exige_campo_name(): void
    {
        [$dirigente] = $this->makeHouseWithDirigente();

        $response = $this->actingAs($dirigente)->post('/my-house/events', [
            'starts_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
        ]);

        $response->assertSessionHasErrors('name');
    }

    // -------------------------------------------------------------------------
    // Criação de tarefas
    // -------------------------------------------------------------------------

    public function test_dirigente_pode_criar_tarefa(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $response = $this->actingAs($dirigente)->post('/my-house/tasks', [
            'title'       => 'Limpar o salão',
            'assigned_to' => $membro->id,
            'points'      => 10,
        ]);

        $response->assertRedirect(route('my-house', ['tab' => 'tarefas']));
        $this->assertDatabaseHas('tasks', [
            'house_id'    => $house->id,
            'title'       => 'Limpar o salão',
            'assigned_to' => $membro->id,
        ]);
    }

    public function test_assistente_pode_criar_tarefa(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $assistente = User::factory()->assistente()->create();
        $house->members()->attach($assistente->id, [
            'role'      => 'assistente',
            'status'    => 'active',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($assistente)->post('/my-house/tasks', [
            'title' => 'Organizar roupas dos orixás',
        ]);

        $response->assertRedirect(route('my-house', ['tab' => 'tarefas']));
    }

    public function test_membro_nao_pode_criar_tarefa(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $response = $this->actingAs($membro)->post('/my-house/tasks', [
            'title' => 'Tarefa Não Autorizada',
        ]);

        $response->assertStatus(403);
    }

    public function test_status_de_tarefa_pode_ser_atualizado(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $tarefa = Task::create([
            'house_id'   => $house->id,
            'created_by' => $dirigente->id,
            'title'      => 'Tarefa Teste',
            'status'     => 'pending',
            'points'     => 5,
        ]);

        $response = $this->actingAs($dirigente)
            ->post("/my-house/tasks/{$tarefa->id}/status", ['status' => 'in_progress']);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['id' => $tarefa->id, 'status' => 'in_progress']);
    }

    // -------------------------------------------------------------------------
    // Criação de estudos
    // -------------------------------------------------------------------------

    public function test_dirigente_pode_criar_estudo(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();

        $response = $this->actingAs($dirigente)->post('/my-house/studies', [
            'title'        => 'Orixás e seus fundamentos',
            'content_type' => 'text',
            'content_body' => 'Conteúdo do estudo...',
            'published'    => '1',
        ]);

        $response->assertRedirect(route('my-house', ['tab' => 'estudos']));
        $this->assertDatabaseHas('studies', [
            'house_id' => $house->id,
            'title'    => 'Orixás e seus fundamentos',
        ]);
    }

    public function test_membro_nao_pode_criar_estudo_via_my_house(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $response = $this->actingAs($membro)->post('/my-house/studies', [
            'title'        => 'Estudo Não Autorizado',
            'content_type' => 'text',
        ]);

        $response->assertStatus(403);
    }

    // -------------------------------------------------------------------------
    // Aprovação/rejeição de membros
    // -------------------------------------------------------------------------

    public function test_dirigente_pode_aprovar_membro_pendente(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $candidato = User::factory()->visitante()->create();
        $house->members()->attach($candidato->id, [
            'role'   => 'membro',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($dirigente)
            ->post("/my-house/members/{$candidato->id}/approve", [
                'role_membro' => 'médium',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('house_user', [
            'house_id' => $house->id,
            'user_id'  => $candidato->id,
            'status'   => 'active',
        ]);
    }

    public function test_dirigente_pode_rejeitar_membro_pendente(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $candidato = User::factory()->visitante()->create();
        $house->members()->attach($candidato->id, [
            'role'   => 'membro',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($dirigente)
            ->post("/my-house/members/{$candidato->id}/reject");

        $response->assertRedirect();
        $this->assertDatabaseHas('house_user', [
            'house_id' => $house->id,
            'user_id'  => $candidato->id,
            'status'   => 'rejected',
        ]);
    }

    // -------------------------------------------------------------------------
    // Sugestões de membros
    // -------------------------------------------------------------------------

    public function test_membro_pode_enviar_sugestao(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $response = $this->actingAs($membro)->post('/my-house/suggestions', [
            'message' => 'Gostaria de sugerir mais estudos sobre Xangô.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('house_suggestions', [
            'house_id' => $house->id,
            'user_id'  => $membro->id,
        ]);
    }

    public function test_visitante_sem_casa_nao_pode_enviar_sugestao(): void
    {
        $user = User::factory()->visitante()->create();

        $response = $this->actingAs($user)->post('/my-house/suggestions', [
            'message' => 'Sugestão qualquer',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
