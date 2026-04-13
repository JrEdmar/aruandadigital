<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HouseFinance extends Model
{
    protected $fillable = [
        'house_id', 'user_id', 'type', 'title', 'amount',
        'status', 'due_date', 'paid_at', 'notes', 'scope',
    ];

    protected $casts = [
        'amount'   => 'float',
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relacionamentos
    // -------------------------------------------------------------------------

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    /** Usuário que deve (devedor). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Registros individuais por membro (quando scope = members). */
    public function memberEntries(): HasMany
    {
        return $this->hasMany(HouseFinanceMember::class, 'finance_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }
}
