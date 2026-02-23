<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToCompany;

class Shift extends Model
{
    use HasUuids, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'shift_name',
        'start_time',
        'end_time',
        'is_overnight',
        'is_active',
    ];

    protected $casts = [
        'is_overnight' => 'boolean',
        'is_active'    => 'boolean',
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

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    /** Duration in minutes, accounting for overnight shifts */
    public function getDurationMinutes(): int
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);

        if ($this->is_overnight && $end->lt($start)) {
            $end->addDay();
        }

        return (int) $start->diffInMinutes($end);
    }
}
