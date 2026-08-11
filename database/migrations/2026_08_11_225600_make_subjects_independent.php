<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create subject_relations table
        Schema::create('subject_relations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subject_id');
            $table->uuid('board_id');
            $table->uuid('stream_id')->nullable();
            $table->uuid('semester_id')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('stream_id')->references('id')->on('streams')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });

        // 2. Add columns to papers table
        Schema::table('papers', function (Blueprint $table) {
            $table->uuid('board_id')->nullable();
            $table->uuid('stream_id')->nullable();
            $table->uuid('semester_id')->nullable();

            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('stream_id')->references('id')->on('streams')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });

        // 3. Populate paper relations and subject relations from existing subjects data
        $subjects = DB::table('subjects')->get();
        foreach ($subjects as $subject) {
            // Check if this relation already exists (e.g. to make subjects unique)
            $exists = DB::table('subject_relations')
                ->where('subject_id', $subject->id)
                ->where('board_id', $subject->board_id)
                ->where('stream_id', $subject->stream_id)
                ->where('semester_id', $subject->semester_id)
                ->exists();

            if (!$exists) {
                DB::table('subject_relations')->insert([
                    'id' => Str::uuid()->toString(),
                    'subject_id' => $subject->id,
                    'board_id' => $subject->board_id,
                    'stream_id' => $subject->stream_id,
                    'semester_id' => $subject->semester_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update papers that were linked to this subject
            DB::table('papers')->where('subject_id', $subject->id)->update([
                'board_id' => $subject->board_id,
                'stream_id' => $subject->stream_id,
                'semester_id' => $subject->semester_id,
            ]);
        }

        // 4. Remove columns from subjects table (after data migration)
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->dropForeign(['stream_id']);
            $table->dropForeign(['semester_id']);
            $table->dropColumn(['board_id', 'stream_id', 'semester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add columns back
        Schema::table('subjects', function (Blueprint $table) {
            $table->uuid('board_id')->nullable();
            $table->uuid('stream_id')->nullable();
            $table->uuid('semester_id')->nullable();

            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('stream_id')->references('id')->on('streams')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });

        // Re-populate from relations
        $relations = DB::table('subject_relations')->get();
        foreach ($relations as $rel) {
            DB::table('subjects')->where('id', $rel->subject_id)->update([
                'board_id' => $rel->board_id,
                'stream_id' => $rel->stream_id,
                'semester_id' => $rel->semester_id,
            ]);
        }

        Schema::dropIfExists('subject_relations');

        Schema::table('papers', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->dropForeign(['stream_id']);
            $table->dropForeign(['semester_id']);
            $table->dropColumn(['board_id', 'stream_id', 'semester_id']);
        });
    }
};
