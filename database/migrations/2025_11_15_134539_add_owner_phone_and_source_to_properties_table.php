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
        Schema::table('properties', function (Blueprint $table) {
            // Egasining telefon raqami
            if (!Schema::hasColumn('properties', 'owner_phone')) {
                $table->string('owner_phone', 20)->nullable()->after('user_id');
                $table->index('owner_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'owner_phone')) {
                $table->dropIndex(['owner_phone']);
                $table->dropColumn('owner_phone');
            }
        });
    }
};
