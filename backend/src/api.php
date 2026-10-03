<?php

declare(strict_types=1);

/** @return array{0: int, 1: array<string, mixed>} */
function handleRequest(string $method, string $path, string $body): array
{
    $routes = ['/' => 'GET', '/api/health' => 'GET', '/api/echo' => 'POST'];
    if (!isset($routes[$path])) {
        return [404, ['error' => 'Route introuvable.']];
    }
    if ($method !== $routes[$path]) {
        return [405, ['error' => 'Méthode non autorisée.']];
    }
    if ($path === '/') {
        return [200, ['project' => 'Webnova', 'type' => 'API', 'health' => '/api/health']];
    }
    if ($path === '/api/health') {
        return [200, ['project' => 'Webnova', 'status' => 'ok', 'backend' => 'PHP']];
    }
    try {
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $error) {
        return [400, ['error' => 'JSON invalide.']];
    }
    $message = is_array($data) ? ($data['message'] ?? null) : null;
    if (!is_string($message) || trim($message) === '') {
        return [422, ['error' => 'Un message non vide est requis.']];
    }
    $message = trim($message);
    $length = preg_match_all('/./us', $message);
    if ($length === false || $length > 500) {
        return [422, ['error' => 'Le message doit contenir au maximum 500 caractères.']];
    }
    return [200, ['project' => 'Webnova', 'message' => $message]];
}
