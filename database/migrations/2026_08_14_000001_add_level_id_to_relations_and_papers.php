<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subject_relations', function (Blueprint $table) {
            $table->uuid('board_id')->nullable()->change();
            $table->foreignUuid('level_id')->nullable()->constrained('levels')->cascadeOnDelete();
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->foreignUuid('level_id')->nullable()->constrained('levels')->cascadeOnDelete();
        });

        $classX = DB::table('levels')->where('name', 'Class X')->first();

        $subjectRelations = DB::table('subject_relations')->get();
        foreach ($subjectRelations as $relation) {
            $levelId = null;
            if ($relation->stream_id) {
                $stream = DB::table('streams')->where('id', $relation->stream_id)->first();
                $levelId = $stream ? $stream->level_id : null;
            } elseif ($relation->semester_id) {
                $semester = DB::table('semesters')->where('id', $relation->semester_id)->first();
                $levelId = $semester ? $semester->level_id : null;
            } else {
                $levelId = $classX ? $classX->id : null;
            }
            if ($levelId) {
                DB::table('subject_relations')->where('id', $relation->id)->update(['level_id' => $levelId]);
            }
        }

        $papers = DB::table('papers')->get();
        foreach ($papers as $paper) {
            $levelId = null;
            if ($paper->stream_id) {
                $stream = DB::table('streams')->where('id', $paper->stream_id)->first();
                $levelId = $stream ? $stream->level_id : null;
            } elseif ($paper->semester_id) {
                $semester = DB::table('semesters')->where('id', $paper->semester_id)->first();
                $levelId = $semester ? $semester->level_id : null;
            } else {
                $levelId = $classX ? $classX->id : null;
            }
            if ($levelId) {
                DB::table('papers')->where('id', $paper->id)->update(['level_id' => $levelId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_relations', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });
    }
};
