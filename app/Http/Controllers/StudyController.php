<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Study;
use App\Models\StudyProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudyController extends Controller
{
    /** Lista estudos públicos — acessível a qualquer usuário autenticado (inclusive visitante). */
    public function publicIndex(): View
    {
        $studies = Study::public()
            ->with('house')
            ->ordered()
            ->paginate(16);

        return view('studies.public', compact('studies'));
    }

    /** Lista os estudos das casas em que o usuário é membro ativo. */
    public function index(): View|RedirectResponse
    {
        $user     = Auth::user();
        $houseIds = $user->activeHouseIds();

        if ($houseIds->isEmpty()) {
            return redirect()->route('houses')
                ->with('error', 'Você precisa ser membro ativo de uma casa para acessar os estudos.');
        }

        $studies = Study::published()
            ->with('house')
            ->whereIn('house_id', $houseIds)
            ->ordered()
            ->paginate(12);

        return view('studies.index', compact('studies'));
    }

    /** Exibe um estudo — membros ativos da casa ou estudos públicos para qualquer usuário. */
    public function show(string $id): View|RedirectResponse
    {
        $user  = Auth::user();
        $study = Study::with('house')->published()->findOrFail($id);

        $isMember = $user->activeHouseIds()->contains($study->house_id);

        if (! $isMember && ! $study->is_public) {
            return redirect()->route('studies.public')
                ->with('error', 'Este estudo é exclusivo para membros da casa.');
        }

        $progress = StudyProgress::where('user_id', $user->id)
            ->where('study_id', $study->id)
            ->first();

        return view('studies.show', compact('study', 'progress'));
    }

    /** Formulário de criação — dirigente (cria para sua casa) e admin. */
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasRole('dirigente,assistente')) {
            $house = $user->activeHouse();
            if (! $house) {
                return redirect()->route('my-house')
                    ->with('error', 'Você não está associado a nenhuma casa.');
            }
            return view('studies.create', compact('house'));
        }

        // admin pode criar para qualquer casa
        $houses = House::where('status', 'active')->orderBy('name')->get();
        return view('studies.create', compact('houses'));
    }

    /** Salva novo estudo — dirigente/assistente (própria casa) e admin. */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $contentType = $request->input('content_type');
        $data = $request->validate([
            'house_id'     => ['required', 'exists:houses,id'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
            'content_type' => ['required', 'in:text,video,audio,pdf'],
            'content_body' => [$contentType === 'text' ? 'required' : 'nullable', 'string'],
            'content_url'  => ['nullable', 'url'],
            'category'     => ['nullable', 'string', 'max:100'],
            'points'       => ['nullable', 'integer', 'min:0'],
            'published'    => ['nullable', 'boolean'],
        ]);

        // Dirigente/assistente só pode criar para sua própria casa
        if ($user->hasRole('dirigente,assistente')) {
            $houseId = $user->activeHouse()?->id;
            if (! $houseId || $data['house_id'] != $houseId) {
                return back()->with('error', 'Você só pode criar estudos para sua própria casa.');
            }
        }

        Study::create([
            ...$data,
            'created_by' => Auth::id(),
            'published'  => $request->boolean('published'),
            'slug'       => \Illuminate\Support\Str::slug($data['title']) . '-' . uniqid(),
        ]);

        return redirect()->route('studies')->with('success', 'Estudo criado com sucesso!');
    }

    public function edit(string $id): \Illuminate\View\View
    {
        $user  = Auth::user();
        $study = Study::findOrFail($id);

        if (! $user->hasRole('admin,dirigente,assistente')) {
            abort(403);
        }

        // Dirigente/assistente can only edit studies of their own house
        if (! $user->isAdmin()) {
            $houseIds = $user->activeHouseIds();
            if (! $houseIds->contains($study->house_id)) {
                abort(403);
            }
        }

        $houses = $user->isAdmin() ? \App\Models\House::active()->orderBy('name')->get() : $user->houses()->wherePivot('status', 'active')->get();

        return view('studies.edit', compact('study', 'houses'));
    }

    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $user  = Auth::user();
        $study = Study::findOrFail($id);

        if (! $user->hasRole('admin,dirigente,assistente')) {
            abort(403);
        }

        // Dirigente/assistente só pode editar estudos da própria casa
        if (! $user->isAdmin()) {
            $houseIds = $user->activeHouseIds();
            if (! $houseIds->contains($study->house_id)) {
                abort(403, 'Você não tem permissão para editar este estudo.');
            }
        }

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
            'content_type' => ['required', 'in:text,video,audio,pdf'],
            'content_url'  => ['nullable', 'url', 'max:500'],
            'content_body' => ['nullable', 'string'],
            'category'     => ['nullable', 'string', 'max:100'],
            'points'       => ['nullable', 'integer', 'min:0'],
            'published'    => ['nullable', 'boolean'],
        ]);

        $study->update([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'content_type' => $data['content_type'],
            'content_url'  => $data['content_url'] ?? null,
            'content_body' => $data['content_body'] ?? null,
            'category'     => $data['category'] ?? null,
            'points'       => $data['points'] ?? 0,
            'published'    => isset($data['published']),
        ]);

        return redirect()->route('studies.show', $study->id)->with('success', 'Estudo atualizado!');
    }

    /** Marca estudo como concluído e adiciona pontos na gamificação da casa. */
    public function complete(string $id): JsonResponse
    {
        $user  = Auth::user();
        $study = Study::published()->findOrFail($id);

        if (! $user->activeHouseIds()->contains($study->house_id)) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $progress = StudyProgress::firstOrNew([
            'user_id'  => $user->id,
            'study_id' => $study->id,
        ]);

        if ($progress->completed_at) {
            return response()->json(['message' => 'Você já concluiu este estudo.'], 422);
        }

        $progress->progress_percent = 100;
        $progress->completed_at     = now();
        $progress->save();

        // Pontos na gamificação da casa (não global)
        if ($study->points > 0 && $study->house_id) {
            $user->addHousePoints($study->house_id, $study->points);
        }

        $housePoints = $user->houses()
            ->wherePivot('house_id', $study->house_id)
            ->first()?->pivot->house_points ?? 0;

        return response()->json([
            'message'      => "Estudo concluído! +{$study->points} pontos na casa.",
            'house_points' => $housePoints,
        ]);
    }
}
