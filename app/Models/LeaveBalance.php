<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToCompany;

class LeaveBalance extends Model
{
    use HasUuids, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'employee_id',
        'leave_type_id',
        'quota',
        'used',
        'period_year',
    ];

    protected $casts = [
        'quota'       => 'integer',
        'used'        => 'integer',
        'period_year' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class)->withTrashed();
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function getRemaining(): int
    {
        return max(0, $this->quota - $this->used);
    }

    public function hasEnough(int $days): bool
    {
        return $this->getRemaining() >= $days;
    }

    /**
     * Atomically increment used days.
     * Uses DB-level increment to prevent race conditions.
     */
    public function deduct(int $days): bool
    {
        if (! $this->hasEnough($days)) {
            return false;
        }

        $this->increment('used', $days);
        $this->refresh();

        return true;
    }

    /**
     * Restore days back to balance (e.g., on rejection/cancellation).
     */
    public function restore(int $days): void
    {
        $this->decrement('used', min($days, $this->used));
        $this->refresh();
    }
}
