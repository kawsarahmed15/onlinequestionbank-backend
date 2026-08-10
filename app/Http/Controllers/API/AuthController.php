<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\ReferralLog;
use App\Models\Subject;
use App\Models\YearAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Initialize guest user session and bind device.
     */
    public function guestInit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'onboarded_level_id' => 'nullable|uuid',
            'onboarded_stream_id' => 'nullable|uuid',
            'onboarded_board_id' => 'nullable|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $deviceUuid = $request->header('X-Device-UUID');
        $deviceModel = $request->header('X-Device-Model');

        if (!$deviceUuid) {
            return response()->json(['success' => false, 'message' => 'Missing X-Device-UUID header'], 400);
        }

        return DB::transaction(function () use ($request, $deviceUuid, $deviceModel) {
            // Find user if this device is already bound
            $device = UserDevice::where('device_uuid', $deviceUuid)->first();

            if ($device) {
                $user = $device->user;
            } else {
                // Create a new guest user
                $user = User::create([
                    'name' => 'Guest User',
                    'onboarded_level_id' => $request->onboarded_level_id,
                    'onboarded_stream_id' => $request->onboarded_stream_id,
                    'onboarded_board_id' => $request->onboarded_board_id,
                    'role' => 'student',
                    'referral_code' => 'PRASHN' . strtoupper(Str::random(6)),
                ]);

                // Bind device
                UserDevice::create([
                    'user_id' => $user->id,
                    'device_uuid' => $deviceUuid,
                    'device_model' => $deviceModel,
                    'last_ip' => $request->ip(),
                    'last_active_at' => now(),
                ]);
            }

            // Revoke previous tokens for clean login
            $user->tokens()->delete();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'token' => $token,
                    'is_registered' => $user->mobile_number !== null,
                ]
            ]);
        });
    }

    /**
     * Convert guest user into fully registered user.
     */
    public function register(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string|unique:users,mobile_number,' . $user->id,
            'name' => 'required|string|max:255',
            'school_college_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'referral_code' => 'nullable|string',
            'bonus_subject_id' => 'nullable|uuid|exists:subjects,id', // Selected subject for email bonus
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request, $user) {
            $referrer = null;
            if ($request->referral_code) {
                $referrer = User::where('referral_code', trim($request->referral_code))->first();
                if ($referrer && $referrer->id === $user->id) {
                    return response()->json(['success' => false, 'message' => 'You cannot use your own referral code.'], 422);
                }
            }

            // Update user properties
            $user->update([
                'name' => $request->name,
                'mobile_number' => $request->mobile_number,
                'school_college_name' => $request->school_college_name,
                'email' => $request->email,
            ]);

            // Save referral mapping
            if ($referrer) {
                ReferralLog::create([
                    'referrer_id' => $referrer->id,
                    'referee_id' => $user->id,
                    'commission_earned' => 0.00, // Updated once referee buys premium
                    'status' => 'pending',
                ]);
            }

            // Award Email Bonus Year Access: Allow access to 2 extra years for the selected subject
            if ($request->email && $request->bonus_subject_id) {
                // Get 2 sample years for this subject to pre-award
                $subjectPapers = DB::table('papers')
                    ->where('subject_id', $request->bonus_subject_id)
                    ->orderBy('year', 'desc')
                    ->limit(2)
                    ->get();

                foreach ($subjectPapers as $paper) {
                    YearAccessLog::firstOrCreate([
                        'user_id' => $user->id,
                        'subject_id' => $request->bonus_subject_id,
                        'year' => $paper->year,
                    ], [
                        'accessed_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration complete successfully.',
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'is_registered' => true,
                ]
            ]);
        });
    }

    /**
     * Refresh active access token.
     */
    public function refreshToken(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Rotate token
        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
            ]
        ]);
    }

    /**
     * Save user onboarding selections.
     */
    public function saveOnboarding(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'onboarded_level_id' => 'required|uuid|exists:levels,id',
            'onboarded_stream_id' => 'nullable|uuid|exists:streams,id',
            'onboarded_board_id' => 'required|uuid|exists:boards,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user->update([
            'onboarded_level_id' => $request->onboarded_level_id,
            'onboarded_stream_id' => $request->onboarded_stream_id,
            'onboarded_board_id' => $request->onboarded_board_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Onboarding details saved successfully.'
        ]);
    }

    /**
     * Sync user subjects.
     */
    public function syncUserSubjects(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'required|uuid|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user->subjects()->sync($request->subject_ids);

        return response()->json([
            'success' => true,
            'message' => 'User subjects synced successfully.'
        ]);
    }
}
