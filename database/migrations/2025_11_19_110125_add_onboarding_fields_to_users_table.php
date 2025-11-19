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
            $table->string('onboarding_status')->default('not_started')->after('featured');
            $table->unsignedTinyInteger('onboarding_progress')->default(0)->after('onboarding_status');
            $table->json('onboarding_data')->nullable()->after('onboarding_progress');
            $table->json('provider_documents')->nullable()->after('onboarding_data');
            $table->text('verification_notes')->nullable()->after('provider_documents');
            $table->timestamp('verified_at')->nullable()->after('verification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'onboarding_status',
                'onboarding_progress',
                'onboarding_data',
                'provider_documents',
                'verification_notes',
                'verified_at',
            ]);
        });
    }
};
