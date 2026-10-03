<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$allowedOrigins = $_ENV['CORS_ALLOWED_ORIGINS'] ?? $_SERVER['CORS_ALLOWED_ORIGINS'] ?? getenv('CORS_ALLOWED_ORIGINS');
if ($allowedOrigins === false) {
    $allowedOrigins = 'http://localhost:5173,http://127.0.0.1:5173';
}
$cors = corsHeaders($_SERVER['HTTP_ORIGIN'] ?? '', $allowedOrigins);
foreach ($cors as $name => $value) {
    header($name . ': ' . $value);
}
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($method === 'OPTIONS') {
    if (!isset($cors['Access-Control-Allow-Origin'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Origine non autorisée.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $requestedMethod = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] ?? '';
    [$status] = handleRequest($requestedMethod, $path, '');
    // Un POST valide peut renvoyer 400 ici puisque son corps n'est pas encore envoyé.
    if ($status === 404 || $status === 405) {
        http_response_code($status);
        if ($status === 405) {
            header('Allow: ' . ($path === '/api/echo' ? 'POST' : 'GET'));
        }
        echo json_encode(['error' => 'Route ou méthode non autorisée.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    http_response_code(204);
    exit;
}

[$status, $data] = handleRequest($method, $path, file_get_contents('php://input') ?: '');
http_response_code($status);
if ($status === 405) {
    header('Allow: ' . ($path === '/api/echo' ? 'POST' : 'GET'));
}
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
