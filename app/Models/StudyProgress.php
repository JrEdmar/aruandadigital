<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyProgress extends Model
{
    protected $fillable = [
        'user_id', 'study_id', 'progress_percent', 'completed_at',
    ];

    protected $casts = [
        'progress_percent' => 'integer',
        'completed_at'     => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relacionamentos
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
