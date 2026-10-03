<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

require __DIR__ . '/api.php';
require __DIR__ . '/cors.php';
