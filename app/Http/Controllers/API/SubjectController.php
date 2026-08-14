<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Board;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Get list of levels with their onboarding config.
     * The config tells the Flutter app exactly which steps to show and what labels to use.
     */
    public function getLevels()
    {
        $levels = Level::orderBy('sort_order', 'asc')->get()->map(function ($level) {
            return [
                'id'                => $level->id,
                'name'              => $level->name,
                'icon_name'         => $level->icon_name,
                'description'       => $level->description,
                'sort_order'        => $level->sort_order,
                'onboarding_config' => $level->resolved_config,
            ];
        });
        return response()->json(['success' => true, 'data' => $levels]);
    }

    /**
     * Get list of streams by level.
     */
    public function getStreams(Request $request)
    {
        $query = Stream::query();
        if ($request->level_id) {
            $query->where('level_id', $request->level_id);
        }
        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    /**
     * Get list of semesters by level.
     */
    public function getSemesters(Request $request)
    {
        $query = \App\Models\Semester::query();
        if ($request->level_id) {
            $query->where('level_id', $request->level_id);
        }
        $semesters = $query->orderBy('number', 'asc')->get();
        return response()->json(['success' => true, 'data' => $semesters]);
    }

    /**
     * Get list of boards.
     */
    public function getBoards(Request $request)
    {
        $query = Board::with('state');
        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%")
                  ->orWhere('full_name', 'like', "%{$request->q}%");
        }
        if ($request->state_id) {
            $query->where('state_id', $request->state_id);
        }
        $boards = $query->orderBy('is_national', 'desc')->get();
        return response()->json(['success' => true, 'data' => $boards]);
    }

    /**
     * Get list of subjects.
     */
    public function getSubjects(Request $request)
    {
        $query = Subject::query();
        
        $query->whereHas('relations', function ($q) use ($request) {
            if ($request->level_id) {
                $q->where('level_id', $request->level_id);
            }
            if ($request->board_id) {
                $q->where('board_id', $request->board_id);
            }
            if ($request->stream_id) {
                $q->where('stream_id', $request->stream_id);
            }
            if ($request->semester_id) {
                $q->where('semester_id', $request->semester_id);
            }
        });

        $subjects = $query->orderBy('name', 'asc')->get();
        return response()->json(['success' => true, 'data' => $subjects]);
    }

    /**
     * Get years status grid for a subject.
     */
    public function getYears($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'Subject not found.'], 404);
        }

        // Expected range: Last 10 years
        $currentYear = (int)date('Y');
        $expectedYears = range($currentYear, $currentYear - 9);

        // Fetch published/available papers
        $availablePapers = DB::table('papers')
            ->where('subject_id', $id)
            ->where('is_active', true)
            ->get();

        $availableYears = $availablePapers->pluck('year')->unique()->toArray();

        $grid = collect($expectedYears)->map(function ($year) use ($id, $availablePapers, $availableYears) {
            $isAvailable = in_array($year, $availableYears);
            $paperSets = $isAvailable 
                ? $availablePapers->where('year', $year)->pluck('paper_set')->filter()->unique()->toArray() 
                : [];

            return [
                'year' => $year,
                'is_available' => $isAvailable,
                'available_sets' => $paperSets,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                ],
                'years_grid' => $grid
            ]
        ]);
    }
}
