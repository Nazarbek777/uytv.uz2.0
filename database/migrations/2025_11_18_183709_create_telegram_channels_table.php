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
        Schema::create('telegram_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kanal nomi
            $table->string('username')->unique(); // @username yoki channel_id
            $table->string('chat_id')->nullable(); // Telegram chat ID
            $table->boolean('is_active')->default(true); // Faol yoki yo'q
            $table->text('description')->nullable(); // Tavsif
            $table->integer('scrape_limit')->default(50); // Bir marta nechta yig'ish
            $table->integer('scrape_days')->default(7); // Necha kunga qadar eski postlarni olish
            $table->timestamp('last_scraped_at')->nullable(); // Oxirgi marta qachon yig'ilgan
            $table->integer('total_scraped')->default(0); // Jami nechta yig'ilgan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_channels');
    }
};
