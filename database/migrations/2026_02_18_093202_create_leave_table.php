<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('name');
            $table->string('code');
            $table->integer('default_quota')->default(0);
            $table->boolean('is_paid')->default(true);
            $table->boolean('is_auto_approve')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->unique(['company_id', 'code']);
        });

        Schema::create('leave_balances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('employee_id');
            $table->uuid('leave_type_id');
            $table->integer('quota');
            $table->integer('used')->default(0);
            // $table->integer('remaining')->nullable();
            $table->year('period_year');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();

            // UNIQUE PER YEAR
            $table->unique([
                'company_id',
                'employee_id',
                'leave_type_id',
                'period_year'
            ]);
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('employee_id');
            $table->uuid('leave_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('restrict');

            $table->foreign('leave_type_id')
                ->references('id')->on('leave_types')
                ->onDelete('restrict');
        });

        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('leave_request_id');
            $table->uuid('approver_id');
            $table->tinyInteger('level')->default(1)->comment('Approval level 1, 2, 3');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->foreign('leave_request_id')
                ->references('id')->on('leave_requests')
                ->onDelete('cascade');

            // Keep record even if approver is deleted
            $table->foreign('approver_id')
                ->references('id')->on('employees')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leave_types');
    }
};
