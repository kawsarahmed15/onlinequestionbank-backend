<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\Submission;
use App\Models\Request as PaperRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminAPIController extends Controller
{
    /**
     * Admin login fallback.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Check if admin user exists and password matches
        $admin = User::where('role', 'admin')->first();

        if (!$admin || !password_verify($request->password, $admin->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid password credentials.'], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * Fetch statistics dashboard for admin mobile app.
     */
    public function stats(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'boards_count' => Board::count(),
                'subjects_count' => Subject::count(),
                'papers_count' => Paper::count(),
                'requests_count' => PaperRequest::where('status', 'pending')->count(),
                'submissions_count' => Submission::where('status', 'pending')->count(),
            ]
        ]);
    }

    /**
     * Create new board.
     */
    public function createBoard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'state_id' => 'nullable|uuid|exists:states,id',
            'is_national' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $board = Board::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'state_id' => $request->state_id,
            'is_national' => $request->is_national ?? false,
        ]);

        return response()->json(['success' => true, 'data' => $board], 201);
    }

    /**
     * Create new subject.
     */
    public function createSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'board_id' => 'required|uuid|exists:boards,id',
            'stream_id' => 'nullable|uuid|exists:streams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $subject = Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'board_id' => $request->board_id,
            'stream_id' => $request->stream_id,
        ]);

        return response()->json(['success' => true, 'data' => $subject], 201);
    }

    /**
     * Delete subject.
     */
    public function deleteSubject(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'Subject not found.'], 404);
        }

        $subject->delete();

        return response()->json(['success' => true, 'message' => 'Subject deleted successfully.']);
    }

    /**
     * Create new paper.
     */
    public function createPaper(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|uuid|exists:subjects,id',
            'board_id' => 'required|uuid|exists:boards,id',
            'stream_id' => 'nullable|uuid|exists:streams,id',
            'semester_id' => 'nullable|uuid|exists:semesters,id',
            'year' => 'required|integer|min:2000',
            'paper_set' => 'required|string|max:5',
            'exam_type' => 'required|string|in:annual,supplementary',
            'file_url' => 'required|string',
            'file_size_bytes' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $paper = Paper::create([
            'subject_id' => $request->subject_id,
            'board_id' => $request->board_id,
            'stream_id' => $request->stream_id,
            'semester_id' => $request->semester_id,
            'year' => $request->year,
            'paper_set' => $request->paper_set,
            'exam_type' => $request->exam_type,
            'file_path' => $request->file_url,
            'file_size_bytes' => $request->file_size_bytes,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'data' => $paper], 201);
    }

    /**
     * Get pending submissions list.
     */
    public function getSubmissions(Request $request)
    {
        $query = Submission::with(['subject', 'submitter']);
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $submissions = $query->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $submissions]);
    }

    /**
     * Approve submission.
     */
    public function approveSubmission(Request $request, $id)
    {
        $submission = Submission::find($id);
        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'Submission not found.'], 404);
        }

        DB::transaction(function () use ($submission) {
            $submission->update(['status' => 'approved']);
            
            $submitter = $submission->submitter;
            
            Paper::create([
                'subject_id' => $submission->subject_id,
                'board_id' => $submitter ? $submitter->onboarded_board_id : '55555555-5555-5555-5555-555555555555',
                'stream_id' => $submitter ? $submitter->onboarded_stream_id : null,
                'semester_id' => $submitter ? $submitter->onboarded_semester_id : null,
                'year' => $submission->year,
                'paper_set' => $submission->paper_set,
                'exam_type' => 'annual',
                'file_path' => $submission->file_path,
                'is_active' => true,
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Submission approved successfully.']);
    }

    /**
     * Reject submission.
     */
    public function rejectSubmission(Request $request, $id)
    {
        $submission = Submission::find($id);
        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'Submission not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return response()->json(['success' => true, 'message' => 'Submission rejected successfully.']);
    }
}
