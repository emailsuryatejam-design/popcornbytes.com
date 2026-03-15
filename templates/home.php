    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Claude AI, Explained<br>In Plain English</h1>
            <p class="hero-subtitle">Honest reviews, practical guides, and straight-talking comparisons &mdash; so you can get more done with AI, starting today.</p>
        </div>
    </section>

    <!-- Header Ad Slot (above content, below fold) -->
    <?php if (ADSENSE_PUBLISHER_ID && ADSENSE_SLOT_HEADER): ?>
    <div class="ad-container ad-header">
        <div class="container">
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="<?= e(ADSENSE_PUBLISHER_ID) ?>"
                 data-ad-slot="<?= e(ADSENSE_SLOT_HEADER) ?>"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>
    </div>
    <?php endif; ?>

    <!-- Featured / Latest Posts -->
    <section class="posts-section">
        <div class="container">
            <div class="content-layout">
                <div class="posts-main">
                    <?php if (!empty($pagination['posts'])): ?>
                        <!-- Featured post (first post on first page) -->
                        <?php if ($pagination['current_page'] === 1): ?>
                            <?php $featured = $pagination['posts'][0]; ?>
                            <article class="post-card post-card-featured">
                                <a href="<?= e(post_url($featured['slug'])) ?>" class="post-card-image">
                                    <img src="<?= e($featured['image'] ?? '/assets/images/default-thumb.jpg') ?>"
                                         alt="<?= e($featured['image_alt'] ?? $featured['title']) ?>"
                                         loading="eager"
                                         width="800" height="450">
                                </a>
                                <div class="post-card-body">
                                    <span class="post-category"><a href="<?= e(category_url($featured['category'])) ?>"><?= e($featured['category']) ?></a></span>
                                    <h2><a href="<?= e(post_url($featured['slug'])) ?>"><?= e($featured['title']) ?></a></h2>
                                    <p><?= e(truncate($featured['excerpt'], 200)) ?></p>
                                    <div class="post-meta">
                                        <span class="post-date"><?= date('M j, Y', strtotime($featured['date'])) ?></span>
                                        <span class="post-reading-time"><?= reading_time($featured['content']) ?> min read</span>
                                    </div>
                                </div>
                            </article>
                        <?php endif; ?>

                        <!-- Post Grid -->
                        <div class="post-grid">
                            <?php
                            $start = ($pagination['current_page'] === 1) ? 1 : 0;
                            $ad_counter = 0;
                            for ($i = $start; $i < count($pagination['posts']); $i++):
                                $p = $pagination['posts'][$i];
                                $ad_counter++;
                            ?>
                                <article class="post-card">
                                    <a href="<?= e(post_url($p['slug'])) ?>" class="post-card-image">
                                        <img src="<?= e($p['image'] ?? '/assets/images/default-thumb.jpg') ?>"
                                             alt="<?= e($p['image_alt'] ?? $p['title']) ?>"
                                             loading="lazy"
                                             width="400" height="225">
                                    </a>
                                    <div class="post-card-body">
                                        <span class="post-category"><a href="<?= e(category_url($p['category'])) ?>"><?= e($p['category']) ?></a></span>
                                        <h2><a href="<?= e(post_url($p['slug'])) ?>"><?= e($p['title']) ?></a></h2>
                                        <p><?= e(truncate($p['excerpt'], 120)) ?></p>
                                        <div class="post-meta">
                                            <span class="post-date"><?= date('M j, Y', strtotime($p['date'])) ?></span>
                                            <span class="post-reading-time"><?= reading_time($p['content']) ?> min read</span>
                                        </div>
                                    </div>
                                </article>

                                <!-- In-feed ad every 4 posts -->
                                <?php if ($ad_counter % 4 === 0 && ADSENSE_PUBLISHER_ID && ADSENSE_SLOT_IN_CONTENT): ?>
                                <div class="ad-container ad-in-feed">
                                    <ins class="adsbygoogle"
                                         style="display:block"
                                         data-ad-client="<?= e(ADSENSE_PUBLISHER_ID) ?>"
                                         data-ad-slot="<?= e(ADSENSE_SLOT_IN_CONTENT) ?>"
                                         data-ad-format="fluid"
                                         data-ad-layout-key="-6t+ed+2i-1n-4w"></ins>
                                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                                </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($pagination['total_pages'] > 1): ?>
                        <nav class="pagination" aria-label="Page navigation">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="btn btn-outline" rel="prev">&larr; Newer Posts</a>
                            <?php endif; ?>

                            <span class="pagination-info">
                                Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?>
                            </span>

                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="btn btn-outline" rel="next">Older Posts &rarr;</a>
                            <?php endif; ?>
                        </nav>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="empty-state">
                            <h2>Posts coming soon!</h2>
                            <p>We&rsquo;re working on our first guides. Subscribe below to be the first to know.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <?php include TEMPLATES_PATH . '/sidebar.php'; ?>
            </div>
        </div>
    </section>
