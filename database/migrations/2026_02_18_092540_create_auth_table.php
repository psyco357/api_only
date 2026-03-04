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
        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('company_id');
            $table->string('token_hash', 255);
            $table->timestamp('expired_at');
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade'); // tokens are worthless without user

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');

            $table->index(['user_id', 'expired_at']);
            $table->index(['user_id', 'revoked_at']);
        });

        Schema::create('user_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('company_id');
            $table->uuid('refresh_token_id');
            $table->string('device_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');

            $table->foreign('refresh_token_id')
                ->references('id')->on('refresh_tokens')
                ->onDelete('cascade');

            $table->index(['user_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
        Schema::dropIfExists('refresh_tokens');
    }
};
