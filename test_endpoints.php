<?php

$baseUrl = 'https://onlinequestionbank.kawsar.tech/api/v1';
$appKey = 'base64:IspHhaNw3pGWVYLsCCvmFNoz/jKsqb048aghSLcsRdo=';
$userAgent = 'PrashnpatraApp/1.0.0 (Flutter)';
$deviceUuid = 'test-device-uuid-1234567890';

// Calculate expected signature
$signature = hash_hmac('sha256', $deviceUuid . '|' . $userAgent, $appKey);

echo "Testing Live Production APIs at: $baseUrl\n";
echo "Device Signature: $signature\n\n";

function sendRequest($method, $path, $data = null, $token = null) {
    global $baseUrl, $userAgent, $deviceUuid, $signature;
    
    $url = $baseUrl . $path;
    $ch = curl_init();
    
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'User-Agent: ' . $userAgent,
        'X-Device-UUID: ' . $deviceUuid,
        'X-Device-Signature: ' . $signature,
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL verification just for testing script
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true) ?: $response
    ];
}

// 1. Test Guest Initialization
echo "1. Testing POST /auth/guest-init... ";
$guestInit = sendRequest('POST', '/auth/guest-init');
if ($guestInit['code'] === 200 && isset($guestInit['body']['success']) && $guestInit['body']['success']) {
    echo "SUCCESS (200)\n";
    $token = $guestInit['body']['data']['token'] ?? null;
    $userId = $guestInit['body']['data']['user_id'] ?? null;
} else {
    echo "FAILED ({$guestInit['code']})\n";
    if (isset($guestInit['body']['message'])) {
        echo "Error Message: " . $guestInit['body']['message'] . "\n";
    } else {
        print_r($guestInit['body']);
    }
    exit(1);
}

// 2. Test Get Levels
echo "2. Testing GET /levels... ";
$levels = sendRequest('GET', '/levels', null, $token);
if ($levels['code'] === 200) {
    echo "SUCCESS (200) - Found " . count($levels['body']['data'] ?? []) . " levels\n";
    $levelId = $levels['body']['data'][0]['id'] ?? null;
} else {
    echo "FAILED ({$levels['code']})\n";
}

// 3. Test Get Streams
echo "3. Testing GET /streams... ";
$streams = sendRequest('GET', '/streams', null, $token);
if ($streams['code'] === 200) {
    echo "SUCCESS (200) - Found " . count($streams['body']['data'] ?? []) . " streams\n";
    $streamId = $streams['body']['data'][0]['id'] ?? null;
} else {
    echo "FAILED ({$streams['code']})\n";
}

// 4. Test Get Boards
echo "4. Testing GET /boards... ";
$boards = sendRequest('GET', '/boards', null, $token);
if ($boards['code'] === 200) {
    echo "SUCCESS (200) - Found " . count($boards['body']['data'] ?? []) . " boards\n";
    $boardId = $boards['body']['data'][0]['id'] ?? null;
} else {
    echo "FAILED ({$boards['code']})\n";
}

// 5. Test User Onboarding Saving
if ($userId && $levelId && $boardId) {
    echo "5. Testing PUT /users/{id}/onboarding... ";
    $onboard = sendRequest('PUT', "/users/{$userId}/onboarding", [
        'onboarded_level_id' => $levelId,
        'onboarded_stream_id' => $streamId,
        'onboarded_board_id' => $boardId
    ], $token);
    if ($onboard['code'] === 200) {
        echo "SUCCESS (200)\n";
    } else {
        echo "FAILED ({$onboard['code']})\n";
        print_r($onboard['body']);
    }
}

// 6. Test Home Dashboard Fetch
echo "6. Testing GET /home... ";
$home = sendRequest('GET', '/home', null, $token);
if ($home['code'] === 200) {
    echo "SUCCESS (200) - Dashboard stats loaded successfully\n";
} else {
    echo "FAILED ({$home['code']})\n";
    print_r($home['body']);
}

echo "\nAll primary mobile API checks completed.\n";
