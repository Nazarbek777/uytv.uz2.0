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
        Schema::create('ai_search_logs', function (Blueprint $table) {
            $table->id();
            $table->text('query'); // Qidiruv so'rovi
            $table->string('locale')->default('uz'); // Til
            $table->json('ai_parsed_filters')->nullable(); // AI tomonidan tahlil qilingan filtrlash
            $table->integer('results_count')->default(0); // Topilgan natijalar soni
            $table->json('properties_found')->nullable(); // Topilgan uy-joylar ID'lari
            $table->integer('response_time_ms')->nullable(); // Javob vaqti
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index('created_at');
            $table->index('locale');
            $table->fullText('query'); // Full-text search uchun
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_search_logs');
    }
};
