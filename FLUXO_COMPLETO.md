# ARUANDA DIGITAL — FLUXO COMPLETO DO APP
> Versão 4 · Atualizado em 2026-04-09
> Baseado nos mockups `/TELAS MODELO DO APP/` e arquitetura de rotas

---

## PERFIS DE USUÁRIO

| Perfil | Descrição |
|--------|-----------|
| **Visitante** | Cadastrado sem filiação a nenhuma casa |
| **Membro** | Filiado e aprovado em uma ou mais casas |
| **Assistente** | Membro com permissão para criar/gerir tarefas e fazer check-in |
| **Dirigente Auxiliar** | Membro com cargo intermediário (entre membro e assistente) |
| **Dirigente** | Dono/gestor da casa — acesso total ao workspace |
| **Loja** | Vendedor com acesso ao painel seller |
| **Loja Master** | Loja com acesso ao catálogo de atacado |
| **Moderador** | Acesso ao painel admin (sem gerir roles) |
| **Admin** | Administrador global da plataforma |

---

## FLUXO 1 — ENTRADA (visitante não autenticado)

```
[Splash / Welcome]
        │
        ├──► [LOGIN]
        │       • Campo e-mail
        │       • Campo senha + toggle mostrar/ocultar
        │       • Botão ENTRAR (verde, full-width)
        │       • Link "Esqueci minha senha"
        │       • Separador "ou continue com"
        │       • Botão Google OAuth
        │       • Botão Facebook OAuth
        │       • Link "Cadastre-se"
        │       └──► autenticado → [HOME]
        │
        ├──► [RECUPERAR SENHA]
        │       • Campo e-mail
        │       • Botão "Enviar link"
        │       └──► e-mail enviado → [REDEFINIR SENHA]
        │               • Campo nova senha
        │               • Campo confirmar senha
        │               └──► → [LOGIN]
        │
        └──► [CADASTRO]
                • Seletor de tipo: Visitante / Casa / Templo / Loja
                • Campo nome completo
                • Campo e-mail
                • Campo senha + confirmar senha
                • Aceite LGPD
                • Botão CADASTRAR (texto muda conforme tipo)
                • Link "Já tenho conta"
                └──► autenticado → [HOME]
```

---

## FLUXO 2 — HOME (todos os perfis autenticados)

```
[HOME]
  │
  ├── Header
  │     • Saudação (Bom dia/tarde/noite + nome)
  │     • Avatar → [PERFIL]
  │     • Sino de notificações → [NOTIFICAÇÕES]
  │     • Barra de busca → [LISTA DE CASAS]
  │
  ├── Quick Actions (chips horizontais)
  │     • 🏠 Casas → [LISTA DE CASAS]
  │     • 📅 Eventos → [LISTA DE EVENTOS]
  │     • 📍 Mapa → [MAPA]
  │     • 🛒 Loja → [VITRINE]
  │     • 🏡 Minha Casa → [MINHA CASA]  ← só se filiado
  │
  ├── Seção "Casas em Destaque"
  │     • Cards com foto + nome + cidade + membros + eventos
  │     • "Ver todas" → [LISTA DE CASAS]
  │     └── tap no card → [DETALHE DA CASA]
  │
  └── Seção "Próximos Eventos"
        • Cards com data + imagem + nome + horário + casa + badges
        • "Ver todos" → [LISTA DE EVENTOS]
        └── tap no card → [DETALHE DO EVENTO]
```

---

## FLUXO 3 — CASAS / TEMPLOS

