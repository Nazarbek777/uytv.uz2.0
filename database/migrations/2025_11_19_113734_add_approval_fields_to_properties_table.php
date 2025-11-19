<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('approval_status')->default('draft')->after('status');
            $table->timestamp('approval_submitted_at')->nullable()->after('approval_status');
            $table->timestamp('approval_reviewed_at')->nullable()->after('approval_submitted_at');
            $table->foreignId('approval_reviewer_id')
                ->nullable()
                ->after('approval_reviewed_at')
                ->constrained('users')
                ->nullOnDelete();
            $table->text('approval_notes')->nullable()->after('approval_reviewer_id');
            $table->json('approval_history')->nullable()->after('approval_notes');
        });

        DB::table('properties')->update([
            'approval_status' => DB::raw("CASE 
                WHEN status = 'published' THEN 'approved'
                WHEN status = 'pending' THEN 'pending'
                WHEN status = 'rejected' THEN 'needs_changes'
                ELSE 'draft'
            END"),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['approval_reviewer_id']);
            $table->dropColumn([
                'approval_status',
                'approval_submitted_at',
                'approval_reviewed_at',
                'approval_reviewer_id',
                'approval_notes',
                'approval_history',
            ]);
        });
    }
};
