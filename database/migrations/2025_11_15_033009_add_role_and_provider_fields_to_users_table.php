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
        Schema::table('users', function (Blueprint $table) {
            // Role (user, provider, admin)
            $table->enum('role', ['user', 'provider', 'admin'])->default('user')->after('email');
            
            // Provider ma'lumotlari
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('company_name')->nullable()->after('bio');
            $table->string('company_logo')->nullable()->after('company_name');
            $table->string('license_number')->nullable()->after('company_logo');
            $table->string('website')->nullable()->after('license_number');
            $table->json('social_links')->nullable()->after('website'); // {facebook, instagram, twitter, etc}
            $table->string('address')->nullable()->after('social_links');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->integer('properties_count')->default(0)->after('country');
            $table->decimal('rating', 3, 2)->default(0)->after('properties_count');
            $table->integer('reviews_count')->default(0)->after('rating');
            $table->boolean('verified')->default(false)->after('reviews_count');
            $table->boolean('featured')->default(false)->after('verified');
            
            // Indexes
            $table->index('role');
            $table->index('verified');
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'avatar',
                'bio',
                'company_name',
                'company_logo',
                'license_number',
                'website',
                'social_links',
                'address',
                'city',
                'country',
                'properties_count',
                'rating',
                'reviews_count',
                'verified',
                'featured',
            ]);
        });
    }
};