```
[LISTA DE CASAS]
  │
  ├── Busca + Filtros (Todos / Umbanda / Candomblé / Misto / Espírita)
  ├── Cards de casa (foto, nome, cidade, membros, eventos)
  └── tap no card → [DETALHE DA CASA]
                        │
                        ├── Foto de capa full-width
                        ├── Logo + Nome + Endereço
                        ├── Linha de stats (Vagas · Membros · Territórios)
                        ├── Botões de ação
                        │     • [Participar] → Modal de Filiação (SweetAlert)
                        │           • Selecionar função desejada
                        │           • Mensagem para o dirigente
                        │           └──► solicitação enviada (aguarda aprovação)
                        │     • [Cancelar solicitação] → cancela pedido pendente
                        │     • [Eventos] → aba Eventos da casa
                        │     • [Chegar] → Google Maps externo
                        │
                        └── Abas
                              • [Info]
                              │    Descrição, linha espiritual, história
                              │    Diferenciais (tags)
                              │    Grade: horários por dia da semana, telefone,
                              │           fundação, capacidade, tipo
                              │    Mapa OpenStreetMap + botão "Ver no mapa"
                              │
                              • [Lista]
                              │    Contador de membros ativos
                              │    Lista: avatar + nome + cargo
                              │    Botão "Solicitar Filiação"
                              │
                              • [Contato]
                              │    Telefone, site, e-mail, endereço
                              │    Botões: Facebook, Instagram, WhatsApp
                              │    Botão Compartilhar
                              │
                              └── [Subgrupos / Eventos]
                                   Lista de eventos da casa
                                   tap → [DETALHE DO EVENTO]
```

---

## FLUXO 4 — EVENTOS

```
[LISTA DE EVENTOS]
  │
  ├── Filtros: Próximos / Gratuitos / Pagos
  ├── Cards: coluna data + imagem + nome + horário + casa + badges
  └── tap → [DETALHE DO EVENTO]
                  │
                  ├── Foto de capa (banner) + botão voltar + compartilhar + salvar
                  ├── Nome + chips (status, data, hora) sobre a foto
                  ├── Botão "CONFIRMAR PRESENÇA" (verde, destaque)
                  │
                  └── Abas
                        • [Informações]
                        │    Grid 2x2: Data início, Data fim, Horário, Vagas
                        │    Endereço do evento
                        │    Descrição do evento
                        │    Regras de participação
                        │    Recomendações / O que levar
                        │    Card organizador → [DETALHE DA CASA]
                        │
                        • [Membros / Inscritos]
                        │    Contador + barra de progresso de vagas
                        │    Lista: avatar inicial + nome + badge (Inscrito/Check-in)
                        │
                        • [Galeria]
                        │    Grid de fotos do evento
                        │
                        └── [Sobre a Casa]
                             Info resumida da casa organizadora
                             Link → [DETALHE DA CASA]
                  │
                  └── Action Bar (rodapé fixo)
                        • Botão compartilhar
                        • Botão INSCREVER-SE / CANCELAR INSCRIÇÃO / ESGOTADO

[MINHA LISTA DE EVENTOS]  (via bottom bar ou menu)
  ├── Aba FUTURO — próximos eventos inscritos
  └── Aba PASSADO — histórico de eventos frequentados
        tap → [DETALHE DO EVENTO]
```

---

## FLUXO 5 — MINHA CASA (Workspace)

> Acesso: Visitante (tela de convite) · Membro (participação) · Assistente (tarefas) · Dirigente (gestão total)

### 5.1 — Visão do Visitante / sem casa

```
[MINHA CASA — Visitante]
  └── Tela de convite para solicitar filiação a uma casa
        • Botão "Encontrar uma Casa" → [LISTA DE CASAS]
```

### 5.2 — Visão do Membro

```
[MINHA CASA — Membro]
  │
  └── Abas
        • [Visão Geral]
        │    Info da casa (nome, linha espiritual, endereço)
        │    Horários de gira por dia da semana
        │    Próximos eventos da casa
        │
        • [Membros]
        │    Lista de membros ativos com foto + nome + cargo + entidades
        │
        • [Eventos]
        │    Lista de eventos com status (aberto/encerrado/cancelado)
        │    tap → [DETALHE DO EVENTO]
        │
        • [Estudos]
        │    Lista de materiais publicados
        │    tap → [ESTUDO]
        │
        • [Tarefas]
        │    Lista de tarefas agrupadas por título (accordion)
        │    Cada grupo mostra: título + quantidade + status geral
        │    Expandir grupo → lista de membros atribuídos
        │    FLUXO DA TAREFA (membro):
        │       pending → [▶ Iniciar] (confirmação SweetAlert) → in_progress
        │       in_progress → [✓ Concluir] (confirmação SweetAlert) → completed
        │       completed → "Aguardando validação" (dirigente valida)
        │       approved → ✅ (ícone de aprovado)
        │
        • [Financeiro / Sugestões]
              Painel de sugestões para o dirigente:
              • Formulário: textarea + botão Enviar
              • Histórico de sugestões enviadas pelo membro
              Botão de check-in (nos dias de gira da casa):
              • Confirmar check-in com SweetAlert
              • Só disponível no dia do evento
```

