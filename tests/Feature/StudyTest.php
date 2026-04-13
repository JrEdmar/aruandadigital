<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Study;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyTest extends TestCase
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

    private function addMembro(House $house): User
    {
        $membro = User::factory()->membro()->create();
        $house->members()->attach($membro->id, [
            'role'      => 'membro',
            'status'    => 'active',
            'joined_at' => now(),
        ]);
        return $membro;
    }

    private function makeStudy(House $house, User $creator, bool $published = true): Study
    {
        return Study::create([
            'house_id'     => $house->id,
            'created_by'   => $creator->id,
            'title'        => 'Fundamentos de Umbanda',
            'content_type' => 'text',
            'content_body' => 'Conteúdo do estudo...',
            'published'    => $published,
            'is_public'    => false,
            'points'       => 20,
        ]);
    }

    // -------------------------------------------------------------------------
    // Listagem
    // -------------------------------------------------------------------------

    public function test_lista_estudos_acessivel_para_membro_com_casa(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $this->actingAs($membro)->get('/studies')->assertStatus(200);
    }

    public function test_lista_estudos_redireciona_usuario_sem_casa(): void
    {
        // index() redireciona se usuário não tem casa ativa
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/studies')->assertRedirect('/houses');
    }

    public function test_estudos_publicos_acessiveis_para_qualquer_autenticado(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/studies/publicos')->assertStatus(200);
    }

    // -------------------------------------------------------------------------
    // Rota /studies/create — validação do bug de rota corrigido
    // -------------------------------------------------------------------------

    public function test_dirigente_acessa_pagina_de_criar_estudo(): void
    {
        // Antes da correção, GET /studies/create era interceptado por /studies/{id}
        // com id='create' e retornava 404. Agora deve retornar 200.
        [$dirigente] = $this->makeHouseWithDirigente();

        $this->actingAs($dirigente)->get('/studies/create')->assertStatus(200);
    }

    public function test_membro_nao_acessa_pagina_de_criar_estudo(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);

        $this->actingAs($membro)->get('/studies/create')->assertStatus(403);
    }

    public function test_visitante_nao_acessa_pagina_de_criar_estudo(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/studies/create')->assertStatus(403);
    }

    // -------------------------------------------------------------------------
    // Criação via MyCasaController (rota /my-house/studies)
    // -------------------------------------------------------------------------

    public function test_dirigente_pode_criar_estudo_via_my_house(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();

        $response = $this->actingAs($dirigente)->post('/my-house/studies', [
            'title'        => 'Orixás e seus Fundamentos',
            'content_type' => 'text',
            'content_body' => 'Texto detalhado...',
            'published'    => '1',
        ]);

        $response->assertRedirect(route('my-house', ['tab' => 'estudos']));
        $this->assertDatabaseHas('studies', [
            'house_id' => $house->id,
            'title'    => 'Orixás e seus Fundamentos',
        ]);
    }

    // -------------------------------------------------------------------------
    // Detalhe do estudo
    // -------------------------------------------------------------------------

    public function test_membro_acessa_estudo_publicado_da_sua_casa(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);
        $estudo = $this->makeStudy($house, $dirigente, published: true);

        $this->actingAs($membro)->get("/studies/{$estudo->id}")->assertStatus(200);
    }

    public function test_membro_sem_acesso_e_redirecionado_de_estudo_privado(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $estudo = $this->makeStudy($house, $dirigente, published: true);

        // Outro usuário sem vínculo com a casa
        $forasteiro = User::factory()->visitante()->create();
        $response = $this->actingAs($forasteiro)->get("/studies/{$estudo->id}");

        $response->assertRedirect(route('studies.public'));
    }

    public function test_id_inexistente_retorna_404(): void
    {
        $user = User::factory()->visitante()->create();
        $this->actingAs($user)->get('/studies/99999')->assertStatus(404);
    }

    // -------------------------------------------------------------------------
    // Edição (/studies/{id}/edit)
    // -------------------------------------------------------------------------

    public function test_dirigente_acessa_pagina_de_editar_estudo(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $estudo = $this->makeStudy($house, $dirigente);

        $this->actingAs($dirigente)->get("/studies/{$estudo->id}/edit")->assertStatus(200);
    }

    public function test_membro_nao_acessa_pagina_de_editar_estudo(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);
        $estudo = $this->makeStudy($house, $dirigente);

        $this->actingAs($membro)->get("/studies/{$estudo->id}/edit")->assertStatus(403);
    }

    // -------------------------------------------------------------------------
    // Conclusão de estudo — complete() retorna JSON
    // -------------------------------------------------------------------------

    public function test_membro_pode_marcar_estudo_como_concluido(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);
        $estudo = $this->makeStudy($house, $dirigente, published: true);

        $response = $this->actingAs($membro)
            ->postJson("/studies/{$estudo->id}/complete");

        $response->assertStatus(200);
        $this->assertDatabaseHas('study_progress', [
            'user_id'  => $membro->id,
            'study_id' => $estudo->id,
        ]);
    }

    public function test_estudo_nao_pode_ser_concluido_duas_vezes(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $membro = $this->addMembro($house);
        $estudo = $this->makeStudy($house, $dirigente, published: true);

        // Primeira conclusão
        $this->actingAs($membro)->postJson("/studies/{$estudo->id}/complete");

        // Segunda tentativa
        $response = $this->actingAs($membro)
            ->postJson("/studies/{$estudo->id}/complete");

        $response->assertStatus(422);
    }

    public function test_usuario_sem_casa_nao_pode_concluir_estudo(): void
    {
        [$dirigente, $house] = $this->makeHouseWithDirigente();
        $estudo = $this->makeStudy($house, $dirigente, published: true);

        $forasteiro = User::factory()->visitante()->create();
        $response = $this->actingAs($forasteiro)
            ->postJson("/studies/{$estudo->id}/complete");

        $response->assertStatus(403);
    }
}
