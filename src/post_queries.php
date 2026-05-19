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

function getPost(PDO $pdo, int $postId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, image, title, description, body, views, published_at
        FROM posts
        WHERE id = :id'
    );
    $stmt->execute(['id' => $postId]);

    $post = $stmt->fetch();

    if (!$post) {
        return null;
    }

    $post['date'] = date('d.m.Y', strtotime($post['published_at']));
    $post['categories'] = getPostCategories($pdo, $postId);

    return $post;
}

function getPostCategories(PDO $pdo, int $postId): array
{
    $stmt = $pdo->prepare(
        'SELECT c.id, c.title
        FROM categories c
        INNER JOIN post_category pc ON pc.category_id = c.id
        WHERE pc.post_id = :post_id
        ORDER BY c.title'
    );
    $stmt->execute(['post_id' => $postId]);

    return $stmt->fetchAll();
}

function addPostView(PDO $pdo, int $postId): void
{
    $stmt = $pdo->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
    $stmt->execute(['id' => $postId]);
}

function getRelatedPosts(PDO $pdo, int $postId, int $limit): array
{
    $stmt = $pdo->prepare(
        'SELECT DISTINCT p.id, p.image, p.title, p.description, p.published_at
        FROM posts p
        INNER JOIN post_category pc ON pc.post_id = p.id
        WHERE p.id <> :post_id
          AND pc.category_id IN (
              SELECT category_id
              FROM post_category
              WHERE post_id = :post_id_for_category
          )
        ORDER BY p.published_at DESC
        LIMIT :limit'
    );
    $stmt->bindValue('post_id', $postId, PDO::PARAM_INT);
    $stmt->bindValue('post_id_for_category', $postId, PDO::PARAM_INT);
    $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return array_map(static function (array $post): array {
        $post['date'] = date('d.m.Y', strtotime($post['published_at']));

        return $post;
    }, $stmt->fetchAll());
}
