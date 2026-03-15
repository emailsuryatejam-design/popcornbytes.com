<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <!-- Static Pages -->
    <url>
        <loc><?= e(SITE_URL) ?>/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= e(SITE_URL) ?>/about</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?= e(SITE_URL) ?>/contact</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <!-- Categories -->
    <?php foreach (get_categories() as $cat): ?>
    <url>
        <loc><?= e(category_url($cat)) ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>

    <!-- Blog Posts -->
    <?php foreach (get_all_posts() as $post): ?>
    <url>
        <loc><?= e(post_url($post['slug'])) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($post['modified'] ?? $post['date'])) ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <?php if (!empty($post['image'])): ?>
        <image:image>
            <image:loc><?= e(SITE_URL . $post['image']) ?></image:loc>
            <image:title><?= e($post['title']) ?></image:title>
            <image:caption><?= e($post['image_alt'] ?? $post['title']) ?></image:caption>
        </image:image>
        <?php endif; ?>
    </url>
    <?php endforeach; ?>
</urlset>
