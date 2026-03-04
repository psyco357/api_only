<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'company_id')) {
                $table->uuid('company_id')->nullable()->index()->after('id');
                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('permissions', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn('permissions', 'is_system')) {
                $table->dropColumn('is_system');
            }
        });
    }
};
