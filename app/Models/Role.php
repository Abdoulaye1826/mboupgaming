<?php

namespace App\Models;

use App\Enums\RoleSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Rôle utilisateur (Administrateur, Gestionnaire, Caissier, Livreur).
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
        'slug' => RoleSlug::class,
    ];

    // ─── Relations ───────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ─── Scopes ──────────────────────────────────────────────

    public function scopeBySlug($query, RoleSlug|string $slug)
    {
        $value = $slug instanceof RoleSlug ? $slug->value : $slug;

        return $query->where('slug', $value);
    }
}
