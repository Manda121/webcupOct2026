<?php

declare(strict_types=1);

/** @return array<string, string> */
function corsHeaders(string $origin, string $allowedOrigins): array
{
    $headers = ['Vary' => 'Origin'];
    $allowed = array_filter(array_map('trim', explode(',', $allowedOrigins)));
    if ($origin !== '' && in_array($origin, $allowed, true)) {
        $headers['Access-Control-Allow-Origin'] = $origin;
        $headers['Access-Control-Allow-Methods'] = 'GET, POST, OPTIONS';
        $headers['Access-Control-Allow-Headers'] = 'Content-Type, Accept';
    }
    return $headers;
}
