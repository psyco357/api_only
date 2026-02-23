<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'full_name',
        'phone_number',
        'address',
        'gender',
        'birth_date',
        'profile_picture',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active'  => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function getAge(): ?int
    {
        return $this->birth_date?->age;
    }
}
