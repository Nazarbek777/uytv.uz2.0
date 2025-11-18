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
            $table->integer('floor')->nullable()->after('floors')->comment('Apartment uchun qavat raqami');
            $table->string('construction_material')->nullable()->after('floor')->comment('Qurilish materiali: gisht, pishgan_gisht, beton, yogoch va h.k.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['floor', 'construction_material']);
        });
    }
};
