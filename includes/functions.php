<?php
/**
 * PopcornBytes - Core Functions
 */

require_once __DIR__ . '/config.php';

/**
 * Load all blog posts from the posts directory, sorted by date descending.
 */
function get_all_posts(): array {
    $posts = [];
    $files = glob(POSTS_PATH . '/*.php');

    foreach ($files as $file) {
        $post = include $file;
        if (is_array($post) && isset($post['slug'])) {
            $post['file'] = basename($file);
            $posts[] = $post;
        }
    }

    usort($posts, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return $posts;
}

/**
 * Get a single post by its slug.
 */
function get_post_by_slug(string $slug): ?array {
    $posts = get_all_posts();
    foreach ($posts as $post) {
        if ($post['slug'] === $slug) {
            return $post;
        }
    }
    return null;
}

/**
 * Get posts by category.
 */
function get_posts_by_category(string $category): array {
    $posts = get_all_posts();
    return array_filter($posts, function ($post) use ($category) {
        return strtolower($post['category']) === strtolower($category);
    });
}

/**
 * Paginate an array of posts.
 */
function paginate_posts(array $posts, int $page = 1): array {
    $total = count($posts);
    $total_pages = max(1, ceil($total / POSTS_PER_PAGE));
    $page = max(1, min($page, $total_pages));
    $offset = ($page - 1) * POSTS_PER_PAGE;

    return [
        'posts' => array_slice($posts, $offset, POSTS_PER_PAGE),
        'current_page' => $page,
        'total_pages' => $total_pages,
        'total_posts' => $total,
    ];
}

/**
 * Get all unique categories from posts.
 */
function get_categories(): array {
    $posts = get_all_posts();
    $categories = [];
    foreach ($posts as $post) {
        $cat = $post['category'] ?? 'Uncategorized';
        if (!in_array($cat, $categories)) {
            $categories[] = $cat;
        }
    }
    sort($categories);
    return $categories;
}

/**
 * Generate a URL for a post.
 */
function post_url(string $slug): string {
    return SITE_URL . '/post/' . $slug;
}

/**
 * Generate a URL for a category.
 */
function category_url(string $category): string {
    return SITE_URL . '/category/' . urlencode(strtolower($category));
}

/**
 * Truncate text to a given length at a word boundary.
 */
function truncate(string $text, int $length = 160): string {
    if (strlen($text) <= $length) {
        return $text;
    }
    $truncated = substr($text, 0, $length);
    $last_space = strrpos($truncated, ' ');
    if ($last_space !== false) {
        $truncated = substr($truncated, 0, $last_space);
    }
    return $truncated . '...';
}

/**
 * Sanitize output for HTML.
 */
function e(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Calculate estimated reading time.
 */
function reading_time(string $content): int {
    $word_count = str_word_count(strip_tags($content));
    return max(1, (int) ceil($word_count / 238));
}

/**
 * Generate JSON-LD structured data for a blog post (BlogPosting schema).
 */
function generate_article_schema(array $post): string {
    $image_url = isset($post['image']) ? $post['image'] : '/assets/images/default-og.jpg';
    if (strpos($image_url, 'http') !== 0) {
        $image_url = SITE_URL . $image_url;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post['title'],
        'description' => $post['excerpt'],
        'image' => [
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => 800,
            'height' => 450,
            'caption' => $post['image_alt'] ?? $post['title'],
        ],
        'author' => [
            '@type' => 'Person',
            'name' => $post['author'],
            'url' => SITE_URL . '/about',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => SITE_NAME,
            'url' => SITE_URL,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => SITE_URL . '/assets/images/logo.svg',
            ],
        ],
        'datePublished' => date('c', strtotime($post['date'])),
        'dateModified' => date('c', strtotime($post['modified'] ?? $post['date'])),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => post_url($post['slug']),
        ],
        'wordCount' => str_word_count(strip_tags($post['content'] ?? '')),
        'articleSection' => $post['category'] ?? 'General',
        'keywords' => implode(', ', $post['tags'] ?? []),
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Generate JSON-LD for the website (WebSite schema).
 */
function generate_website_schema(): string {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => SITE_NAME,
        'url' => SITE_URL,
        'description' => SITE_DESCRIPTION,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => SITE_URL . '/search?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Generate breadcrumb JSON-LD.
 */
function generate_breadcrumb_schema(array $items): string {
    $list_items = [];
    foreach ($items as $i => $item) {
        $list_items[] = [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $item['name'],
            'item' => $item['url'],
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $list_items,
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Generate JSON-LD for Organization schema (sitewide).
 */
function generate_organization_schema(): string {
    $same_as = array_filter([
        SOCIAL_TWITTER ? 'https://twitter.com/' . SOCIAL_TWITTER : '',
        SOCIAL_FACEBOOK ? 'https://facebook.com/' . SOCIAL_FACEBOOK : '',
        SOCIAL_INSTAGRAM ? 'https://instagram.com/' . SOCIAL_INSTAGRAM : '',
    ]);

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => SITE_NAME,
        'url' => SITE_URL,
        'logo' => SITE_URL . '/assets/images/logo.png',
        'description' => SITE_DESCRIPTION,
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'email' => CONTACT_EMAIL,
            'contactType' => 'customer service',
        ],
    ];

    if (!empty($same_as)) {
        $schema['sameAs'] = array_values($same_as);
    }

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Get related posts based on category (excluding the current post).
 */
function get_related_posts(array $current_post, int $limit = 3): array {
    $posts = get_posts_by_category($current_post['category']);
    $related = array_filter($posts, function ($p) use ($current_post) {
        return $p['slug'] !== $current_post['slug'];
    });
    return array_slice(array_values($related), 0, $limit);
}

/**
 * Simple router - get the current route from the URL.
 */
function get_route(): array {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $uri = parse_url($uri, PHP_URL_PATH);
    $uri = rtrim($uri, '/') ?: '/';

    if ($uri === '/') {
        return ['page' => 'home'];
    }

    if (preg_match('#^/post/([a-z0-9\-]+)$#', $uri, $matches)) {
        return ['page' => 'post', 'slug' => $matches[1]];
    }

    if (preg_match('#^/category/([a-z0-9\-\%]+)$#', $uri, $matches)) {
        return ['page' => 'category', 'category' => urldecode($matches[1])];
    }

    if ($uri === '/about') {
        return ['page' => 'about'];
    }

    if ($uri === '/contact') {
        return ['page' => 'contact'];
    }

    if ($uri === '/privacy-policy') {
        return ['page' => 'privacy'];
    }

    if ($uri === '/sitemap.xml') {
        return ['page' => 'sitemap'];
    }

    return ['page' => '404'];
}
