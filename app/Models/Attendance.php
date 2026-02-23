<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Traits\BelongsToCompany;

class Attendance extends Model
{
    use HasUuids, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'employee_id',
        'branch_id',
        'shift_id',
        'work_date',
        'check_in',
        'check_out',
        'check_in_lat',
        'check_in_lng',
        'check_out_lat',
        'check_out_lng',
        'check_in_photo',
        'check_out_photo',
        'late_minutes',
        'working_minutes',
        'status',
        'source',
    ];

    protected $casts = [
        'work_date'      => 'date',
        'check_in'       => 'datetime',
        'check_out'      => 'datetime',
        'check_in_lat'   => 'decimal:7',
        'check_in_lng'   => 'decimal:7',
        'check_out_lat'  => 'decimal:7',
        'check_out_lng'  => 'decimal:7',
        'late_minutes'   => 'integer',
        'working_minutes' => 'integer',
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class)->withDefault();
    }

    public function faceLog(): HasOne
    {
        return $this->hasOne(FaceLog::class);
    }

    public function faceLogs(): HasMany
    {
        return $this->hasMany(FaceLog::class);
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopePresent($query)
    {
        return $query->whereIn('status', ['present', 'late']);
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('work_date', $date);
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('work_date', $year)->whereMonth('work_date', $month);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isCheckedOut(): bool
    {
        return $this->check_out !== null;
    }

    public function getWorkingHours(): float
    {
        return round($this->working_minutes / 60, 2);
    }
}
