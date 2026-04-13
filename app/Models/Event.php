<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'house_id', 'created_by', 'name', 'slug',
        'description', 'rules', 'recommendations', 'banner_image',
        'starts_at', 'ends_at', 'price', 'capacity',
        'status', 'visibility', 'address', 'latitude', 'longitude',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'price'     => 'float',
        'capacity'  => 'integer',
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->name) . '-' . now()->timestamp;
            }
        });
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $cols = ['status', 'registered_at', 'checked_in_at'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('event_user', 'intent')) {
            $cols[] = 'intent';
        }

        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot($cols)
            ->withTimestamps();
    }

    public function getBannerImageUrlAttribute(): string
    {
        if ($this->banner_image) {
            return asset('storage/' . $this->banner_image);
        }

        return 'https://placehold.co/400x200/dcfce7/166534?text=' . urlencode($this->name ?? 'Evento');
    }

    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'open'      => 'Aberto',
            'full'      => 'Lotado',
            'cancelled' => 'Cancelado',
            'finished'  => 'Encerrado',
            default     => 'Rascunho',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'open'      => 'success',
            'full'      => 'warning',
            'cancelled' => 'danger',
            'finished'  => 'secondary',
            default     => 'secondary',
        };
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }
}
