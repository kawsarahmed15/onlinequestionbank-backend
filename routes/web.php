<?php

use App\Models\Board;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\Request as PaperRequest;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $stats = [
        'boards' => Board::count(),
        'subjects' => Subject::count(),
        'papers' => Paper::where('is_active', true)->count(),
        'requests' => PaperRequest::count(),
    ];

    $subjects = Subject::with('board')
        ->withCount(['papers' => function ($query) {
            $query->where('is_active', true);
        }])
        ->orderBy('name', 'asc')
        ->get();

    return view('welcome', compact('stats', 'subjects'));
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
        'paper_set' => $request->paper_set,
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
        'paper_set' => $request->paper_set,
        'file_path' => $path,
        'status' => 'pending',
    ]);

    return redirect()->back()->with('success', 'Thank you! Your paper has been submitted for verification.');
});
