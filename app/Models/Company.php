<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'company_code',
        'name_company',
        'email',
        'phone_number',
        'logo',
        'address',
        'subscription_plan',
        'subscription_end_date',
        'is_active',
    ];

    protected $casts = [
        'subscription_end_date' => 'date',
        'is_active'             => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function attendanceSettings(): HasMany
    {
        return $this->hasMany(AttendanceSetting::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveTypes(): HasMany
    {
        return $this->hasMany(LeaveType::class);
    }

    public function loginLogs(): HasMany
    {
        return $this->hasMany(LoginLog::class);
    }
}
