<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // -------------------------------------------------------------------------
    // Fillable / Hidden / Casts
    // -------------------------------------------------------------------------

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'phone', 'cpf', 'birth_date', 'avatar',
        'google_id', 'facebook_id', 'lgpd_accepted_at',
        'points', 'level',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'birth_date'        => 'date',
            'lgpd_accepted_at'  => 'datetime',
            'points'            => 'integer',
            'level'             => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relacionamentos
    // -------------------------------------------------------------------------

    /** Casas das quais o usuário é membro. */
    public function houses(): BelongsToMany
    {
        return $this->belongsToMany(House::class, 'house_user')
            ->withPivot(['role', 'role_membro', 'status', 'joined_at', 'message', 'cancelled_at', 'house_points', 'house_level'])
            ->withTimestamps();
    }

    /** Casas que o usuário criou / é dono. */
    public function ownedHouses(): HasMany
    {
        return $this->hasMany(House::class, 'owner_id');
    }

    /** Tarefas atribuídas ao usuário. */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /** Notificações do usuário. */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /** Conquistas desbloqueadas. */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot(['earned_at'])
            ->withTimestamps();
    }

    /** Progresso nos estudos. */
    public function studyProgress(): HasMany
    {
        return $this->hasMany(StudyProgress::class);
    }

    /** Eventos nos quais o usuário está inscrito. */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->withPivot(['status', 'registered_at', 'checked_in_at'])
            ->withTimestamps();
    }

    /** Pedidos do usuário. */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /** URL do avatar ou placeholder com inicial do nome via ui-avatars. */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $initial = urlencode(mb_strtoupper(mb_substr($this->name, 0, 1)));
        return "https://ui-avatars.com/api/?name={$initial}&background=16A34A&color=fff&size=128";
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeModerador($query)
    {
        return $query->where('role', 'moderador');
    }

    public function scopeDirigente($query)
    {
        return $query->where('role', 'dirigente');
    }

    public function scopeAssistente($query)
    {
        return $query->where('role', 'assistente');
    }

    public function scopeMembro($query)
    {
        return $query->where('role', 'membro');
    }

    public function scopeVisitante($query)
    {
        return $query->where('role', 'visitante');
    }

    public function scopeLoja($query)
    {
        return $query->whereIn('role', ['loja', 'loja_master']);
    }

    // -------------------------------------------------------------------------
    // Helpers de role
    // -------------------------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerador(): bool
    {
        return $this->role === 'moderador';
    }

    public function isDirigente(): bool
    {
        return $this->role === 'dirigente';
    }

    public function isAssistente(): bool
    {
        return $this->role === 'assistente';
    }

    public function isMembro(): bool
    {
        return $this->role === 'membro';
    }

    public function isVisitante(): bool
    {
        return $this->role === 'visitante';
    }

    public function isLoja(): bool
    {
        return in_array($this->role, ['loja', 'loja_master']);
    }

    /**
     * Verifica se o usuário possui um dos roles informados (separados por vírgula).
     * Exemplo: $user->hasRole('admin,dirigente')
     */
    public function hasRole(string $role): bool
    {
        $roles = array_map('trim', explode(',', $role));
        return in_array($this->role, $roles);
    }

    /**
     * Retorna a primeira casa onde o usuário é membro ativo.
     */
    public function activeHouse(): ?House
    {
        return $this->houses()->wherePivot('status', 'active')->first();
    }

    /**
     * Retorna os IDs de todas as casas onde o usuário é membro ativo.
     */
    public function activeHouseIds(): \Illuminate\Support\Collection
    {
        return $this->houses()->wherePivot('status', 'active')->pluck('houses.id');
    }

    /**
     * Adiciona pontos ao usuário na gamificação da casa (house_user pivot).
     * Recalcula house_level (1 nível a cada 100 pontos na casa).
     */
    public function addHousePoints(int $houseId, int $points): void
    {
        $pivot = $this->houses()->wherePivot('house_id', $houseId)->first()?->pivot;
        if (! $pivot) return;

        $newPoints = $pivot->house_points + $points;
        $newLevel  = max(1, (int) floor($newPoints / 100) + 1);

        $this->houses()->updateExistingPivot($houseId, [
            'house_points' => $newPoints,
            'house_level'  => $newLevel,
        ]);
    }

    /**
     * Adiciona pontos globais ao usuário (legado — manter para compatibilidade).
     * Nível sobe a cada 100 pontos acumulados.
     */
    public function addPoints(int $points): void
    {
        $this->increment('points', $points);
        $newLevel = max(1, (int) floor($this->fresh()->points / 100) + 1);
        $this->update(['level' => $newLevel]);
    }
}
