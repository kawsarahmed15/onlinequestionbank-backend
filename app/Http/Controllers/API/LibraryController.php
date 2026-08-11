<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Request as PaperRequest;
use App\Models\Submission;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Get list of saved papers.
     */
    public function getSavedPapers(Request $request)
    {
        $user = $request->user();
        $papers = $user->savedPapers()->with('subject')->get();
        return response()->json([
            'success' => true,
            'data' => $papers
        ]);
    }

    /**
     * Toggle saved status of a paper.
     */
    public function toggleSavePaper(Request $request, $id)
    {
        $user = $request->user();
        $paper = Paper::find($id);

        if (!$paper) {
            return response()->json(['success' => false, 'message' => 'Paper not found.'], 404);
        }

        $exists = $user->savedPapers()->where('paper_id', $id)->exists();

        if ($exists) {
            $user->savedPapers()->detach($id);
            $saved = false;
        } else {
            $user->savedPapers()->attach($id);
            $saved = true;
        }

        return response()->json([
            'success' => true,
            'saved' => $saved,
            'message' => $saved ? 'Paper added to saved list.' : 'Paper removed from saved list.'
        ]);
    }

    /**
     * Get list of requests logged by the authenticated user.
     */
    public function getMyRequests(Request $request)
    {
        $user = $request->user();
        $requests = PaperRequest::with('subject')
            ->where('requested_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * Get list of crowdsourced submissions uploaded by the authenticated user.
     */
    public function getMySubmissions(Request $request)
    {
        $user = $request->user();
        $submissions = Submission::with('subject')
            ->where('submitted_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $submissions
        ]);
    }
}
