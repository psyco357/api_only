<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApproval extends Model
{
    use HasUuids;

    protected $fillable = [
        'company_id',
        'leave_request_id',
        'approver_id',
        'level',
        'status',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'level'       => 'integer',
        'approved_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approver_id');
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function approve(string $notes = null): bool
    {
        return $this->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'notes'       => $notes,
        ]);
    }

    public function reject(string $notes = null): bool
    {
        return $this->update([
            'status'      => 'rejected',
            'approved_at' => now(),
            'notes'       => $notes,
        ]);
    }
}
