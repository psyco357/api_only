<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeShift extends Model
{
    use HasUuids;

    protected $fillable = [
        'company_id',
        'branch_id',
        'employee_id',
        'shift_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class)->withDefault();
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isActive(): bool
    {
        $now = now()->toDateString();
        return $this->start_date->toDateString() <= $now
            && ($this->end_date === null || $this->end_date->toDateString() >= $now);
    }
}
