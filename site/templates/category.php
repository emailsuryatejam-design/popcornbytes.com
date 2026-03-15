    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <ol>
                <li><a href="/">Home</a></li>
                <li aria-current="page"><?= e(ucfirst($category)) ?></li>
            </ol>
        </div>
    </nav>

    <section class="posts-section">
        <div class="container">
            <header class="section-header">
                <h1>Category: <?= e(ucfirst($category)) ?></h1>
                <p><?= $pagination['total_posts'] ?> article<?= $pagination['total_posts'] !== 1 ? 's' : '' ?> in this category</p>
            </header>

            <div class="content-layout">
                <div class="posts-main">
                    <?php if (!empty($pagination['posts'])): ?>
                    <div class="post-grid">
                        <?php foreach ($pagination['posts'] as $p): ?>
                        <article class="post-card">
                            <a href="<?= e(post_url($p['slug'])) ?>" class="post-card-image">
                                <img src="<?= e($p['image'] ?? '/assets/images/default-thumb.jpg') ?>"
                                     alt="<?= e($p['title']) ?>"
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
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                    <nav class="pagination" aria-label="Page navigation">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="btn btn-outline" rel="prev">&larr; Newer</a>
                        <?php endif; ?>
                        <span class="pagination-info">Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?></span>
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="btn btn-outline" rel="next">Older &rarr;</a>
                        <?php endif; ?>
                    </nav>
                    <?php endif; ?>

                    <?php else: ?>
                    <div class="empty-state">
                        <h2>No posts in this category yet</h2>
                        <p>Check back soon or <a href="/">browse all posts</a>.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <?php include TEMPLATES_PATH . '/sidebar.php'; ?>
            </div>
        </div>
    </section>
