<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Request as PaperRequest;
use App\Models\Submission;
use App\Models\SystemSetting;
use App\Models\YearAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class PaperController extends Controller
{
    /**
     * Get list of papers.
     */
    public function getPapers(Request $request)
    {
        $query = Paper::query();
        if ($request->subject_ids) {
            $ids = explode(',', $request->subject_ids);
            $query->whereIn('subject_id', $ids);
        }
        if ($request->year) {
            $query->where('year', $request->year);
        }
        if ($request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }
        $papers = $query->where('is_active', true)->get();
        return response()->json(['success' => true, 'data' => $papers]);
    }

    /**
     * Check access rights and generate temporary signed URL.
     */
    public function checkAccessAndGetUrl(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        // Guests must register to download or preview
        if (!$user->mobile_number) {
            return response()->json([
                'success' => false,
                'action_required' => 'register',
                'message' => 'Guest limits exceeded. Please register to download or preview papers.'
            ], 403);
        }

        $paper = Paper::with('subject')->find($id);
        if (!$paper) {
            return response()->json(['success' => false, 'message' => 'Paper not found.'], 404);
        }

        // Check active subscription status
        $sub = $user->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        if ($sub) {
            // Tier 1 Premium constraint check (Class/Stream lock)
            if ($sub->plan_type === 'tier1_99') {
                if ($sub->locked_level_id !== $user->onboarded_level_id ||
                    ($sub->locked_stream_id && $sub->locked_stream_id !== $user->onboarded_stream_id)) {
                    return response()->json([
                        'success' => false,
                        'action_required' => 'upgrade',
                        'message' => 'This paper belongs to a class outside your premium subscription. Upgrade to change classes unlimitedly.'
                    ], 403);
                }
            }
            // Tier 2 (Tier2_149) has no class locks
        } else {
            // Free Tier Year Quota evaluation:
            // Check if this year is already logged in access logs
            $alreadyLogged = YearAccessLog::where('user_id', $user->id)
                ->where('subject_id', $paper->subject_id)
                ->where('year', $paper->year)
                ->exists();

            if (!$alreadyLogged) {
                // Check if account-wide limits are exceeded
                $usedYearsCount = YearAccessLog::where('user_id', $user->id)
                    ->distinct()
                    ->count('year');

                $limit = (int)SystemSetting::get('free_tier_year_limit', 3);

                if ($usedYearsCount >= $limit) {
                    return response()->json([
                        'success' => false,
                        'action_required' => 'upgrade',
                        'message' => "Free limit of {$limit} unique years reached. Upgrade to premium for unlimited access."
                    ], 403);
                }

                // Log the newly consumed year
                YearAccessLog::create([
                    'user_id' => $user->id,
                    'subject_id' => $paper->subject_id,
                    'year' => $paper->year,
                    'accessed_at' => now(),
                ]);
            }
        }

        // Generate dynamic signed storage URL valid for 10 minutes
        $downloadUrl = URL::temporarySignedRoute(
            'api.papers.download',
            now()->addMinutes(10),
            ['id' => $paper->id, 'user_id' => $user->id]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'file_url' => $downloadUrl,
                'file_size' => $paper->file_size_bytes,
                'paper' => [
                    'year' => $paper->year,
                    'set' => $paper->paper_set,
                    'exam_type' => $paper->exam_type,
                ]
            ]
        ]);
    }

    /**
     * Download secure paper endpoint.
     */
    public function downloadPaper(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired secure signature link.');
        }

        $paper = Paper::find($id);
        if (!$paper) {
            abort(404, 'Paper file not found.');
        }

        // Increment download counter safely
        $paper->increment('download_count');

        // Check if file is stored in public or local storage
        if (str_starts_with($paper->file_path, 'http')) {
            return redirect($paper->file_path);
        }

        if (!Storage::disk('private')->exists($paper->file_path)) {
            abort(404, 'Paper source file not found on disk.');
        }

        return Storage::disk('private')->download($paper->file_path, "paper_{$paper->year}_{$paper->paper_set}.pdf");
    }

    /**
     * Submit crowdsourced paper for review.
     */
    public function submitPaper(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|uuid|exists:subjects,id',
            'year' => 'required|integer|min:2000',
            'paper_set' => 'nullable|string|max:5',
            'file' => 'required|file|mimes:pdf|max:10240', // Limit to 10MB PDF
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Check rate limiting rules on submissions (max 3 uploads per hour)
        $hourlyUploadsCount = Submission::where('submitted_by', $user->id)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($hourlyUploadsCount >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limit exceeded: You can only upload up to 3 papers per hour.'
            ], 429);
        }

        $filePath = $request->file('file')->store('submissions', 'private');

        $submission = Submission::create([
            'subject_id' => $request->subject_id,
            'year' => $request->year,
            'paper_set' => $request->paper_set,
            'file_path' => $filePath,
            'status' => 'pending',
            'submitted_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Paper submitted successfully and is pending review.',
            'data' => [
                'submission_id' => $submission->id,
            ]
        ], 201);
    }

    /**
     * Submit missing paper request.
     */
    public function requestPaper(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|uuid|exists:subjects,id',
            'year' => 'required|integer|min:2000',
            'paper_set' => 'nullable|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $paperRequest = PaperRequest::create([
            'subject_id' => $request->subject_id,
            'year' => $request->year,
            'paper_set' => $request->paper_set,
            'status' => 'pending',
            'requested_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request logged successfully.',
            'data' => [
                'request_id' => $paperRequest->id,
            ]
        ], 201);
    }
}
