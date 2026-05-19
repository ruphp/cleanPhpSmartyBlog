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

function getCategory(PDO $pdo, int $categoryId): ?array
{
    $stmt = $pdo->prepare('SELECT id, title, description FROM categories WHERE id = :id');
    $stmt->execute(['id' => $categoryId]);

    $category = $stmt->fetch();

    return $category ?: null;
}

function getCategoryPosts(PDO $pdo, int $categoryId, string $sort, int $page, int $perPage): array
{
    $orderBy = $sort === 'views' ? 'p.views DESC' : 'p.published_at DESC';
    $offset = ($page - 1) * $perPage;

    $stmt = $pdo->prepare(
        "SELECT p.id, p.image, p.title, p.description, p.views, p.published_at
        FROM posts p
        INNER JOIN post_category pc ON pc.post_id = p.id
        WHERE pc.category_id = :category_id
        ORDER BY {$orderBy}
        LIMIT :limit OFFSET :offset"
    );

    $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return array_map(static function (array $post): array {
        $post['date'] = date('d.m.Y', strtotime($post['published_at']));

        return $post;
    }, $stmt->fetchAll());
}

function countCategoryPosts(PDO $pdo, int $categoryId): int
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*)
        FROM posts p
        INNER JOIN post_category pc ON pc.post_id = p.id
        WHERE pc.category_id = :category_id'
    );
    $stmt->execute(['category_id' => $categoryId]);

    return (int) $stmt->fetchColumn();
}
