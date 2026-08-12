<?php

use App\Models\Board;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\Level;
use App\Models\Stream;
use App\Models\Request as PaperRequest;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function (Request $request) {
    // 1. Ensure we have a persistent Web User (Guest) session
    $webUserId = session('web_user_id');
    if (!$webUserId) {
        $guestUser = User::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Web Guest ' . Str::random(4),
            'role' => 'student',
            'password' => bcrypt(Str::random(16)),
        ]);
        session(['web_user_id' => $guestUser->id]);
        $webUserId = $guestUser->id;
    }
    
    $webUser = User::find($webUserId);
    if (!$webUser) {
        session()->forget('web_user_id');
        return redirect('/');
    }

    // 2. Fetch onboarding taxonomies
    $levels = Level::orderBy('sort_order', 'asc')->get();
    $streams = Stream::all();
    $boards = Board::with('state')->orderBy('is_national', 'desc')->get();
    $semesters = \App\Models\Semester::orderBy('number', 'asc')->get();

    // 3. Read current focus from session
    $focusLevelId = session('focus_level_id');
    $focusStreamId = session('focus_stream_id');
    $focusBoardId = session('focus_board_id');
    $focusSemesterId = session('focus_semester_id');

    $focusLevel = $focusLevelId ? Level::find($focusLevelId) : null;
    $focusStream = $focusStreamId ? Stream::find($focusStreamId) : null;
    $focusBoard = $focusBoardId ? Board::find($focusBoardId) : null;
    $focusSemester = $focusSemesterId ? \App\Models\Semester::find($focusSemesterId) : null;

    // 4. Fetch subjects matching the selected focus
    $subjectsQuery = Subject::query();
    
    // Check if user has pinned subjects
    $pinnedSubjectIds = $webUser->subjects()->pluck('subjects.id')->toArray();
    if (count($pinnedSubjectIds) > 0) {
        $subjectsQuery->whereIn('id', $pinnedSubjectIds);
    } else {
        if ($focusBoardId) {
            $subjectsQuery->whereHas('relations', function ($q) use ($focusBoardId, $focusStreamId, $focusSemesterId) {
                $q->where('board_id', $focusBoardId);
                if ($focusStreamId) {
                    $q->where('stream_id', $focusStreamId);
                }
                if ($focusSemesterId) {
                    $q->where('semester_id', $focusSemesterId);
                }
            });
        }
    }
    
    $subjects = $focusBoardId ? $subjectsQuery->withCount(['papers' => function ($query) {
            $query->where('is_active', true);
        }])
        ->orderBy('name', 'asc')
        ->get() : collect();

    // 5. Fetch library views data based on '?view='
    $currentView = $request->query('view', 'dashboard');
    $savedPapers = collect();
    $myRequests = collect();
    $mySubmissions = collect();

    if ($currentView === 'saved') {
        $savedPapers = $webUser->savedPapers()->with(['subject', 'board'])->get();
    } elseif ($currentView === 'requests') {
        $myRequests = PaperRequest::with('subject')
            ->where('requested_by', $webUserId)
            ->orderBy('created_at', 'desc')
            ->get();
    } elseif ($currentView === 'uploads') {
        $mySubmissions = Submission::with('subject')
            ->where('submitted_by', $webUserId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // 6. Fetch papers for year grids if a subject is clicked
    $selectedSubject = null;
    $papersGrid = [];
    if ($request->has('subject')) {
        $selectedSubject = Subject::find($request->subject);
        if ($selectedSubject) {
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

    // Get bookmark states map for the selected subject
    $userSavedPaperIds = $webUser->savedPapers()->pluck('papers.id')->toArray();

    return view('welcome', compact(
        'levels', 'streams', 'boards', 'semesters',
        'focusLevel', 'focusStream', 'focusBoard', 'focusSemester',
        'subjects', 'selectedSubject', 'papersGrid',
        'currentView', 'savedPapers', 'myRequests', 'mySubmissions',
        'userSavedPaperIds', 'webUser'
    ));
});

// Post action to save onboarding choices in session
Route::post('/onboarding/save', function (Request $request) {
    $request->validate([
        'level_id' => 'required|uuid|exists:levels,id',
        'stream_id' => 'nullable|uuid|exists:streams,id',
        'board_id' => 'required|uuid|exists:boards,id',
        'semester_id' => 'nullable|uuid|exists:semesters,id',
        'subject_ids' => 'nullable|array',
    ]);

    session([
        'focus_level_id' => $request->level_id,
        'focus_stream_id' => $request->stream_id,
        'focus_board_id' => $request->board_id,
        'focus_semester_id' => $request->semester_id,
    ]);

    // Also update onboarding details on the persistent Web User model
    $webUserId = session('web_user_id');
    if ($webUserId) {
        User::where('id', $webUserId)->update([
            'onboarded_level_id' => $request->level_id,
            'onboarded_stream_id' => $request->stream_id,
            'onboarded_board_id' => $request->board_id,
            'onboarded_semester_id' => $request->semester_id,
        ]);
        
        $user = User::find($webUserId);
        if ($user && $request->has('subject_ids')) {
            $user->subjects()->sync($request->subject_ids);
        }
    }

    return redirect('/')->with('success', 'Focus set successfully!');
});

// Stepper quick browse setup
Route::post('/onboarding/quick-browse', function (Request $request) {
    $request->validate([
        'level_id' => 'required|uuid',
        'board_id' => 'required|uuid',
        'stream_id' => 'nullable|uuid',
        'semester_id' => 'nullable|uuid',
    ]);

    session([
        'focus_level_id' => $request->level_id,
        'focus_stream_id' => $request->stream_id,
        'focus_board_id' => $request->board_id,
        'focus_semester_id' => $request->semester_id,
    ]);

    $webUserId = session('web_user_id');
    if ($webUserId) {
        User::where('id', $webUserId)->update([
            'onboarded_level_id' => $request->level_id,
            'onboarded_stream_id' => $request->stream_id,
            'onboarded_board_id' => $request->board_id,
            'onboarded_semester_id' => $request->semester_id,
        ]);
    }

    return redirect('/?view=dashboard')->with('success', 'Dashboard view re-scoped!');
});

// Clear onboarding focus
Route::post('/onboarding/clear', function () {
    session()->forget(['focus_level_id', 'focus_stream_id', 'focus_board_id', 'focus_semester_id']);
    return redirect('/');
});

// Toggle bookmark on Web Panel
Route::post('/papers/{id}/toggle-save-web', function ($id) {
    $webUserId = session('web_user_id');
    if (!$webUserId) return redirect()->back()->with('error', 'Session expired.');

    $user = User::find($webUserId);
    $exists = $user->savedPapers()->where('paper_id', $id)->exists();

    if ($exists) {
        $user->savedPapers()->detach($id);
        $msg = 'Paper removed from saved.';
    } else {
        $user->savedPapers()->attach($id);
        $msg = 'Paper added to saved.';
    }

    return redirect()->back()->with('success', $msg);
});

// Store requests
Route::post('/requests/store', function (Request $request) {
    $webUserId = session('web_user_id');
    if (!$webUserId) return redirect()->back()->with('error', 'Session expired.');

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
        'requested_by' => $webUserId,
    ]);

    return redirect()->back()->with('success', 'Your request has been logged successfully!');
});

