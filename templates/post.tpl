{extends file="layout.tpl"}

{block name="content"}
    <article class="post-page">
        <img class="post-cover" src="{$post.image|escape}" alt="{$post.title|escape}">

        <div class="post-content">
            <h1>{$post.title|escape}</h1>

            <div class="post-meta">
                <span>{$post.date|escape}</span>
                <span>{$post.views|escape} просмотров</span>
            </div>

            <div class="post-categories">
                {foreach $post.categories as $category}
                    <a href="/category?id={$category.id|escape}">{$category.title|escape}</a>
                {/foreach}
            </div>

            <p class="post-description">{$post.description|escape}</p>
            <div class="post-body">
                {$post.body|escape|nl2br}
            </div>
        </div>
    </article>

    {if $relatedPosts}
        <section class="related-section">
            <div class="section-head">
                <h2>Похожие статьи</h2>
            </div>

            <div class="post-grid">
                {foreach $relatedPosts as $post}
                    <article class="post-card">
                        <img src="{$post.image|escape}" alt="{$post.title|escape}">
                        <h3>{$post.title|escape}</h3>
                        <time>{$post.date|escape}</time>
                        <p>{$post.description|escape}</p>
                        <a class="read-link" href="/post?id={$post.id|escape}">Читать далее</a>
                    </article>
                {/foreach}
            </div>
        </section>
    {/if}
{/block}

