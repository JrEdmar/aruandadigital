<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseSuggestion extends Model
{
    protected $table = 'house_suggestions';

    protected $fillable = ['house_id', 'user_id', 'message', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
