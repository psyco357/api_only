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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Use SET NULL so users survive company soft/hard delete
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('restrict');

            $table->unique(['company_id', 'username']);
            $table->unique(['company_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
