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
            $table->json('additional_phones')->nullable()->after('phone');
            $table->string('whatsapp_number')->nullable()->after('additional_phones');
            $table->string('telegram_username')->nullable()->after('whatsapp_number');
            $table->string('secondary_email')->nullable()->after('email');
            $table->string('district')->nullable()->after('city');
            $table->string('latitude', 30)->nullable()->after('country');
            $table->string('longitude', 30)->nullable()->after('latitude');
            $table->boolean('is_profile_public')->default(true)->after('featured');
            $table->json('notification_preferences')->nullable()->after('onboarding_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'additional_phones',
                'whatsapp_number',
                'telegram_username',
                'secondary_email',
                'district',
                'latitude',
                'longitude',
                'is_profile_public',
                'notification_preferences',
            ]);
        });
    }
};