### 5.3 — Visão do Dirigente / Assistente

```
[MINHA CASA — Dirigente]
  │
  └── Abas
        • [Visão Geral]
        │    Cards de stats: total membros, eventos ativos, estudos publicados
        │    Feed "Atividades Recentes":
        │       - novo membro aprovado
        │       - evento criado/cancelado
        │       - tarefa concluída/validada
        │       - pagamento registrado
        │
        • [Membros]
        │    Lista: foto + nome + cargo + cargo de função + entidades + status
        │    Seção "Solicitações Pendentes"
        │       [Aprovar] → define cargo de função (médium/cambone/dirigente auxiliar)
        │       [Rejeitar] → notificação ao usuário
        │    Alterar cargo do membro (membro/assistente/dirigente auxiliar/dirigente)
        │    Transferência de dirigência → aguarda aprovação do Admin
        │    Botão "Avisar todos" → Modal de notificação coletiva
        │    Botão de chat individual → Modal de notificação individual
        │         Modal contém: título + mensagem + seletor de alvo
        │
        • [Eventos]
        │    Lista de eventos com status
        │    Botão "Criar Evento" → Modal com:
        │       nome, data início, data fim, horário, endereço,
        │       vagas, preço, descrição, regras, recomendações,
        │       visibilidade (público/membros), banner (upload de imagem)
        │    Editar evento → mesmo modal preenchido
        │    Cancelar evento → confirmação
        │
        • [Estudos]
        │    Lista com toggle publicado/rascunho
        │    Botão "Criar Material" → Modal com:
        │       título, descrição, tipo (texto/vídeo/áudio/PDF),
        │       URL (vídeo/áudio) ou upload PDF, categoria,
        │       pontos XP, público (sim/não), publicar agora
        │    Editar material → mesmo modal
        │
        • [Tarefas]
        │    Lista agrupada por título (accordion)
        │    Botão "Criar Tarefa" → Modal com:
        │       título, descrição, data limite, atribuir a (membro ou Todos),
        │       pontos XP
        │    "Distribuir aleatoriamente" → atribui tarefas sem dono
        │    FLUXO DA TAREFA (dirigente):
        │       completed → [✔ Validar] (confirmação) → approved + XP ao membro
        │       completed → [✗ Devolver] (confirmação) → in_progress (membro revisa)
        │
        • [Financeiro]
              Visível apenas ao dirigente/admin
              Saldo: total entradas − total saídas
              Lista de lançamentos (accordion — minimizado por padrão):
                 Clique no cabeçalho → expande detalhes do lançamento
                 Escopo: Global / Todos os membros / Membros selecionados
                 Para lançamentos de membros: lista de pagantes com toggle pago/pendente
                    Toggle solicita confirmação SweetAlert
              Botão "Registrar Lançamento" → Modal com:
                 tipo (entrada/saída), título, valor, status,
                 data vencimento, escopo, seleção de membros
              Filtros: Todos / Pendentes / Pagos / Vencidos
              Sugestões dos membros: lista com mensagem + nome + data
```

---

## FLUXO 6 — CHECK-IN

> Acesso dirigente/assistente: Scanner de QR para check-in de membros
> Acesso membro: Auto check-in nos dias de gira

```
[CHECK-IN — Dirigente/Assistente]
  ├── Scanner de QR Code da carteirinha do membro
  ├── Confirmação visual (✅ aprovado / ❌ não inscrito)
  └── Lista de presentes/ausentes em tempo real

[CHECK-IN — Membro]  (via aba Minha Casa)
  ├── Botão "Fazer Check-in" (visível apenas no dia do evento)
  ├── Confirmação SweetAlert: "Confirmar presença na gira de hoje?"
  └── Registra status "checked_in" com timestamp no pivot event_user
```

---

