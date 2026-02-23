<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Traits\BelongsToCompany;

class Role extends Model
{
    use HasUuids, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'slug',
        'name',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->withPivot('company_id')
            ->withTimestamps();
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function givePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        $this->permissions()->syncWithoutDetaching($permission);
    }

    public function revokePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        $this->permissions()->detach($permission);
    }
}
