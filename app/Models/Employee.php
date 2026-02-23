<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Traits\BelongsToCompany;

class Employee extends Model
{
    use HasUuids, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'user_id',
        'branch_id',
        'department_id',
        'position_id',
        'manager_id',
        'employee_number',
        'salary',
        'hire_date',
        'employment_status',
        'termination_date',
        'reason_termination',
        'is_active',
    ];

    protected $casts = [
        'salary'           => 'decimal:2',
        'hire_date'        => 'date',
        'termination_date' => 'date',
        'is_active'        => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /** Direct manager (self-reference) */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id')->withDefault();
    }

    /** Direct subordinates (self-reference) */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /** All subordinates recursively (requires recursive CTE or eager loading chain) */
    public function allSubordinates(): HasMany
    {
        return $this->subordinates()->with('allSubordinates');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function faceProfile(): HasOne
    {
        return $this->hasOne(FaceProfile::class)->where('is_active', true)->latestOfMany();
    }

    public function faceProfiles(): HasMany
    {
        return $this->hasMany(FaceProfile::class);
    }

    public function faceLogs(): HasMany
    {
        return $this->hasMany(FaceLog::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveApprovals(): HasMany
    {
        return $this->hasMany(LeaveApproval::class, 'approver_id');
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isTerminated(): bool
    {
        return $this->termination_date !== null && $this->termination_date->isPast();
    }

    public function getLeaveBalance(string $leaveTypeId, int $year = null): ?LeaveBalance
    {
        return $this->leaveBalances()
            ->where('leave_type_id', $leaveTypeId)
            ->where('period_year', $year ?? now()->year)
            ->first();
    }
}
