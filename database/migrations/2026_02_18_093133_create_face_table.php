<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('employee_id');
            $table->json('face_embedding');
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade');
        });

        Schema::create('face_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('employee_id')->nullable();
            $table->uuid('attendance_id')->nullable();
            $table->decimal('similarity_score', 5, 4)->nullable();
            $table->enum('status', ['matched', 'not_matched'])->default('not_matched');
            $table->string('image_capture_path')->nullable();
            $table->string('device_info')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            // Nullable — log preserved even if employee deleted
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('set null');

            $table->foreign('attendance_id')
                ->references('id')->on('attendances')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_logs');
        Schema::dropIfExists('face_profiles');
    }
};
