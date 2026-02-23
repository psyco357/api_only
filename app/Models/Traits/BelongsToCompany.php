<?php

namespace App\Models\Traits;

use App\Models\Scopes\CompanyScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope());

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->company_id = Auth::user()->company_id;
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                if ($model->getOriginal('company_id')) {
                    $model->company_id = $model->getOriginal('company_id');
                } else {
                    $model->company_id = Auth::user()->company_id;
                }
            }
        });
    }
}