## FLUXO 7 — PERFIL

```
[PERFIL]
  ├── Avatar + nome + cargo + casa
  ├── Stats: eventos frequentados, casas, pontos XP
  ├── Botão "Editar Perfil" → [EDITAR PERFIL]
  │       • Upload avatar
  │       • Nome, telefone, data de nascimento
  │       • Salvar
  ├── Botão "Carteirinha" → [CARTEIRINHA DIGITAL]
  │       • Card visual: foto + nome + casa + cargo
  │       • QR Code único do membro
  │       • Botão salvar/compartilhar imagem
  ├── Link "Alterar Senha" → [ALTERAR SENHA]
  └── Link "Configurações" → [CONFIGURAÇÕES]

[EDITAR PERFIL — Dirigente]
  Seção adicional: Configurações da Casa
  ├── Nome da casa, linha espiritual, descrição
  ├── Endereço, capacidade, tipo (público/privado)
  ├── Horários por dia da semana:
  │     Checkboxes: Seg / Ter / Qua / Qui / Sex / Sáb / Dom
  │     Cada dia ativo tem campo de horário (HH:MM)
  │     Salva como JSON: {"seg":"20:00","sex":"19:30"}
  └── Redes sociais e contatos
```

---

## FLUXO 8 — ESTUDOS

> Acesso público: estudos marcados como `is_public`
> Acesso membro: todos os estudos publicados da sua casa

```
[LISTA DE ESTUDOS]
  ├── Estudos Públicos → acessíveis a qualquer usuário autenticado
  ├── Módulos com barra de progresso e badge (concluído/em andamento/bloqueado)
  └── tap → [ESTUDO]
                ├── Título + categoria + pontos XP
                ├── Conteúdo conforme tipo:
                │     text  → corpo HTML renderizado
                │     video → player embed (YouTube/Vimeo)
                │     audio → player de áudio
                │     pdf   → iframe embed do PDF + link "Baixar"
                └── Botão "Marcar como concluído" → XP ganho (gamificação)
```

---

## FLUXO 9 — LOJA / E-COMMERCE

```
[VITRINE]
  │
  ├── Busca + Filtros por categoria
  ├── Grid de produtos: imagem + nome + preço + badge (Novo/Hot/Promoção)
  └── tap → [DETALHE DO PRODUTO]
                  ├── Fotos do produto
                  ├── Nome + preço + descrição
                  ├── Seletor de quantidade
                  └── Botão "Adicionar ao Carrinho" → [CARRINHO]

[CARRINHO]
  ├── Lista de itens com quantidade + subtotal por item
  ├── Remover item
  ├── Subtotal + total
  └── Botão "Finalizar Compra" → [CHECKOUT]

[CHECKOUT]
  ├── Resumo do pedido
  ├── Validação de estoque em tempo real (preços e disponibilidade recarregados do banco)
  ├── Forma de pagamento: PIX / Cartão / Boleto
  └── Botão "Confirmar Pedido" → [PEDIDOS]
        Estoque decrementado atomicamente ao confirmar

[PEDIDOS]
  └── Histórico de pedidos com status (pendente/enviado/entregue)
```

---

## FLUXO 10 — ÁREA SELLER

> Acesso: Loja · Loja Master · Admin

```
[DASHBOARD SELLER]
  ├── Stats: vendas do mês, receita, pedidos pendentes
  ├── Lista de produtos ativos
  ├── Botão "Novo Produto" → [CRIAR PRODUTO]
  │       Form: nome, preço (mín. R$ 0,01), estoque, fotos, descrição, categoria
  ├── Pedidos recentes
  └── Link "Atacado" → [ATACADO]  ← loja_master
                          ├── Catálogo atacado
                          └── Tabela de preços por volume
```

---

## FLUXO 11 — GAMIFICAÇÃO

```
[DASHBOARD XP]
  ├── XP atual + nível + título (ex: "Ogã Iniciante")
  ├── Barra de progresso para próximo nível
  ├── Conquistas recentes
  └── Links:
        • [CONQUISTAS] — grid de badges desbloqueados/bloqueados
        └── [RANKING]
               ├── Top 3 em destaque (pódio)
               └── Lista: posição + avatar + nome + XP
```

