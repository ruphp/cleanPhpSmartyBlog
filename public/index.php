<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/bootstrap.php';
require dirname(__DIR__) . '/src/db.php';
require dirname(__DIR__) . '/src/post_queries.php';

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

$pdo = db();

if ($path === '/category') {
    $categoryId = max(1, (int) ($_GET['id'] ?? 0));
    $sort = ($_GET['sort'] ?? 'date') === 'views' ? 'views' : 'date';
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $perPage = 4;
    $category = getCategory($pdo, $categoryId);

    if ($category === null) {
        echo 'Категория не найдена';
        exit;
    }

    $totalPosts = countCategoryPosts($pdo, $categoryId);
    $totalPages = max(1, (int) ceil($totalPosts / $perPage));
    $page = min($page, $totalPages);

    view('category.tpl', [
        'title' => $category['title'],
        'category' => $category,
        'posts' => getCategoryPosts($pdo, $categoryId, $sort, $page, $perPage),
        'sort' => $sort,
        'page' => $page,
        'totalPages' => $totalPages,
    ]);
    exit;
}

view('home.tpl', [
    'title' => 'Блог на чистом PHP',
    'categories' => getHomeCategories($pdo),
]);
