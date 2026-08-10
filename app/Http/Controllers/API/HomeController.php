<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SystemSetting;
use App\Models\YearAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Get dashboard details.
     */
    public function getDashboard(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // 1. Fetch Subscription details
        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        // 2. Fetch Subjects based on onboarding selection
        $subjectsQuery = Subject::query()
            ->where('board_id', $user->onboarded_board_id);

        if ($user->onboarded_stream_id) {
            $subjectsQuery->where('stream_id', $user->onboarded_stream_id);
        }

        $subjects = $subjectsQuery->get();

        // Calculate available paper count and unique year count for each subject
        $subjectsData = $subjects->map(function ($subject) {
            $paperStats = DB::table('papers')
                ->select(DB::raw('count(*) as count'), DB::raw('count(distinct year) as year_count'))
                ->where('subject_id', $subject->id)
                ->first();

            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'paper_count' => $paperStats->count ?? 0,
                'year_count' => $paperStats->year_count ?? 0,
            ];
        });

        // 3. Compute Quota Limits
        $maxFreeLimit = (int)SystemSetting::get('free_tier_year_limit', 3);
        $usedYears = YearAccessLog::where('user_id', $user->id)
            ->distinct()
            ->pluck('year')
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'is_premium' => $activeSubscription !== null,
                    'premium_ends_at' => $activeSubscription?->ends_at?->toIso8601String(),
                    'premium_plan' => $activeSubscription?->plan_type,
                    'onboarding' => [
                        'level_id' => $user->onboarded_level_id,
                        'stream_id' => $user->onboarded_stream_id,
                        'board_id' => $user->onboarded_board_id,
                    ]
                ],
                'subjects' => $subjectsData,
                'quota' => [
                    'max_free_years' => $maxFreeLimit,
                    'used_years' => $usedYears,
                    'remaining_free_years' => max(0, $maxFreeLimit - count($usedYears)),
                ]
            ]
        ]);
    }
}
