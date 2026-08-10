<?php

$appKey = 'base64:IspHhaNw3pGWVYLsCCvmFNoz/jKsqb048aghSLcsRdo=';
$userAgent = 'PrashnpatraApp/1.0.0 (Flutter)';
$deviceUuid = 'test-device-uuid-1234567890';
$signature = hash_hmac('sha256', $deviceUuid . '|' . $userAgent, $appKey);

$prefixes = [
    'https://onlinequestionbank.kawsar.tech/api/v1',
    'https://onlinequestionbank.kawsar.tech/api',
    'https://onlinequestionbank.kawsar.tech',
];

echo "Testing Prefix Routes...\n";

foreach ($prefixes as $base) {
    $url = $base . '/auth/guest-init';
    echo "Testing URL: $url\n";
    
    $ch = curl_init();
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'User-Agent: ' . $userAgent,
        'X-Device-UUID: ' . $deviceUuid,
        'X-Device-Signature: ' . $signature,
    ];
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Status Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 500) . "\n\n";
}
