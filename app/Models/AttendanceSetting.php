<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToCompany;

class AttendanceSetting extends Model
{
    use HasUuids, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'work_start_at',
        'work_end_at',
        'late_tolerance_minutes',
        'early_checkin_minutes',
        'late_cutoff_minutes',
        'allow_weekend',
        'require_face_recognition',
        'require_geolocation',
        'auto_checkout',
    ];

    protected $casts = [
        'late_tolerance_minutes'   => 'integer',
        'early_checkin_minutes'    => 'integer',
        'late_cutoff_minutes'      => 'integer',
        'allow_weekend'            => 'boolean',
        'require_face_recognition' => 'boolean',
        'require_geolocation'      => 'boolean',
        'auto_checkout'            => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class)->withDefault();
    }
}
