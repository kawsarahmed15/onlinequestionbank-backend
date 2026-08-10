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
            $table->foreign('onboarded_level_id')->references('id')->on('levels')->onDelete('set null');
            $table->foreign('onboarded_stream_id')->references('id')->on('streams')->onDelete('set null');
            $table->foreign('onboarded_board_id')->references('id')->on('boards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['onboarded_level_id']);
            $table->dropForeign(['onboarded_stream_id']);
            $table->dropForeign(['onboarded_board_id']);
        });
    }
};
