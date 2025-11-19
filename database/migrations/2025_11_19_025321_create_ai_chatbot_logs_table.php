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
        Schema::create('ai_chatbot_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // Session ID
            $table->string('locale')->default('uz'); // Til
            $table->text('user_message'); // Foydalanuvchi xabari
            $table->text('ai_response'); // AI javobi
            $table->json('properties_suggested')->nullable(); // Taklif qilingan uy-joylar
            $table->integer('response_time_ms')->nullable(); // Javob vaqti (millisekund)
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('success')->default(true); // Muvaffaqiyatli yoki yo'q
            $table->text('error_message')->nullable(); // Xato xabari
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('created_at');
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chatbot_logs');
    }
};
