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
        // 1. User Devices
        Schema::create('user_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('device_uuid');
            $table->string('device_model')->nullable();
            $table->string('last_ip')->nullable();
            $table->timestamp('last_active_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'device_uuid']);
        });

        // 2. Year Access Logs (Free model year tracking)
        Schema::create('year_access_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('subject_id');
            $table->integer('year');
            $table->timestamp('accessed_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->unique(['user_id', 'subject_id', 'year']);
        });

        // 3. Subscriptions (Premium control)
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('plan_type'); // tier1_99, tier2_149
            $table->uuid('locked_level_id')->nullable();
            $table->uuid('locked_stream_id')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('active'); // active, expired
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('locked_level_id')->references('id')->on('levels')->onDelete('set null');
            $table->foreign('locked_stream_id')->references('id')->on('streams')->onDelete('set null');
        });

        // 4. Referral Logs
        Schema::create('referral_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referrer_id');
            $table->uuid('referee_id');
            $table->decimal('commission_earned', 8, 2)->default(0.00);
            $table->string('status')->default('pending'); // pending, credited
            $table->timestamps();

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referee_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 5. User Subjects (Pivot table for student selected/onboarded subjects)
        Schema::create('user_subjects', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('subject_id');
            $table->primary(['user_id', 'subject_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        // 6. Saved / Bookmarked Papers Pivot Table
        Schema::create('saved_papers', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('paper_id');
            $table->primary(['user_id', 'paper_id']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_papers');
        Schema::dropIfExists('user_subjects');
        Schema::dropIfExists('referral_logs');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('year_access_logs');
        Schema::dropIfExists('user_devices');
    }
};
