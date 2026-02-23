<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Company\Controllers\CompanyController;

Route::prefix('v1')->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::put('/companies', [CompanyController::class, 'updateCompanyInfo'])->name('companies.update');
    Route::delete('/companies', [CompanyController::class, 'destroy'])->name('companies.destroy');
});
