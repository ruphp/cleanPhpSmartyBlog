<?php

declare(strict_types=1);

function getHomeCategories(PDO $pdo): array
{
    $stmt = $pdo->query(
        'SELECT c.id, c.title, c.description
        FROM categories c
        WHERE EXISTS (
            SELECT 1
            FROM post_category pc
            WHERE pc.category_id = c.id
        )
        ORDER BY c.id'
    );

    $categories = $stmt->fetchAll();

    foreach ($categories as $key => $category) {
        $categories[$key]['posts'] = getLastPostsByCategory($pdo, (int) $category['id'], 3);
    }

    return $categories;
}

function getLastPostsByCategory(PDO $pdo, int $categoryId, int $limit): array
{
    $stmt = $pdo->prepare(
        'SELECT p.id, p.image, p.title, p.description, p.published_at
        FROM posts p
        INNER JOIN post_category pc ON pc.post_id = p.id
        WHERE pc.category_id = :category_id
        ORDER BY p.published_at DESC
        LIMIT :limit'
    );

    $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return array_map(static function (array $post): array {
        $post['date'] = date('d.m.Y', strtotime($post['published_at']));

        return $post;
    }, $stmt->fetchAll());
}

