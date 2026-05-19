<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/db.php';

$pdo = db();

$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE posts');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS=1');

$categories = [
    ['Новости', 'Последние новости и короткие заметки.'],
    ['Разработка', 'Статьи про PHP, MySQL и веб-разработку.'],
    ['Дизайн', 'Материалы про интерфейсы и визуальную часть сайта.'],
    ['Проекты', 'Истории о создании небольших проектов.'],
];

$categoryIds = [];

foreach ($categories as [$title, $description]) {
    $stmt = $pdo->prepare('INSERT INTO categories (title, description) VALUES (:title, :description)');
    $stmt->execute([
        'title' => $title,
        'description' => $description,
    ]);

    $categoryIds[] = (int) $pdo->lastInsertId();
}

$posts = [
    [
        'image' => '/assets/images/post-bag.png',
        'title' => 'Первый запуск блога',
        'description' => 'Короткая заметка о первом запуске проекта.',
        'body' => 'Это первая статья в блоге. Здесь будет основной текст статьи.',
        'views' => 12,
        'published_at' => '2026-05-01 10:00:00',
        'categories' => [0, 3],
    ],
    [
        'image' => '/assets/images/post-portrait.png',
        'title' => 'Как устроен простой роутинг',
        'description' => 'Разбираем базовый роутинг без фреймворка.',
        'body' => 'В чистом PHP можно сделать простой роутинг через текущий путь запроса.',
        'views' => 48,
        'published_at' => '2026-05-03 12:00:00',
        'categories' => [1],
    ],
    [
        'image' => '/assets/images/post-coffee.png',
        'title' => 'Зачем нужен шаблонизатор',
        'description' => 'Smarty помогает отделить HTML от PHP-кода.',
        'body' => 'Шаблонизатор делает страницы аккуратнее и проще для поддержки.',
        'views' => 31,
        'published_at' => '2026-05-05 09:30:00',
        'categories' => [1, 2],
    ],
    [
        'image' => '/assets/images/post-bag.png',
        'title' => 'Минимальная структура проекта',
        'description' => 'Какие папки нужны небольшому PHP-проекту.',
        'body' => 'Для небольшого проекта достаточно public, src, templates и database.',
        'views' => 24,
        'published_at' => '2026-05-06 15:00:00',
        'categories' => [1, 3],
    ],
    [
        'image' => '/assets/images/post-portrait.png',
        'title' => 'Как выбрать обложку статьи',
        'description' => 'Несколько простых правил для изображений в блоге.',
        'body' => 'Обложка должна помогать отличать статьи друг от друга.',
        'views' => 19,
        'published_at' => '2026-05-08 11:00:00',
        'categories' => [2],
    ],
    [
        'image' => '/assets/images/post-coffee.png',
        'title' => 'Работа с MySQL через PDO',
        'description' => 'PDO дает простой способ делать запросы к базе.',
        'body' => 'Для запросов к MySQL используем PDO и подготовленные выражения.',
        'views' => 63,
        'published_at' => '2026-05-10 14:00:00',
        'categories' => [1],
    ],
    [
        'image' => '/assets/images/post-bag.png',
        'title' => 'Страница категории',
        'description' => 'На странице категории нужны сортировка и пагинация.',
        'body' => 'Сортировка и пагинация помогают удобно смотреть список статей.',
        'views' => 37,
        'published_at' => '2026-05-12 16:20:00',
        'categories' => [0, 3],
    ],
    [
        'image' => '/assets/images/post-portrait.png',
        'title' => 'История маленького проекта',
        'description' => 'Как шаг за шагом собрать простой блог.',
        'body' => 'Проект удобнее делать маленькими шагами и проверять каждый этап.',
        'views' => 52,
        'published_at' => '2026-05-14 18:00:00',
        'categories' => [3],
    ],
    [
        'image' => '/assets/images/post-coffee.png',
        'title' => 'Короткая новость проекта',
        'description' => 'Небольшое обновление о состоянии блога.',
        'body' => 'В этой новости описаны последние изменения проекта и ближайшие планы.',
        'views' => 16,
        'published_at' => '2026-05-15 10:30:00',
        'categories' => [0],
    ],
    [
        'image' => '/assets/images/post-bag.png',
        'title' => 'Простая работа с шаблонами',
        'description' => 'Как передавать данные из PHP в Smarty-шаблон.',
        'body' => 'PHP подготавливает данные, а Smarty отвечает за вывод HTML.',
        'views' => 44,
        'published_at' => '2026-05-16 13:00:00',
        'categories' => [1],
    ],
    [
        'image' => '/assets/images/post-portrait.png',
        'title' => 'Чистый вид карточки',
        'description' => 'Карточка статьи должна быть простой и читаемой.',
        'body' => 'Хорошая карточка помогает быстро понять тему статьи и перейти к чтению.',
        'views' => 28,
        'published_at' => '2026-05-17 09:15:00',
        'categories' => [2],
    ],
    [
        'image' => '/assets/images/post-coffee.png',
        'title' => 'Планирование этапов',
        'description' => 'Почему проект удобно делать маленькими шагами.',
        'body' => 'Небольшие этапы помогают чаще проверять результат и проще находить ошибки.',
        'views' => 35,
        'published_at' => '2026-05-18 17:45:00',
        'categories' => [3],
    ],
];

foreach ($posts as $post) {
    $stmt = $pdo->prepare(
        'INSERT INTO posts (image, title, description, body, views, published_at)
        VALUES (:image, :title, :description, :body, :views, :published_at)'
    );

    $stmt->execute([
        'image' => $post['image'],
        'title' => $post['title'],
        'description' => $post['description'],
        'body' => $post['body'],
        'views' => $post['views'],
        'published_at' => $post['published_at'],
    ]);

    $postId = (int) $pdo->lastInsertId();

    foreach ($post['categories'] as $categoryIndex) {
        $stmt = $pdo->prepare('INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)');
        $stmt->execute([
            'post_id' => $postId,
            'category_id' => $categoryIds[$categoryIndex],
        ]);
    }
}

echo 'Seed done' . PHP_EOL;
