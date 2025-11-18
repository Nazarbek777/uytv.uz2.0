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
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable'); // property_id, seoable_type
            $table->string('locale', 5)->default('uz'); // uz, ru, en
            
            // Meta tags
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_robots')->default('index,follow');
            
            // Open Graph
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            $table->string('og_url')->nullable();
            
            // Twitter Card
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            
            // Canonical URL
            $table->string('canonical_url')->nullable();
            
            // Structured Data (JSON-LD)
            $table->json('structured_data')->nullable();
            
            // Hreflang (alternate languages)
            $table->json('hreflang')->nullable(); // {"uz": "url", "ru": "url", "en": "url"}
            
            $table->timestamps();
            
            // Indexes
            // Note: morphs('seoable') already creates index for seoable_type and seoable_id
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
