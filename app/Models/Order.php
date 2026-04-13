<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'total', 'status', 'payment_method',
        'address', 'notes', 'paid_at',
    ];

    protected $casts = [
        'address' => 'array',
        'total'   => 'float',
        'paid_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relacionamentos
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /** Nome legível do status do pedido. */
    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Aguardando pagamento',
            'paid'      => 'Pago',
            'shipped'   => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            default     => 'Desconhecido',
        };
    }
}
