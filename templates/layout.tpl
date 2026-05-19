<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title|escape}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <header class="site-header">
        <a class="site-logo" href="/">Блог</a>
    </header>

    <main class="site-main">
        {block name="content"}{/block}
    </main>

    <footer class="site-footer">
        <p>© 2026. Все права защищены.</p>
    </footer>
</body>
</html>
