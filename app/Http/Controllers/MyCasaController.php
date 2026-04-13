<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\HouseFinance;
use App\Models\HouseFinanceMember;
use App\Models\HouseSuggestion;
use App\Models\Study;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MyCasaController extends Controller
{
    /** Painel de gestão da casa do dirigente/assistente. */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Visitante (sem filiação ativa) → tela de convite para filiar-se
        $allowedRoles = ['membro', 'assistente', 'dirigente', 'admin'];
        if (! in_array($user->role, $allowedRoles)) {
            return view('my-house.visitante', compact('user'));
        }

        $tab   = $request->get('tab', 'visao-geral');

        $house = $user->ownedHouses()->with([
            'activeMembers',
            'upcomingEvents',
        ])->first();

        if (! $house) {
            $house = $user->houses()
                ->wherePivot('status', 'active')
                ->with(['activeMembers', 'upcomingEvents'])
                ->first();
        }

        // Membro sem casa ativa → mesma tela de filiação do visitante
        if (! $house) {
            return view('my-house.visitante', compact('user'));
        }

        $isManager = $user->hasRole('assistente,dirigente,admin');

        $members  = $house ? $house->members()->withPivot('role', 'role_membro', 'entities', 'status', 'message')->get() : collect();
        $tasks    = $house ? Task::where('house_id', $house->id)->with('assignedTo')->orderBy('due_date')->get() : collect();
        $finances = $house && $isManager ? HouseFinance::where('house_id', $house->id)->with('memberEntries.user')->latest()->limit(30)->get() : collect();
        $studiesQ = $house ? Study::where('house_id', $house->id) : null;
        $studies  = $studiesQ
            ? ($isManager ? $studiesQ->orderBy('order_column')->orderByDesc('created_at')->get()
                          : $studiesQ->where('published', true)->orderBy('order_column')->orderByDesc('created_at')->get())
            : collect();

        // Saldo real calculado via aggregate (não limitado pelo paginate)
        $balanceCredit = $house ? HouseFinance::where('house_id', $house->id)->where('type', 'credit')->sum('amount') : 0;
        $balanceDebit  = $house ? HouseFinance::where('house_id', $house->id)->where('type', 'debit')->sum('amount') : 0;

        // Sugestões dos membros (somente para gestores)
        $suggestions = $house && $isManager
            ? HouseSuggestion::where('house_id', $house->id)->with('user')->latest()->limit(20)->get()
            : collect();

        // Eventos de hoje (lembrete automático)
        $todayEvents = $house
            ? Event::where('house_id', $house->id)
                ->whereDate('starts_at', today())
                ->whereNotIn('status', ['cancelled', 'draft'])
                ->get()
            : collect();

        // Intenção do usuário nos eventos de hoje
        $todayUserIntents = collect();
        if ($todayEvents->count() > 0) {
            $todayUserIntents = DB::table('event_user')
                ->where('user_id', $user->id)
                ->whereIn('event_id', $todayEvents->pluck('id'))
                ->pluck('status', 'event_id');
        }

        // Estatísticas de intenção por evento (para dirigente)
        $eventIntentStats = [];
        if ($house && $isManager && $house->upcomingEvents->count() > 0) {
            $eventIds        = $house->upcomingEvents->pluck('id');
            $hasIntentColumn = Schema::hasColumn('event_user', 'intent');

            if ($hasIntentColumn) {
                $rawStats = DB::table('event_user')
                    ->whereIn('event_id', $eventIds)
                    ->selectRaw('event_id, intent, status, COUNT(*) as cnt')
                    ->groupBy('event_id', 'intent', 'status')
                    ->get();
            } else {
                $rawStats = DB::table('event_user')
                    ->whereIn('event_id', $eventIds)
                    ->selectRaw('event_id, status, COUNT(*) as cnt')
                    ->groupBy('event_id', 'status')
                    ->get();
            }

            foreach ($house->upcomingEvents as $ev) {
                $rows = $rawStats->where('event_id', $ev->id);
                $eventIntentStats[$ev->id] = [
                    'going'      => $hasIntentColumn ? $rows->where('intent', 'going')->sum('cnt') : 0,
                    'maybe'      => $hasIntentColumn ? $rows->where('intent', 'maybe')->sum('cnt') : 0,
                    'checked_in' => $rows->where('status', 'checked_in')->sum('cnt'),
                    'total'      => $rows->sum('cnt'),
                ];
            }
        }

        // Frequência média + membros inativos (últimos 5 eventos encerrados)
        $avgFrequency   = null;
        $inactiveCount  = 0;
        if ($house && $isManager) {
            $pastEventIds = Event::where('house_id', $house->id)
                ->where('status', 'finished')
                ->latest('starts_at')
                ->limit(5)
                ->pluck('id');

            if ($pastEventIds->count() > 0) {
                $freqData = DB::table('event_user')
                    ->whereIn('event_id', $pastEventIds)
                    ->selectRaw('event_id, COUNT(*) as total, SUM(CASE WHEN status = \'checked_in\' THEN 1 ELSE 0 END) as checked')
                    ->groupBy('event_id')
                    ->get();

                $totalReg     = $freqData->sum('total');
                $totalChecked = $freqData->sum('checked');
                $avgFrequency = $totalReg > 0 ? round(($totalChecked / $totalReg) * 100) : null;

                // Membros ativos sem nenhum check-in nos últimos 5 eventos
                $activeMemberIds = $house->activeMembers->pluck('id');
                $activeMemberIds = $activeMemberIds->filter(fn ($id) => $id !== $user->id);
                if ($activeMemberIds->count() > 0) {
                    $checkedInIds = DB::table('event_user')
                        ->whereIn('event_id', $pastEventIds)
                        ->where('status', 'checked_in')
                        ->whereIn('user_id', $activeMemberIds)
                        ->distinct()
                        ->pluck('user_id');

                    $inactiveCount = $activeMemberIds->diff($checkedInIds)->count();
                }
            }
        }

        return view('my-house.index', compact(
            'user', 'house', 'tab', 'members', 'tasks', 'finances',
            'balanceCredit', 'balanceDebit', 'studies', 'isManager', 'suggestions',
            'todayEvents', 'todayUserIntents', 'eventIntentStats', 'avgFrequency', 'inactiveCount'
        ));
    }

    /** Cria um novo evento para a casa — dirigente/admin. */
    public function storeEvent(Request $request): RedirectResponse
    {
        $user  = Auth::user();
        $house = $this->getUserHouse($user);

        if (! $house) {
            return back()->with('error', 'Você não está associado a nenhuma casa.');
        }

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'starts_at'       => ['required', 'date'],
            'ends_at'         => ['nullable', 'date'],
            'price'           => ['nullable', 'numeric', 'min:0'],
            'capacity'        => ['nullable', 'integer', 'min:1'],
            'address'         => ['nullable', 'string', 'max:500'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'rules'           => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'visibility'      => ['nullable', 'in:public,members_only'],
            'banner_image'    => ['nullable', 'image', 'max:4096'],
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('events/banners', 'public');
        }

        Event::create([
            'house_id'        => $house->id,
            'created_by'      => $user->id,
            'name'            => $data['name'],
            'starts_at'       => $data['starts_at'],
            'ends_at'         => $data['ends_at'] ?? null,
            'price'           => $data['price'] ?? 0,
            'capacity'        => $data['capacity'] ?? null,
            'address'         => $data['address'] ?? null,
            'description'     => $data['description'] ?? null,
            'rules'           => $data['rules'] ?? null,
            'recommendations' => $data['recommendations'] ?? null,
            'status'          => 'open',
            'visibility'      => $data['visibility'] ?? 'public',
            'banner_image'    => $bannerPath,
        ]);

        return redirect()->route('my-house', ['tab' => 'eventos'])
            ->with('success', 'Evento criado com sucesso!');
    }

    /** Cria uma nova tarefa para a casa — assistente/dirigente/admin. */
    public function storeTask(Request $request): RedirectResponse
    {
        $user  = Auth::user();
        $house = $this->getUserHouse($user);

        if (! $house) {
            return back()->with('error', 'Você não está associado a nenhuma casa.');
        }

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'due_date'    => ['nullable', 'date'],
            'assigned_to' => ['nullable'],
            'points'      => ['nullable', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($house, $user, $data) {
            if (($data['assigned_to'] ?? null) === 'all') {
                // Cria uma tarefa para cada membro ativo dentro de uma transação
                $memberIds = $house->activeMembers()->pluck('users.id');
                foreach ($memberIds as $memberId) {
                    Task::create([
                        'house_id'    => $house->id,
                        'created_by'  => $user->id,
                        'title'       => $data['title'],
                        'description' => $data['description'] ?? null,
                        'due_date'    => $data['due_date'] ?? null,
                        'assigned_to' => $memberId,
                        'points'      => $data['points'] ?? 0,
                        'status'      => 'pending',
                    ]);
                }
            } else {
                Task::create([
                    'house_id'    => $house->id,
                    'created_by'  => $user->id,
                    'title'       => $data['title'],
                    'description' => $data['description'] ?? null,
                    'due_date'    => $data['due_date'] ?? null,
                    'assigned_to' => !empty($data['assigned_to']) ? (int) $data['assigned_to'] : null,
                    'points'      => $data['points'] ?? 0,
                    'status'      => 'pending',
                ]);
            }
        });

        return redirect()->route('my-house', ['tab' => 'tarefas'])
            ->with('success', 'Tarefa criada com sucesso!');
    }

    /** Cria um lançamento financeiro para a casa — dirigente/admin. */
    public function storeFinance(Request $request): RedirectResponse
    {
        $user  = Auth::user();
        $house = $this->getUserHouse($user);

        if (! $house) {
            return back()->with('error', 'Você não está associado a nenhuma casa.');
        }

        $data = $request->validate([
            'type'         => ['required', 'in:credit,debit'],
            'title'        => ['required', 'string', 'max:255'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'status'       => ['nullable', 'in:pending,paid,overdue'],
            'due_date'     => ['nullable', 'date'],
            'scope'        => ['nullable', 'in:global,all_members,selected_members'],
            'member_ids'   => ['nullable', 'array'],
            'member_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $scope = $data['scope'] ?? 'global';

        $finance = HouseFinance::create([
            'house_id' => $house->id,
            'user_id'  => $user->id,
            'type'     => $data['type'],
            'title'    => $data['title'],
            'amount'   => $data['amount'],
            'status'   => $scope === 'global' ? ($data['status'] ?? 'paid') : 'pending',
            'due_date' => $data['due_date'] ?? null,
            'scope'    => $scope,
        ]);

        // Gera registro individual por membro
        if ($scope === 'all_members') {
            $memberIds = $house->activeMembers()->pluck('users.id');
        } elseif ($scope === 'selected_members') {
            // Garante que apenas membros ativos desta casa sejam aceitos
            $activeMemberIds = $house->activeMembers()->pluck('users.id');
            $memberIds = collect($data['member_ids'] ?? [])->intersect($activeMemberIds);
        } else {
            $memberIds = collect();
        }

        foreach ($memberIds as $memberId) {
            HouseFinanceMember::create([
                'finance_id' => $finance->id,
                'user_id'    => $memberId,
                'status'     => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('my-house', ['tab' => 'financeiro'])
            ->with('success', 'Lançamento registrado!');
    }

    /** Aprova um membro pendente na casa — dirigente/admin. */
    public function approveMember(Request $request, string $id): RedirectResponse
    {
        $user  = Auth::user();
        $house = $this->getUserHouse($user);

        if (! $house) {
            return back()->with('error', 'Você não está associado a nenhuma casa.');
        }

        $data = $request->validate([
            'role_membro' => ['nullable', 'in:médium,cambone,dirigente auxiliar'],
        ]);

        $house->members()->updateExistingPivot($id, [
            'status'      => 'active',
            'joined_at'   => now(),
            'role_membro' => $data['role_membro'] ?? null,
        ]);

        return redirect()->route('my-house', ['tab' => 'membros'])
            ->with('success', 'Membro aprovado com sucesso!');
    }

    /** Rejeita um membro pendente da casa — preserva histórico com status=rejected. */
    public function rejectMember(string $id): RedirectResponse
    {
        $user  = Auth::user();
        $house = $this->getUserHouse($user);

        if (! $house) {
            return back()->with('error', 'Você não está associado a nenhuma casa.');
        }

        $house->members()->updateExistingPivot($id, [
            'status' => 'rejected',
        ]);

        return redirect()->route('my-house', ['tab' => 'membros'])
            ->with('success', 'Solicitação rejeitada.');
    }

    /** Muda o cargo de um membro no pivot da casa — dirigente/admin. */
    public function updateMemberRole(Request $request, string $id): RedirectResponse
    {
        $data  = $request->validate([
            'role'       => ['required', 'in:membro,assistente,dirigente auxiliar,dirigente'],
            'role_membro'=> ['nullable', 'in:médium,cambone'],
            'entities'   => ['nullable', 'string', 'max:500'],
        ]);

        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Você não está associado a nenhuma casa.');

        // Regra: casa só pode ter UM dirigente
        if ($data['role'] === 'dirigente') {
            $currentDirigente = $house->members()
                ->wherePivot('role', 'dirigente')
                ->wherePivot('status', 'active')
                ->where('users.id', '!=', $id)
                ->first();

            if ($currentDirigente) {
                // Sinaliza transferência pendente — aguarda aprovação do admin
                $house->members()->updateExistingPivot($id, [
                    'status'  => 'pending_transfer',
                    'message' => 'Solicitação de transferência de dirigência para este membro.',
                ]);

                return redirect()->route('my-house', ['tab' => 'membros'])
                    ->with('warning', 'Solicitação de transferência de dirigência enviada para aprovação do administrador.');
            }
        }

        $update = ['role' => $data['role']];
        if (array_key_exists('role_membro', $data)) {
            $update['role_membro'] = $data['role_membro'];
        }
        $update['entities'] = $data['entities'] ?? null;

        $house->members()->updateExistingPivot($id, $update);

        return redirect()->route('my-house', ['tab' => 'membros'])
            ->with('success', 'Cargo atualizado com sucesso!');
    }

    /** Muda o status de uma tarefa — assistente/dirigente/admin. */
    public function updateTaskStatus(Request $request, string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $data = $request->validate(['status' => ['required', 'in:pending,in_progress,completed']]);
        $task = Task::where('house_id', $house->id)->findOrFail($id);

        $update = ['status' => $data['status']];
        if ($data['status'] === 'completed') $update['completed_at'] = now();

        $task->update($update);

        return redirect()->route('my-house', ['tab' => 'tarefas'])
            ->with('success', 'Status da tarefa atualizado!');
    }

    /** Aprova uma tarefa concluída — dirigente/admin (adiciona pontos ao membro). */
    public function approveTask(string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $task = Task::where('house_id', $house->id)->findOrFail($id);

        if ($task->status !== 'completed') {
            return back()->with('error', 'Somente tarefas concluídas podem ser aprovadas.');
        }

        $task->update(['status' => 'approved', 'approved_at' => now()]);

        if ($task->assigned_to && $task->points > 0) {
            $task->assignedTo?->addPoints($task->points);
        }

        return redirect()->route('my-house', ['tab' => 'tarefas'])
            ->with('success', 'Tarefa aprovada! Pontos atribuídos ao membro.');
    }

    /** Rejeita uma tarefa concluída — devolve para in_progress. */
    public function rejectTask(string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $task = Task::where('house_id', $house->id)->findOrFail($id);

        if ($task->status !== 'completed') {
            return back()->with('error', 'Apenas tarefas concluídas podem ser rejeitadas.');
        }

        $task->update([
            'status'       => 'in_progress',
            'completed_at' => null,
        ]);

        return redirect()->route('my-house', ['tab' => 'tarefas'])
            ->with('success', 'Tarefa devolvida ao membro para revisão.');
    }

    /** Atribui uma tarefa a um membro da casa. */
    public function assignTask(Request $request, string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $data = $request->validate(['assigned_to' => ['required', 'exists:users,id']]);
        Task::where('house_id', $house->id)->findOrFail($id)->update(['assigned_to' => $data['assigned_to']]);

        return redirect()->route('my-house', ['tab' => 'tarefas'])
            ->with('success', 'Tarefa atribuída!');
    }

    /** Distribui aleatoriamente as tarefas pendentes entre os membros ativos. */
    public function randomizeTasks(): RedirectResponse
    {
        $house   = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $members = $house->activeMembers()->pluck('users.id')->toArray();
        if (empty($members)) return back()->with('error', 'Nenhum membro ativo para atribuir.');

        $tasks = Task::where('house_id', $house->id)
            ->where('status', 'pending')
            ->whereNull('assigned_to')
            ->get();

        foreach ($tasks as $i => $task) {
            $task->update(['assigned_to' => $members[$i % count($members)]]);
        }

        return redirect()->route('my-house', ['tab' => 'tarefas'])
            ->with('success', "{$tasks->count()} tarefa(s) distribuída(s) aleatoriamente!");
    }

    /** Cancela um evento da casa — dirigente/admin. */
    public function cancelEvent(string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $event = Event::where('house_id', $house->id)->findOrFail($id);
        $event->update(['status' => 'cancelled']);

        return redirect()->route('my-house', ['tab' => 'eventos'])
            ->with('success', 'Evento cancelado.');
    }

    /** Atualiza dados de um evento da casa. */
    public function updateEvent(Request $request, string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $event = Event::where('house_id', $house->id)->findOrFail($id);

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'starts_at'       => ['required', 'date'],
            'ends_at'         => ['nullable', 'date'],
            'price'           => ['nullable', 'numeric', 'min:0'],
            'capacity'        => ['nullable', 'integer', 'min:1'],
            'address'         => ['nullable', 'string', 'max:500'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'rules'           => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'visibility'      => ['nullable', 'in:public,members_only'],
            'banner_image'    => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('events/banners', 'public');
        } else {
            unset($data['banner_image']);
        }

        $event->update($data);

        return redirect()->route('my-house', ['tab' => 'eventos'])->with('success', 'Evento atualizado!');
    }

    /** Atualiza dados de uma tarefa da casa. */
    public function updateTask(Request $request, string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $task = Task::where('house_id', $house->id)->findOrFail($id);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'due_date'    => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'points'      => ['nullable', 'integer', 'min:0'],
        ]);

        $task->update([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date'    => $data['due_date'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
            'points'      => $data['points'] ?? $task->points,
        ]);

        return redirect()->route('my-house', ['tab' => 'tarefas'])->with('success', 'Tarefa atualizada!');
    }

    /** Atualiza um lançamento financeiro da casa. */
    public function updateFinance(Request $request, string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $finance = HouseFinance::where('house_id', $house->id)->findOrFail($id);

        $data = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'amount'   => ['required', 'numeric', 'min:0.01'],
            'status'   => ['nullable', 'in:pending,paid,overdue'],
            'due_date' => ['nullable', 'date'],
        ]);

        $finance->update($data);

        return redirect()->route('my-house', ['tab' => 'financeiro'])->with('success', 'Lançamento atualizado!');
    }

    /** Marca/desmarca pagamento de um membro em um lançamento. */
    public function toggleMemberPayment(Request $request, string $financeId, string $userId): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        // Valida que a finance pertence à casa antes de alterar o pagamento
        $finance = HouseFinance::where('house_id', $house->id)->findOrFail($financeId);

        $entry = HouseFinanceMember::where('finance_id', $finance->id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $entry->update([
            'status'  => $entry->status === 'paid' ? 'pending' : 'paid',
            'paid_at' => $entry->status === 'paid' ? null : now(),
        ]);

        return back()->with('success', 'Status atualizado!');
    }

    /** Cria ou atualiza um material de estudo para a casa — assistente/dirigente/admin. */
    public function storeStudy(Request $request): RedirectResponse
    {
        $user  = Auth::user();
        $house = $this->getUserHouse($user);

        if (! $house) {
            return back()->with('error', 'Você não está associado a nenhuma casa.');
        }

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
            'content_type' => ['required', 'in:text,video,audio,pdf'],
            'content_url'  => ['nullable', 'url', 'max:500'],
            'content_body' => ['nullable', 'string'],
            'content_file' => [
                $request->input('content_type') === 'pdf' ? 'required' : 'nullable',
                'file', 'mimes:pdf', 'max:20480',
            ],
            'category'     => ['nullable', 'string', 'max:100'],
            'points'       => ['nullable', 'integer', 'min:0'],
            'is_public'    => ['nullable', 'boolean'],
            'published'    => ['nullable', 'boolean'],
        ]);

        $pdfPath = null;
        if ($data['content_type'] === 'pdf' && $request->hasFile('content_file')) {
            $pdfPath = $request->file('content_file')->store('studies/pdfs', 'public');
        }

        Study::create([
            'house_id'     => $house->id,
            'created_by'   => $user->id,
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'content_type' => $data['content_type'],
            'content_url'  => $data['content_url'] ?? null,
            'content_body' => $data['content_body'] ?? null,
            'content_file' => $pdfPath,
            'category'     => $data['category'] ?? null,
            'points'       => $data['points'] ?? 20,
            'is_public'    => $request->boolean('is_public'),
            'published'    => $request->boolean('published'),
            'slug'         => \Illuminate\Support\Str::slug($data['title']) . '-' . uniqid(),
        ]);

        return redirect()->route('my-house', ['tab' => 'estudos'])
            ->with('success', 'Material de estudo criado com sucesso!');
    }

    /** Atualiza um material de estudo da casa. */
    public function updateStudy(Request $request, string $id): RedirectResponse
    {
        $house = $this->getUserHouse(Auth::user());
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $study = Study::where('house_id', $house->id)->findOrFail($id);

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
            'content_type' => ['required', 'in:text,video,audio,pdf'],
            'content_url'  => ['nullable', 'url', 'max:500'],
            'content_body' => ['nullable', 'string'],
            'content_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'category'     => ['nullable', 'string', 'max:100'],
            'points'       => ['nullable', 'integer', 'min:0'],
            'is_public'    => ['nullable', 'boolean'],
            'published'    => ['nullable', 'boolean'],
        ]);

        $pdfPath = $study->content_file;
        if ($data['content_type'] === 'pdf' && $request->hasFile('content_file')) {
            $pdfPath = $request->file('content_file')->store('studies/pdfs', 'public');
        } elseif ($data['content_type'] !== 'pdf') {
            $pdfPath = null;
        }

        $study->update([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'content_type' => $data['content_type'],
            'content_url'  => $data['content_url'] ?? null,
            'content_body' => $data['content_body'] ?? null,
            'content_file' => $pdfPath,
            'category'     => $data['category'] ?? null,
            'points'       => $data['points'] ?? $study->points,
            'is_public'    => $request->boolean('is_public'),
            'published'    => $request->boolean('published'),
        ]);

        return redirect()->route('my-house', ['tab' => 'estudos'])
            ->with('success', 'Material atualizado!');
    }

    /** Registra uma sugestão do membro ao dirigente da casa. */
    public function storeSuggestion(Request $request): RedirectResponse
    {
        $user  = Auth::user();
        $house = $user->houses()->wherePivot('status', 'active')->first();

        if (! $house) {
            return back()->with('error', 'Você não está associado a nenhuma casa.');
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        HouseSuggestion::create([
            'house_id' => $house->id,
            'user_id'  => $user->id,
            'message'  => $data['message'],
        ]);

        return back()->with('success', 'Sugestão enviada ao dirigente!');
    }

    /** Envia notificação/mensagem da casa para um ou todos os membros. */
    public function sendHouseNotification(Request $request): RedirectResponse
    {
        $user  = Auth::user();
        $house = $this->getUserHouse($user);
        if (! $house) return back()->with('error', 'Sem casa associada.');

        $data = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'body'       => ['required', 'string', 'max:1000'],
            'target'     => ['required', 'in:all,individual'],
            'user_id'    => ['nullable', 'integer'],
        ]);

        DB::transaction(function () use ($house, $user, $data) {
            if ($data['target'] === 'individual' && ! empty($data['user_id'])) {
                \App\Models\Notification::create([
                    'user_id' => $data['user_id'],
                    'type'    => 'house_message',
                    'title'   => '[' . $house->name . '] ' . $data['title'],
                    'body'    => $data['body'],
                    'data'    => ['from_house_id' => $house->id, 'from_user' => $user->name],
                ]);
            } else {
                $memberIds = $house->activeMembers()->pluck('users.id');
                foreach ($memberIds as $memberId) {
                    \App\Models\Notification::create([
                        'user_id' => $memberId,
                        'type'    => 'house_message',
                        'title'   => '[' . $house->name . '] ' . $data['title'],
                        'body'    => $data['body'],
                        'data'    => ['from_house_id' => $house->id, 'from_user' => $user->name],
                    ]);
                }
            }
        });

        return redirect()->route('my-house', ['tab' => 'membros'])
            ->with('success', 'Mensagem enviada com sucesso!');
    }

    /** Retorna a casa principal do usuário (própria ou como dirigente/assistente). */
    private function getUserHouse($user)
    {
        $house = $user->ownedHouses()->first();

        if (! $house) {
            $house = $user->houses()
                ->wherePivotIn('role', ['dirigente', 'assistente'])
                ->first();
        }

        return $house;
    }
}
