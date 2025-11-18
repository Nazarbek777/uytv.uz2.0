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
            $table->string('source')->nullable()->after('favorites_count')->comment('Ma\'lumot manbasi (olx, uybor, exarid, etc)');
            $table->string('source_id')->nullable()->after('source')->comment('Manba saytdagi ID');
            $table->string('source_url')->nullable()->after('source_id')->comment('Manba saytdagi URL');
            
            $table->index(['source', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['source', 'source_id']);
            $table->dropColumn(['source', 'source_id', 'source_url']);
        });
    }
};
