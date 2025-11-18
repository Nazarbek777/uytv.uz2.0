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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Anonymous comments uchun nullable
            $table->string('name')->nullable(); // Anonymous uchun
            $table->string('email')->nullable(); // Anonymous uchun
            $table->text('comment');
            $table->integer('rating')->nullable(); // 1-5 yulduz
            $table->boolean('approved')->default(false); // Admin tomonidan tasdiqlash
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Reply uchun
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('property_id');
            $table->index('user_id');
            $table->index('approved');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
