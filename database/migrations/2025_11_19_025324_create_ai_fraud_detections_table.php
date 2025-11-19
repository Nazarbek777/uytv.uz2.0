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
        Schema::create('ai_fraud_detections', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // Property, Development, etc.
            $table->unsignedBigInteger('model_id');
            $table->decimal('fraud_score', 5, 2)->default(0); // 0-100 orasida yolg'onlik ehtimoli
            $table->json('detected_issues')->nullable(); // Aniqlangan muammolar
            $table->json('ai_analysis')->nullable(); // AI tahlili
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            
            $table->index(['model_type', 'model_id']);
            $table->index('fraud_score');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_fraud_detections');
    }
};