// Store submissions
Route::post('/submissions/store', function (Request $request) {
    $webUserId = session('web_user_id');
    if (!$webUserId) return redirect()->back()->with('error', 'Session expired.');

    $request->validate([
        'subject_id' => 'required|uuid|exists:subjects,id',
        'year' => 'required|integer|min:2000|max:2030',
        'paper_set' => 'nullable|string|max:5',
        'file' => 'required|file|mimes:pdf|max:10240',
    ]);

    $path = $request->file('file')->store('submissions', 'public');

    Submission::create([
        'subject_id' => $request->subject_id,
        'year' => $request->year,
        'paper_set' => $request->paper_set ?: 'A',
        'file_path' => '/storage/' . $path,
        'status' => 'pending',
        'submitted_by' => $webUserId,
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

// JSON endpoint for web client subject fetching (bypass fingerprint headers)
Route::get('/web/subjects', function (Request $request) {
    $query = Subject::query();
    $query->whereHas('relations', function ($q) use ($request) {
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
    return response()->json(['success' => true, 'data' => $query->orderBy('name', 'asc')->get()]);
});

// JSON endpoint for fetching papers for a specific subject
Route::get('/web/subjects/{id}/papers', function ($id) {
    $subject = Subject::find($id);
    if (!$subject) {
        return response()->json(['success' => false, 'message' => 'Subject not found.'], 404);
    }
    
    $currentYear = (int)date('Y');
    $expectedYears = range($currentYear, $currentYear - 9);

    $availablePapers = Paper::where('subject_id', $id)
        ->where('is_active', true)
        ->get();

    $papersByYear = $availablePapers->groupBy('year');

    $grid = [];
    foreach ($expectedYears as $year) {
        $yearPapers = $papersByYear[$year] ?? collect();
        $grid[] = [
            'year' => $year,
            'available' => count($yearPapers) > 0,
            'papers' => $yearPapers->map(function ($p) {
                return [
                    'id' => $p->id,
                    'paper_set' => $p->paper_set,
                    'exam_type' => $p->exam_type,
                    'file_url' => $p->file_url,
                ];
            })->toArray()
        ];
    }

    return response()->json([
        'success' => true,
        'subject' => [
            'id' => $subject->id,
            'name' => $subject->name,
            'code' => $subject->code,
        ],
        'grid' => $grid
    ]);
});

// AJAX Auth: Login
Route::post('/web/login', function (Request $request) {
    $request->validate([
        'mobile_number' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('mobile_number', $request->mobile_number)->first();
    if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        session(['web_user_id' => $user->id]);
        
        if ($user->onboarded_level_id) {
            session([
                'focus_level_id' => $user->onboarded_level_id,
                'focus_stream_id' => $user->onboarded_stream_id,
                'focus_board_id' => $user->onboarded_board_id,
                'focus_semester_id' => $user->onboarded_semester_id,
            ]);
        }

        return response()->json([
            'success' => true, 
            'user' => [
                'name' => $user->name,
                'mobile_number' => $user->mobile_number
            ]
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid mobile number or password.'], 401);
});

// AJAX Auth: Register
Route::post('/web/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'mobile_number' => 'required|string|unique:users,mobile_number',
        'school_college_name' => 'required|string|max:255',
        'password' => 'required|string|min:6',
        'email' => 'nullable|email',
        'referral_code' => 'nullable|string',
    ]);

    $user = User::create([
        'id' => Str::uuid()->toString(),
        'name' => $request->name,
        'mobile_number' => $request->mobile_number,
        'school_college_name' => $request->school_college_name,
        'password' => bcrypt($request->password),
        'email' => $request->email,
        'referral_code' => $request->referral_code,
        'role' => 'student',
    ]);

    $focusLevelId = session('focus_level_id');
    $focusStreamId = session('focus_stream_id');
    $focusBoardId = session('focus_board_id');
    $focusSemesterId = session('focus_semester_id');

    if ($focusLevelId && $focusBoardId) {
        $user->update([
            'onboarded_level_id' => $focusLevelId,
            'onboarded_stream_id' => $focusStreamId,
            'onboarded_board_id' => $focusBoardId,
            'onboarded_semester_id' => $focusSemesterId,
        ]);
    }

    session(['web_user_id' => $user->id]);

    return response()->json([
        'success' => true,
        'user' => [
            'name' => $user->name,
            'mobile_number' => $user->mobile_number
        ]
    ]);
});

// AJAX Auth: Logout
Route::post('/web/logout', function () {
    session()->forget(['web_user_id', 'focus_level_id', 'focus_stream_id', 'focus_board_id', 'focus_semester_id']);
    return response()->json(['success' => true]);
});