---

## FLUXO 12 — NOTIFICAÇÕES

```
[NOTIFICAÇÕES]
  ├── Botão "Marcar todas como lidas"
  ├── Lista agrupada por data
  │     • 🟢 Filiação aprovada/rejeitada
  │     • 📅 Novo evento na sua casa
  │     • ✅ Tarefa atribuída / validada / devolvida
  │     • 🛒 Pedido atualizado
  │     • 🏆 Conquista desbloqueada
  │     • ⚠️ Alerta de emergência
  │     • 💬 Mensagem da casa (individual ou coletiva)
  └── tap → tela correspondente ao tipo da notificação
```

---

## FLUXO 13 — MAPA

```
[MAPA]
  ├── Mapa interativo (OpenStreetMap / Leaflet)
  ├── Marcadores de casas/templos próximos
  ├── Filtro por tipo
  └── tap no marcador → popup com nome + botão "Ver Casa" → [DETALHE DA CASA]
```

---

## FLUXO 14 — CONFIGURAÇÕES

```
[CONFIGURAÇÕES]
  ├── Notificações: toggle push / toggle e-mail
  ├── Privacidade: perfil público/privado
  ├── Idioma
  ├── Tema (claro/escuro — futuro)
  ├── Sobre o app / Versão
  ├── Botão SAIR (logout) → [LOGIN]
  └── Botão "Excluir conta" (confirmação SweetAlert)
```

---

## FLUXO 15 — PAINEL ADMIN

> Acesso: Admin · Moderador

```
[PAINEL ADMIN]
  ├── Stats globais: usuários, casas ativas, eventos, receita
  │
  ├── Seção "Casas Pendentes de Aprovação"
  │     Lista de casas recém-cadastradas
  │     [Aprovar] → casa ativa, dirigente notificado
  │     [Rejeitar] → casa rejeitada, motivo enviado por e-mail
  │
  ├── Seção "Usuários"
  │     Busca por nome/e-mail
  │     Alterar cargo/role
  │     Suspender conta
  │
  ├── Seção "Transferência de Dirigência"
  │     Solicitações pendentes de troca de dirigente
  │     [Aprovar] / [Rejeitar] transferência
  │
  └── Seção "Moderação"
        Denúncias recebidas
        Conteúdo pendente de revisão
```

---

## BOTTOM BAR — NAVEGAÇÃO PRINCIPAL

| Ícone | Label | Rota | Visível para |
|-------|-------|------|-------------|
| 🏠 | Início | `/` | Todos |
| 📅 | Eventos | `/events` | Todos |
| 🏡 | Minha Casa | `/my-house` | Membros+ |
| 🛒 | Loja | `/shop` | Todos |
| 👤 | Perfil | `/profile` | Todos |

---

## DIAGRAMA DE PAPÉIS E ACESSOS

```
VISITANTE (autenticado, sem filiação)
  └── Home, Casas, Eventos (ver), Mapa, Loja, Perfil,
      Notificações, Gamificação, Solicitar Filiação,
      Estudos Públicos

MEMBRO (filiado e aprovado)
  └── Tudo do Visitante +
      Estudos da casa, Minha Casa (participação),
      Carteirinha Digital, Check-in de gira,
      Envio de sugestões ao dirigente,
      Iniciar/Concluir próprias tarefas

DIRIGENTE AUXILIAR
  └── Tudo do Membro (cargo honorífico — sem permissões extras no sistema)

ASSISTENTE
  └── Tudo do Membro +
      Criar/atribuir/randomizar tarefas,
      Check-in de eventos (scanner)

DIRIGENTE
  └── Tudo do Assistente +
      Workspace completo: Dashboard, Membros, Eventos,
      Estudos, Tarefas (validação), Finanças,
      Aprovar/Rejeitar filiações, Alterar cargos,
      Enviar notificações individuais e coletivas,
      Ver sugestões dos membros,
      Configurações da casa (horários por dia)

LOJA
  └── Dashboard Seller, Criar Produtos, Pedidos

LOJA MASTER
  └── Tudo da Loja + Área de Atacado

MODERADOR
  └── Tudo do usuário autenticado + Painel Admin (sem gerir roles)

ADMIN
  └── Tudo +
      Painel Admin, Aprovar Casas, Gerir Usuários/Roles,
      Aprovar transferências de dirigência
```

