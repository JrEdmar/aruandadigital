<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class House extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'cnpj', 'type',
        'description', 'spiritual_line', 'history', 'differentials',
        'cover_image', 'logo_image',
        'email', 'phone', 'website', 'whatsapp',
        'facebook', 'instagram', 'youtube',
        'foundation_date', 'capacity', 'schedule',
        'zip_code', 'street', 'number', 'complement',
        'neighborhood', 'city', 'state',
        'latitude', 'longitude',
        'status', 'rejection_reason', 'approved_at',
    ];

    protected $casts = [
        'foundation_date' => 'date',
        'approved_at'     => 'datetime',
        'latitude'        => 'float',
        'longitude'       => 'float',
        'capacity'        => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Boot
    // -------------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (House $house) {
            if (empty($house->slug)) {
                $house->slug = Str::slug($house->name);
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relacionamentos
    // -------------------------------------------------------------------------

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'house_user')
            ->withPivot(['role', 'role_membro', 'entities', 'status', 'joined_at', 'message', 'cancelled_at', 'house_points', 'house_level'])
            ->withTimestamps();
    }

    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('status', 'active');
    }

    public function studies(): HasMany
    {
        return $this->hasMany(Study::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function upcomingEvents(): HasMany
    {
        return $this->hasMany(Event::class)
            ->whereIn('status', ['open', 'full'])
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at');
    }

    public function pastEvents(): HasMany
    {
        return $this->hasMany(Event::class)
            ->where('status', 'finished')
            ->orderByDesc('starts_at');
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->number,
            $this->complement,
            $this->neighborhood,
            $this->city && $this->state ? "{$this->city} / {$this->state}" : $this->city,
        ]);

        return implode(', ', $parts);
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        return 'https://placehold.co/800x300/dcfce7/166534?text=' . urlencode($this->name ?? 'Casa');
    }

    public function getLogoImageUrlAttribute(): string
    {
        if ($this->logo_image) {
            return asset('storage/' . $this->logo_image);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'A') . '&background=dcfce7&color=166534&size=96';
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'umbanda'   => 'Umbanda',
            'candomble' => 'Candomblé',
            'misto'     => 'Misto',
            default     => 'Outro',
        };
    }

    public function getMapsUrlAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }

        $address = urlencode($this->full_address);
        return "https://www.google.com/maps/search/?api=1&query={$address}";
    }

    // -------------------------------------------------------------------------
    // Escopos
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNearby($query, float $lat, float $lng, float $radius = 50)
    {
        // Usa subquery para compatibilidade com PostgreSQL (aliases não são permitidos em HAVING)
        $distanceExpr = "(6371 * acos(
            cos(radians(?)) * cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(latitude))
        ))";

        return $query
            ->selectRaw("*, {$distanceExpr} AS distance", [$lat, $lng, $lat])
            ->whereRaw("{$distanceExpr} <= ?", [$lat, $lng, $lat, $radius])
            ->orderByRaw("{$distanceExpr}", [$lat, $lng, $lat]);
    }
}
