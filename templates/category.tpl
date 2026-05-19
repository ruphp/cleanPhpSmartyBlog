{extends file="layout.tpl"}

{block name="content"}
    <section class="category-page">
        <div class="page-title">
            <h1>{$category.title|escape}</h1>
            <p>{$category.description|escape}</p>
        </div>

        <div class="sort-links">
            <span>Сортировка:</span>
            <a class="{if $sort === 'date'}active{/if}" href="/category?id={$category.id|escape}&sort=date">по дате</a>
            <a class="{if $sort === 'views'}active{/if}" href="/category?id={$category.id|escape}&sort=views">по просмотрам</a>
        </div>

        <div class="post-list">
            {foreach $posts as $post}
                <article class="post-row">
                    <img src="{$post.image|escape}" alt="{$post.title|escape}">
                    <div>
                        <h2>{$post.title|escape}</h2>
                        <div class="post-meta">
                            <span>{$post.date|escape}</span>
                            <span>{$post.views|escape} просмотров</span>
                        </div>
                        <p>{$post.description|escape}</p>
                        <a class="read-link" href="/post?id={$post.id|escape}">Читать далее</a>
                    </div>
                </article>
            {/foreach}
        </div>

        {if $totalPages > 1}
            <div class="pagination">
                {for $number=1 to $totalPages}
                    <a class="{if $number === $page}active{/if}" href="/category?id={$category.id|escape}&sort={$sort|escape}&page={$number}">
                        {$number}
                    </a>
                {/for}
            </div>
        {/if}
    </section>
{/block}

