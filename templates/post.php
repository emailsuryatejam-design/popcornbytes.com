    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="<?= e(category_url($post['category'])) ?>"><?= e($post['category']) ?></a></li>
                <li aria-current="page"><?= e(truncate($post['title'], 50)) ?></li>
            </ol>
        </div>
    </nav>

    <!-- Article -->
    <article class="article-page">
        <div class="container">
            <div class="content-layout">
                <div class="article-main">
                    <!-- Article Header -->
                    <header class="article-header">
                        <span class="post-category"><a href="<?= e(category_url($post['category'])) ?>"><?= e($post['category']) ?></a></span>
                        <h1><?= e($post['title']) ?></h1>
                        <div class="article-meta">
                            <span class="post-author">By <?= e($post['author']) ?></span>
                            <time datetime="<?= date('Y-m-d', strtotime($post['date'])) ?>"><?= date('F j, Y', strtotime($post['date'])) ?></time>
                            <span class="post-reading-time"><?= reading_time($post['content']) ?> min read</span>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    <?php if (!empty($post['image'])): ?>
                    <figure class="article-hero-image">
                        <img src="<?= e($post['image']) ?>"
                             alt="<?= e($post['image_alt'] ?? $post['title']) ?>"
                             width="800" height="450"
                             loading="eager">
                    </figure>
                    <?php endif; ?>

                    <!-- Header Ad -->
                    <?php if (ADSENSE_PUBLISHER_ID && ADSENSE_SLOT_HEADER): ?>
                    <div class="ad-container ad-article-top">
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="<?= e(ADSENSE_PUBLISHER_ID) ?>"
                             data-ad-slot="<?= e(ADSENSE_SLOT_HEADER) ?>"
                             data-ad-format="auto"
                             data-full-width-responsive="true"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="article-content">
                        <?= $post['content'] ?>
                    </div>

                    <!-- Article Tags -->
                    <?php if (!empty($post['tags'])): ?>
                    <div class="article-tags">
                        <strong>Tags:</strong>
                        <?php foreach ($post['tags'] as $tag): ?>
                            <span class="tag"><?= e($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Share Buttons -->
                    <div class="share-buttons">
                        <span>Share this article:</span>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(post_url($post['slug'])) ?>&text=<?= urlencode($post['title']) ?>"
                           target="_blank" rel="noopener noreferrer" class="share-btn share-twitter" aria-label="Share on Twitter">Twitter</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(post_url($post['slug'])) ?>"
                           target="_blank" rel="noopener noreferrer" class="share-btn share-facebook" aria-label="Share on Facebook">Facebook</a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(post_url($post['slug'])) ?>&title=<?= urlencode($post['title']) ?>"
                           target="_blank" rel="noopener noreferrer" class="share-btn share-linkedin" aria-label="Share on LinkedIn">LinkedIn</a>
                    </div>

                    <!-- In-content Ad (after article) -->
                    <?php if (ADSENSE_PUBLISHER_ID && ADSENSE_SLOT_IN_CONTENT): ?>
                    <div class="ad-container ad-after-content">
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="<?= e(ADSENSE_PUBLISHER_ID) ?>"
                             data-ad-slot="<?= e(ADSENSE_SLOT_IN_CONTENT) ?>"
                             data-ad-format="auto"
                             data-full-width-responsive="true"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                    <?php endif; ?>

                    <!-- Related Posts -->
                    <?php if (!empty($related_posts)): ?>
                    <section class="related-posts">
                        <h2>You Might Also Like</h2>
                        <div class="related-grid">
                            <?php foreach ($related_posts as $rp): ?>
                            <article class="post-card post-card-small">
                                <a href="<?= e(post_url($rp['slug'])) ?>" class="post-card-image">
                                    <img src="<?= e($rp['image'] ?? '/assets/images/default-thumb.jpg') ?>"
                                         alt="<?= e($rp['image_alt'] ?? $rp['title']) ?>"
                                         loading="lazy"
                                         width="300" height="170">
                                </a>
                                <div class="post-card-body">
                                    <h3><a href="<?= e(post_url($rp['slug'])) ?>"><?= e($rp['title']) ?></a></h3>
                                    <span class="post-date"><?= date('M j, Y', strtotime($rp['date'])) ?></span>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <?php include TEMPLATES_PATH . '/sidebar.php'; ?>
            </div>
        </div>
    </article>
