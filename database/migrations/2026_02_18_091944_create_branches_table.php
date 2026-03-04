<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('branch_code');
            $table->string('branch_name');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('radius')->nullable()->comment('in meters');
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Soft FK — no ON DELETE CASCADE to preserve data when company deleted
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->unique(['company_id', 'branch_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
