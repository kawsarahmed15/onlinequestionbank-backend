<?php

use App\Models\Board;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\Level;
use App\Models\Stream;
use App\Models\Request as PaperRequest;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    // 1. Fetch onboarding taxonomies
    $levels = Level::orderBy('sort_order', 'asc')->get();
    $streams = Stream::all();
    $boards = Board::with('state')->orderBy('is_national', 'desc')->get();

    // 2. Read current focus from session (defaults to null if not onboarded)
    $focusLevelId = session('focus_level_id');
    $focusStreamId = session('focus_stream_id');
    $focusBoardId = session('focus_board_id');

    $focusLevel = $focusLevelId ? Level::find($focusLevelId) : null;
    $focusStream = $focusStreamId ? Stream::find($focusStreamId) : null;
    $focusBoard = $focusBoardId ? Board::find($focusBoardId) : null;

    // 3. Fetch subjects matching the selected focus
    $subjectsQuery = Subject::query()->with('board');
    if ($focusBoardId) {
        $subjectsQuery->where('board_id', $focusBoardId);
    }
    if ($focusStreamId) {
        $subjectsQuery->where('stream_id', $focusStreamId);
    }
    
    $subjects = $subjectsQuery->withCount(['papers' => function ($query) {
            $query->where('is_active', true);
        }])
        ->orderBy('name', 'asc')
        ->get();

    // 4. Fetch general stats
    $stats = [
        'boards' => Board::count(),
        'subjects' => Subject::count(),
        'papers' => Paper::where('is_active', true)->count(),
        'requests' => PaperRequest::count(),
    ];

    // 5. Fetch all papers for year grids if a subject is clicked (via query parameter)
    $selectedSubject = null;
    $papersGrid = [];
    if ($request->has('subject')) {
        $selectedSubject = Subject::with('board')->find($request->subject);
        if ($selectedSubject) {
            // Last 10 years grid range
            $currentYear = (int)date('Y');
            $expectedYears = range($currentYear, $currentYear - 9);

            $availablePapers = Paper::where('subject_id', $selectedSubject->id)
                ->where('is_active', true)
                ->get()
                ->groupBy('year');

            foreach ($expectedYears as $year) {
                $papersGrid[$year] = [
                    'available' => isset($availablePapers[$year]),
                    'papers' => $availablePapers[$year] ?? collect(),
                ];
            }
        }
    }

    return view('welcome', compact(
        'levels', 'streams', 'boards',
        'focusLevel', 'focusStream', 'focusBoard',
        'subjects', 'stats', 'selectedSubject', 'papersGrid'
    ));
});

// Post action to save onboarding choices in session
Route::post('/onboarding/save', function (Request $request) {
    $request->validate([
        'level_id' => 'required|uuid|exists:levels,id',
        'stream_id' => 'nullable|uuid|exists:streams,id',
        'board_id' => 'required|uuid|exists:boards,id',
    ]);

    session([
        'focus_level_id' => $request->level_id,
        'focus_stream_id' => $request->stream_id,
        'focus_board_id' => $request->board_id,
    ]);

    return redirect('/')->with('success', 'Focus set successfully!');
});

// Clear onboarding focus
Route::post('/onboarding/clear', function () {
    session()->forget(['focus_level_id', 'focus_stream_id', 'focus_board_id']);
    return redirect('/');
});

Route::post('/requests/store', function (Request $request) {
    $request->validate([
        'subject_id' => 'required|uuid|exists:subjects,id',
        'year' => 'required|integer|min:2000|max:2030',
        'paper_set' => 'nullable|string|max:5',
    ]);

    PaperRequest::create([
        'subject_id' => $request->subject_id,
        'year' => $request->year,
        'paper_set' => $request->paper_set ?: 'A',
        'status' => 'pending',
    ]);

    return redirect()->back()->with('success', 'Your request has been logged successfully!');
});

Route::post('/submissions/store', function (Request $request) {
    $request->validate([
        'subject_id' => 'required|uuid|exists:subjects,id',
        'year' => 'required|integer|min:2000|max:2030',
        'paper_set' => 'nullable|string|max:5',
        'file' => 'required|file|mimes:pdf|max:10240',
    ]);

    $path = $request->file('file')->store('submissions', 'private');

    Submission::create([
        'subject_id' => $request->subject_id,
        'year' => $request->year,
        'paper_set' => $request->paper_set ?: 'A',
        'file_path' => $path,
        'status' => 'pending',
    ]);

    return redirect()->back()->with('success', 'Thank you! Your paper has been submitted for verification.');
});

// Helper route to run migrations
Route::get('/run-migrations', function () {
    try {
        echo "Starting migrations and seeding on remote VPS...<br>";
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
        echo "<b>Success! Database tables created and sample records seeded.</b><br>";
        echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        echo "<b>Migration Failed:</b> " . $e->getMessage();
    }
});
