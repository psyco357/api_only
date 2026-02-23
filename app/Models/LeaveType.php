<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'default_quota',
        'is_paid',
        'is_auto_approve',
        'is_active',
    ];

    protected $casts = [
        'default_quota'   => 'integer',
        'is_paid'         => 'boolean',
        'is_auto_approve' => 'boolean',
        'is_active'       => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
