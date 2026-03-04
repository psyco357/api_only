<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('branch_id');
            $table->uuid('department_id');
            $table->uuid('position_id');
            $table->uuid('manager_id')->nullable(); // self-reference

            $table->string('employee_number');
            $table->decimal('salary', 15, 2)->nullable();
            $table->date('hire_date');
            $table->enum('employment_status', ['permanent', 'contract', 'internship'])->default('contract');
            $table->date('termination_date')->nullable();
            $table->text('reason_termination')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('manager_id')->references('id')->on('employees')->nullOnDelete();

            $table->unique(['company_id', 'employee_number']);
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->string('full_name');
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('profile_picture')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade'); // profile goes with employee
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('employees');
    }
};
