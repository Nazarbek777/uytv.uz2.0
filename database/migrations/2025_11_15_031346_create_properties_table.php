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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Provider/Agent
            $table->string('slug')->unique();
            
            // Asosiy ma'lumotlar (tildan mustaqil)
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('area', 10, 2)->nullable(); // maydon
            $table->string('area_unit', 10)->default('m²'); // m², sqft
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('garages')->nullable();
            $table->integer('floors')->nullable();
            $table->year('year_built')->nullable();
            
            // Property type
            $table->enum('property_type', ['house', 'apartment', 'villa', 'land', 'commercial', 'office'])->default('house');
            $table->enum('listing_type', ['sale', 'rent'])->default('sale');
            
            // Location (coordinates)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            
            // Media
            $table->json('images')->nullable(); // Array of image paths
            $table->string('featured_image')->nullable();
            $table->json('videos')->nullable(); // Array of video URLs
            
            // Status
            $table->enum('status', ['draft', 'published', 'sold', 'rented'])->default('draft');
            $table->boolean('featured')->default(false);
            $table->boolean('verified')->default(false);
            
            // Views & Stats
            $table->integer('views')->default(0);
            $table->integer('favorites_count')->default(0);
            
            // SEO slug
            $table->string('seo_slug_uz')->nullable();
            $table->string('seo_slug_ru')->nullable();
            $table->string('seo_slug_en')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('property_type');
            $table->index('listing_type');
            $table->index('featured');
            $table->index('price');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
