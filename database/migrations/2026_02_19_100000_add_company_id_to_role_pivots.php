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
        Schema::table('permission_role', function (Blueprint $table) {
            if (!Schema::hasColumn('permission_role', 'company_id')) {
                $table->uuid('company_id')->nullable()->index()->after('permission_id');
                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('cascade');
            }
        });

        Schema::table('role_user', function (Blueprint $table) {
            if (!Schema::hasColumn('role_user', 'company_id')) {
                $table->uuid('company_id')->nullable()->index()->after('role_id');
                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_role', function (Blueprint $table) {
            if (Schema::hasColumn('permission_role', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
        });

        Schema::table('role_user', function (Blueprint $table) {
            if (Schema::hasColumn('role_user', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
        });
    }
};
