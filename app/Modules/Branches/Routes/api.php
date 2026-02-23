<?php

use App\Modules\Branches\Controllers\BranchesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/branches', [BranchesController::class, 'index'])->name('branches.index');
    Route::post('/branches', [BranchesController::class, 'store'])->name('branches.store');
    // Route::put('/branches/{branch}', [BranchesController::class, 'update'])->name('branches.update');
    // Route::delete('/branches/{branch}', [BranchesController::class, 'destroy'])->name('branches.destroy');
});
