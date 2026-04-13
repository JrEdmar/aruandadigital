<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// House imported via App\Models namespace
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Study extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'house_id', 'created_by', 'title', 'slug', 'description',
        'content_type', 'content_url', 'content_body', 'content_file',
        'thumbnail', 'category', 'points', 'published', 'is_public',
    ];

    protected $casts = [
        'published'    => 'boolean',
        'is_public'    => 'boolean',
        'points'       => 'integer',
        'order_column' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Boot — gera slug automaticamente
    // -------------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Study $study) {
            if (empty($study->slug)) {
                $study->slug = Str::slug($study->title) . '-' . uniqid();
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relacionamentos
    // -------------------------------------------------------------------------

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studyProgress(): HasMany
    {
        return $this->hasMany(StudyProgress::class);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /** URL da thumbnail ou placeholder padrão. */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        return asset('images/study-default.jpg');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopePublic($query)
    {
        return $query->where('published', true)->where('is_public', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_column')->orderBy('created_at');
    }
}
