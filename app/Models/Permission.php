<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Traits\BelongsToCompany;

class Permission extends Model
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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
            ->withPivot('company_id')
            ->withTimestamps();
    }
}
