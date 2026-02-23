<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceLog extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'employee_id',
        'attendance_id',
        'similarity_score',
        'status',
        'image_capture_path',
        'device_info',
        'created_at',
    ];

    protected $casts = [
        'similarity_score' => 'decimal:4',
        'created_at'       => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class)->withDefault();
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class)->withDefault();
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isMatched(): bool
    {
        return $this->status === 'matched';
    }
}
