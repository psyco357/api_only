<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToCompany;

class Branch extends Model
{
    use HasUuids, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'branch_code',
        'branch_name',
        'latitude',
        'longitude',
        'radius',
        'address',
        'phone_number',
        'is_active',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'radius'    => 'integer',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function attendanceSettings(): HasMany
    {
        return $this->hasMany(AttendanceSetting::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
