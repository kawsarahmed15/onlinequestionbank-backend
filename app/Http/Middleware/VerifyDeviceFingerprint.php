<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyDeviceFingerprint
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For development/debugging or local testing via postman, we can bypass if signature header is 'bypass'
        if (config('app.env') === 'local' && $request->header('X-Device-Signature') === 'bypass') {
            return $next($request);
        }

        $deviceUuid = $request->header('X-Device-UUID');
        $signature = $request->header('X-Device-Signature');
        $userAgent = $request->userAgent() ?? '';

        if (!$deviceUuid || !$signature) {
            return response()->json([
                'success' => false,
                'message' => 'Security validation failed: Missing device fingerprint headers.'
            ], 400);
        }

        // Calculate expected signature using the received User-Agent header
        $expectedSignature = hash_hmac('sha256', $deviceUuid . '|' . $userAgent, config('app.key'));

        // Fallback for Flutter Web running in browser (where browser overrides the User-Agent header but app signs using hardcoded UA)
        $fallbackSignature = hash_hmac('sha256', $deviceUuid . '|' . 'PrashnpatraApp/1.0.0 (Flutter)', config('app.key'));

        if ($signature !== $expectedSignature && $signature !== $fallbackSignature) {
            \Illuminate\Support\Facades\Log::info("Signature mismatch. Received UA: " . json_encode($userAgent) . ", UUID: " . $deviceUuid . ", Signature: " . $signature . ", Expected: " . $expectedSignature . ", Fallback Expected: " . $fallbackSignature);
            return response()->json([
                'success' => false,
                'message' => 'Security validation failed: Invalid request signature.'
            ], 403);
        }

        return $next($request);
    }
}
