<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Primary Meta Tags -->
    <title><?= e($meta_title) ?></title>
    <meta name="title" content="<?= e($meta_title) ?>">
    <meta name="description" content="<?= e($meta_description) ?>">
    <link rel="canonical" href="<?= e($canonical_url) ?>">

    <!-- Author & Keywords -->
    <?php if ($page === 'post' && isset($post)): ?>
    <meta name="author" content="<?= e($post['author']) ?>">
    <?php if (!empty($post['tags'])): ?>
    <meta name="keywords" content="<?= e(implode(', ', $post['tags'])) ?>">
    <?php endif; ?>
    <?php endif; ?>

    <?php
    $og_image = isset($post['image']) ? $post['image'] : '/assets/images/default-og.jpg';
    $og_image_url = (strpos($og_image, 'http') === 0) ? $og_image : SITE_URL . $og_image;
    $og_image_alt = isset($post['image_alt']) ? $post['image_alt'] : (isset($post['title']) ? $post['title'] : SITE_NAME);
    ?>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= ($page === 'post') ? 'article' : 'website' ?>">
    <meta property="og:url" content="<?= e($canonical_url) ?>">
    <meta property="og:title" content="<?= e($meta_title) ?>">
    <meta property="og:description" content="<?= e($meta_description) ?>">
    <meta property="og:image" content="<?= e($og_image_url) ?>">
    <meta property="og:image:width" content="<?= ($page === 'post') ? '800' : '1200' ?>">
    <meta property="og:image:height" content="<?= ($page === 'post') ? '450' : '630' ?>">
    <meta property="og:image:alt" content="<?= e($og_image_alt) ?>">
    <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
    <meta property="og:locale" content="en_US">
    <?php if ($page === 'post' && isset($post)): ?>
    <meta property="article:published_time" content="<?= date('c', strtotime($post['date'])) ?>">
    <meta property="article:modified_time" content="<?= date('c', strtotime($post['modified'] ?? $post['date'])) ?>">
    <meta property="article:author" content="<?= e($post['author']) ?>">
    <meta property="article:section" content="<?= e($post['category']) ?>">
    <?php if (!empty($post['tags'])): foreach ($post['tags'] as $tag): ?>
    <meta property="article:tag" content="<?= e($tag) ?>">
    <?php endforeach; endif; ?>
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= e($canonical_url) ?>">
    <meta name="twitter:title" content="<?= e($meta_title) ?>">
    <meta name="twitter:description" content="<?= e($meta_description) ?>">
    <meta name="twitter:image" content="<?= e($og_image_url) ?>">
    <meta name="twitter:image:alt" content="<?= e($og_image_alt) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
    <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">

    <!-- Preload featured image for article pages -->
    <?php if ($page === 'post' && isset($post) && !empty($post['image'])): ?>
    <link rel="preload" as="image" href="<?= e($post['image']) ?>">
    <?php endif; ?>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Schema.org Structured Data -->
    <?= generate_organization_schema() ?>
    <?php if ($page === 'home'): ?>
        <?= generate_website_schema() ?>
    <?php elseif ($page === 'post' && isset($post)): ?>
        <?= generate_article_schema($post) ?>
        <?= generate_breadcrumb_schema([
            ['name' => 'Home', 'url' => SITE_URL],
            ['name' => $post['category'], 'url' => category_url($post['category'])],
            ['name' => $post['title'], 'url' => post_url($post['slug'])],
        ]) ?>
    <?php endif; ?>

    <!-- Google Analytics -->
    <?php if (GOOGLE_ANALYTICS_ID): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GOOGLE_ANALYTICS_ID) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= e(GOOGLE_ANALYTICS_ID) ?>');
    </script>
    <?php endif; ?>

    <!-- Google AdSense -->
    <?php if (ADSENSE_PUBLISHER_ID): ?>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?= e(ADSENSE_PUBLISHER_ID) ?>" crossorigin="anonymous"></script>
    <?php endif; ?>
</head>
<body>
    <!-- Cookie Consent Banner (GDPR Compliance) -->
    <div id="cookie-consent" class="cookie-banner" style="display:none;">
        <div class="cookie-content">
            <p>We use cookies to improve your experience and serve relevant ads. By continuing, you agree to our <a href="/privacy-policy">Privacy Policy</a>.</p>
            <div class="cookie-actions">
                <button onclick="acceptCookies()" class="btn btn-primary btn-sm">Accept All</button>
                <button onclick="rejectCookies()" class="btn btn-outline btn-sm">Essential Only</button>
            </div>
        </div>
    </div>

    <!-- Skip to main content (Accessibility) -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Site Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <a href="/" class="logo" aria-label="<?= e(SITE_NAME) ?> Home">
                    <img src="/assets/images/logo.png" alt="<?= e(SITE_NAME) ?>" class="logo-img" width="180" height="67">
                </a>
                <nav class="main-nav" aria-label="Main navigation">
                    <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
                        <span class="hamburger"></span>
                    </button>
                    <ul class="nav-list">
                        <li><a href="/" <?= $page === 'home' ? 'aria-current="page"' : '' ?>>Home</a></li>
                        <?php
                        $categories = get_categories();
                        foreach (array_slice($categories, 0, 5) as $cat): ?>
                            <li><a href="<?= e(category_url($cat)) ?>" <?= ($page === 'category' && isset($category) && $category === strtolower($cat)) ? 'aria-current="page"' : '' ?>><?= e($cat) ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="/about" <?= $page === 'about' ? 'aria-current="page"' : '' ?>>About</a></li>
                        <li><a href="/contact" <?= $page === 'contact' ? 'aria-current="page"' : '' ?>>Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main id="main-content">
