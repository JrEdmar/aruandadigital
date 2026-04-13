<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseFinanceMember extends Model
{
    protected $table = 'house_finance_members';

    protected $fillable = [
        'finance_id', 'user_id', 'status', 'paid_at', 'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function finance(): BelongsTo
    {
        return $this->belongsTo(HouseFinance::class, 'finance_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
