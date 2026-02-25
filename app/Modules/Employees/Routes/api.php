<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Employees\Controllers\EmployeeController;

Route::prefix('v1')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::put('/employees', [EmployeeController::class, 'updateEmployeeInfo'])->name('employees.update');
});
