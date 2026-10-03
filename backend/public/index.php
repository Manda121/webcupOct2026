<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/api.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
[$status, $data] = handleRequest($method, $path, file_get_contents('php://input') ?: '');

http_response_code($status);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
if ($status === 405) {
    header('Allow: ' . ($path === '/api/health' ? 'GET' : 'POST'));
}
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
