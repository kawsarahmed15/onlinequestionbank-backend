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

        // Calculate expected signature using the APP_KEY secret key
        $expectedSignature = hash_hmac('sha256', $deviceUuid . '|' . $userAgent, config('app.key'));

        if ($signature !== $expectedSignature) {
            return response()->json([
                'success' => false,
                'message' => 'Security validation failed: Invalid request signature.'
            ], 403);
        }

        return $next($request);
    }
}
