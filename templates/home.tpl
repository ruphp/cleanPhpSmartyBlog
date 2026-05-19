{extends file="layout.tpl"}

{block name="content"}
    {foreach $categories as $category}
        <section class="category-section">
            <div class="section-head">
                <h2>{$category.title|escape}</h2>
                <a href="/category?id={$category.id|escape}">Все статьи</a>
            </div>

            <div class="post-grid">
                {foreach $category.posts as $post}
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
    {/foreach}
{/block}