---

## FLUXO DE TAREFAS — ESTADOS E PERMISSÕES

```
[CRIAÇÃO]
  Assistente/Dirigente/Admin → cria tarefa:
    • Atribuir a: membro específico ou "Todos os membros"
    • "Todos" → cria uma tarefa por membro ativo (em transação)
    • "Distribuir aleatoriamente" → distribui pendentes sem dono

[EXECUÇÃO — pelo membro atribuído]
  pending    → [▶ Iniciar]  (confirmação) → in_progress
  in_progress → [✓ Concluir] (confirmação) → completed

[VALIDAÇÃO — pelo dirigente]
  completed → [✔ Validar]  (confirmação) → approved  (+XP ao membro)
  completed → [✗ Devolver] (confirmação) → in_progress (membro revisa)

[VISIBILIDADE]
  • Apenas o membro atribuído vê os botões de ação
  • Dirigente vê botões de Validar/Devolver
  • Outros membros veem apenas o status
```

---

## FLUXO FINANCEIRO — ESCOPOS E PAGAMENTOS

```
[LANÇAMENTO]
  Dirigente cria com escopo:
    • Global       → registro único da casa (pago/pendente diretamente)
    • Todos membros → gera registro individual por membro ativo
    • Selecionados  → gera registro para membros escolhidos
                       (validado contra lista de membros ativos)

[EXIBIÇÃO]
  • Lista accordion (minimizada por padrão)
  • Clique no cabeçalho → expande detalhes e membros
  • Saldo visível apenas ao dirigente/admin

[PAGAMENTO]
  Toggle pago/pendente → confirmação SweetAlert → registro atualizado
  Campos: status, paid_at
```

---

## EVENTOS QUE GERAM XP (Gamificação)

| Ação | XP |
|------|-----|
| Primeiro login | +50 |
| Completar perfil | +30 |
| Filiar-se a uma casa | +100 |
| Participar de evento | +20 |
| Check-in confirmado | +30 |
| Concluir módulo de estudo | +50 |
| Tarefa aprovada pelo dirigente | variável (definido na tarefa) |
| Convidar novo membro | +40 |
| Realizar compra na loja | +10 |

---

## NOTIFICAÇÕES AUTOMÁTICAS

| Gatilho | Quem recebe | Canal |
|---------|------------|-------|
| Solicitação de filiação | Dirigente | Push + in-app |
| Filiação aprovada/rejeitada | Membro | Push + in-app |
| Novo evento criado | Membros da casa | Push + in-app |
| Evento em 24h | Inscritos | Push |
| Tarefa atribuída | Membro | Push + in-app |
| Tarefa concluída (aguardando validação) | Dirigente | In-app |
| Tarefa aprovada/devolvida | Membro | In-app |
| Check-in realizado | Membro | In-app |
| Mensagem da casa (coletiva) | Todos os membros | In-app |
| Mensagem individual da casa | Membro alvo | In-app |
| Pedido confirmado | Comprador | E-mail + in-app |
| Conquista desbloqueada | Usuário | In-app |
| Casa aprovada pela plataforma | Dirigente | E-mail + push |
| Transferência de dirigência solicitada | Admin | In-app |

---

## SEGURANÇA E AUTORIZAÇÕES (implementadas)

| Área | Regra |
|------|-------|
| Tarefas | Toda busca de tarefa filtra por `house_id` da casa do usuário |
| Finanças | Toggle de pagamento valida que a finance pertence à casa |
| Membros de finance | `member_ids` aceitos apenas se forem membros ativos da casa |
| Estudos | Dirigente/assistente só edita estudos da própria casa |
| Pedidos | Preço e estoque revalidados do banco no momento do checkout |
| Estoque | Decrementado atomicamente dentro de `DB::transaction` |
| Tarefas em massa | Criação para "todos" dentro de `DB::transaction` |
| Notificações em massa | Envio dentro de `DB::transaction` |
| Eventos | FK `created_by` com `ON DELETE SET NULL` |
