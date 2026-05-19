<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/bootstrap.php';
require dirname(__DIR__) . '/src/db.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($path === '/db-check') {
    try {
        db()->query('SELECT 1');
        echo 'База данных доступна';
    } catch (Throwable) {
        echo 'База данных недоступна';
    }

    exit;
}

view('home.tpl', [
    'title' => 'Блог на чистом PHP',
    'text' => 'Первая страница на смарти.',
]);
