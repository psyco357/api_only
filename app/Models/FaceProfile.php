<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceProfile extends Model
{
    use HasUuids;

    protected $fillable = [
        'company_id',
        'employee_id',
        'face_embedding',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'face_embedding' => 'array',
        'is_active'      => 'boolean',
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

    // ─── Helpers ──────────────────────────────────────────────

    /** Deactivate all other face profiles for this employee when activating this one */
    public function activate(): void
    {
        static::where('employee_id', $this->employee_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        $this->update(['is_active' => true]);
    }
}
