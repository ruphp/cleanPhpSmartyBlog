<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/bootstrap.php';

view('home.tpl', [
    'title' => 'Блог на чистом PHP',
    'text' => 'Первая страница на смарти.',
]);
