<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('branch_id')->nullable();
            $table->time('work_start_at');
            $table->time('work_end_at');
            $table->integer('late_tolerance_minutes')->default(0);
            $table->integer('early_checkin_minutes')->default(30);
            $table->integer('late_cutoff_minutes')->default(0);
            $table->boolean('allow_weekend')->default(false);
            $table->boolean('require_face_recognition')->default(false);
            $table->boolean('require_geolocation')->default(true);
            $table->boolean('auto_checkout')->default(false);
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onDelete('set null');
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('branch_id');
            $table->string('shift_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_overnight')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onDelete('restrict');
        });

        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('branch_id');
            $table->uuid('employee_id');
            $table->uuid('shift_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onDelete('restrict');

            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade');

            // Keep shift_id but allow set null if shift deleted (historical record preserved)
            $table->foreign('shift_id')
                ->references('id')->on('shifts')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_shifts');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('attendance_settings');
    }
};
