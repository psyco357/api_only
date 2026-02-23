<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToCompany;

class UserSession extends Model
{
    use HasUuids, BelongsToCompany;

    protected $fillable = [
        'user_id',
        'company_id',
        'refresh_token_id',
        'device_name',
        'ip_address',
        'user_agent',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function refreshToken(): BelongsTo
    {
        return $this->belongsTo(RefreshToken::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function updateActivity(): bool
    {
        return $this->update(['last_activity_at' => now()]);
    }
}
