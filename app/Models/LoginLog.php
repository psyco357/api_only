<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginLog extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'user_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
