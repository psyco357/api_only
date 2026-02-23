<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Traits\BelongsToCompany;

class RefreshToken extends Model
{
    use HasUuids, BelongsToCompany;

    protected $fillable = [
        'user_id',
        'company_id',
        'token_hash',
        'expired_at',
        'revoked_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'revoked_at' => 'datetime',
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

    public function session(): HasOne
    {
        return $this->hasOne(UserSession::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isRevoked();
    }

    public function revoke(): bool
    {
        return $this->update(['revoked_at' => now()]);
    }
}
