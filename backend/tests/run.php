<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/api.php';

$cases = [
    ['Connexion', 'GET', '/api/health', '', 200, ['project' => 'Webnova', 'status' => 'ok', 'backend' => 'PHP']],
    ['Message', 'POST', '/api/echo', '{"message":" Bonjour Webnova ! "}', 200, ['project' => 'Webnova', 'message' => 'Bonjour Webnova !']],
    ['JSON invalide', 'POST', '/api/echo', '{', 400, null],
    ['Message absent', 'POST', '/api/echo', '{}', 422, null],
    ['Message vide', 'POST', '/api/echo', '{"message":"  "}', 422, null],
    ['Type invalide', 'POST', '/api/echo', '{"message":123}', 422, null],
    ['JSON scalaire', 'POST', '/api/echo', 'null', 422, null],
    ['Message trop long', 'POST', '/api/echo', json_encode(['message' => str_repeat('é', 501)]), 422, null],
    ['Limite Unicode', 'POST', '/api/echo', json_encode(['message' => str_repeat('é', 500)]), 200, null],
    ['Route absente', 'GET', '/api/inconnue', '', 404, null],
    ['Méthode incorrecte', 'POST', '/api/health', '', 405, null],
    ['Lecture echo interdite', 'GET', '/api/echo', '', 405, null],
];

$failures = 0;
foreach ($cases as [$name, $method, $path, $body, $expectedStatus, $expectedData]) {
    [$status, $data] = handleRequest($method, $path, $body);
    $passed = $status === $expectedStatus
        && ($expectedData === null || $data === $expectedData)
        && ($expectedStatus < 400 || isset($data['error']));
    echo ($passed ? 'OK' : 'ECHEC') . ' — ' . $name . PHP_EOL;
    $failures += $passed ? 0 : 1;
}
echo count($cases) . ' tests, ' . $failures . ' échec(s).' . PHP_EOL;
exit($failures > 0 ? 1 : 0);
