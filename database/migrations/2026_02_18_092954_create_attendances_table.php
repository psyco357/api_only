<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('employee_id');
            $table->uuid('branch_id');
            $table->uuid('shift_id');
            $table->date('work_date');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->decimal('check_in_lat', 10, 7)->nullable();
            $table->decimal('check_in_lng', 10, 7)->nullable();
            $table->decimal('check_out_lat', 10, 7)->nullable();
            $table->decimal('check_out_lng', 10, 7)->nullable();
            $table->string('check_in_photo')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->integer('late_minutes')->default(0);
            $table->integer('working_minutes')->default(0);
            $table->enum('status', ['present', 'late', 'absent', 'leave', 'sick']);
            $table->enum('source', ['web', 'mobile', 'device']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();

            // PERFORMANCE INDEX
            $table->index(['company_id', 'work_date']);
            $table->index(['company_id', 'employee_id']);

            // ANTI DOUBLE ABSEN
            $table->unique(['company_id', 'employee_id', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
