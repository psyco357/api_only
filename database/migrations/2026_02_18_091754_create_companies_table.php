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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_code')->unique();
            $table->string('name_company');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->enum('subscription_plan', ['basic', 'pro', 'enterprise'])->default('basic');
            $table->date('subscription_end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
