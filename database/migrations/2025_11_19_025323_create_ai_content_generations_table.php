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
        Schema::create('ai_content_generations', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // Property, Development, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('content_type'); // 'description', 'title', 'meta_description', etc.
            $table->string('locale')->default('uz');
            $table->text('prompt'); // AI'ga yuborilgan prompt
            $table->text('generated_content'); // AI tomonidan yaratilgan kontent
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_used')->default(false); // Ishlatilgan yoki yo'q
            $table->integer('tokens_used')->nullable(); // Ishlatilgan tokenlar soni
            $table->decimal('cost', 10, 6)->nullable(); // Xarajat
            $table->timestamps();
            
            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_content_generations');
    }
};
