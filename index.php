<?php
/**
 * PopcornBytes - Main Entry Point
 * All requests are routed through this file via .htaccess
 */

require_once __DIR__ . '/includes/functions.php';

$route = get_route();
$page = $route['page'];

switch ($page) {
    case 'home':
        $all_posts = get_all_posts();
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $pagination = paginate_posts($all_posts, $current_page);
        $meta_title = SITE_NAME . ' - ' . SITE_TAGLINE;
        $meta_description = SITE_DESCRIPTION;
        $canonical_url = SITE_URL;
        include TEMPLATES_PATH . '/header.php';
        include TEMPLATES_PATH . '/home.php';
        include TEMPLATES_PATH . '/footer.php';
        break;

    case 'post':
        $post = get_post_by_slug($route['slug']);
        if (!$post) {
            http_response_code(404);
            $meta_title = 'Page Not Found - ' . SITE_NAME;
            $meta_description = 'The page you are looking for does not exist.';
            $canonical_url = SITE_URL;
            include TEMPLATES_PATH . '/header.php';
            include TEMPLATES_PATH . '/404.php';
            include TEMPLATES_PATH . '/footer.php';
            break;
        }
        $meta_title = $post['title'] . ' - ' . SITE_NAME;
        $meta_description = truncate($post['excerpt'], 160);
        $canonical_url = post_url($post['slug']);
        $related_posts = get_related_posts($post);
        include TEMPLATES_PATH . '/header.php';
        include TEMPLATES_PATH . '/post.php';
        include TEMPLATES_PATH . '/footer.php';
        break;

    case 'category':
        $category = $route['category'];
        $cat_posts = array_values(get_posts_by_category($category));
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $pagination = paginate_posts($cat_posts, $current_page);
        $meta_title = ucfirst($category) . ' - ' . SITE_NAME;
        $meta_description = 'Browse all articles in the ' . ucfirst($category) . ' category on ' . SITE_NAME . '.';
        $canonical_url = category_url($category);
        include TEMPLATES_PATH . '/header.php';
        include TEMPLATES_PATH . '/category.php';
        include TEMPLATES_PATH . '/footer.php';
        break;

    case 'about':
        $meta_title = 'About - ' . SITE_NAME;
        $meta_description = 'Learn about PopcornBytes, a blog serving bite-sized stories about how the internet changed everything.';
        $canonical_url = SITE_URL . '/about';
        include TEMPLATES_PATH . '/header.php';
        include TEMPLATES_PATH . '/about.php';
        include TEMPLATES_PATH . '/footer.php';
        break;

    case 'contact':
        $meta_title = 'Contact - ' . SITE_NAME;
        $meta_description = 'Get in touch with PopcornBytes. We would love to hear from you.';
        $canonical_url = SITE_URL . '/contact';
        include TEMPLATES_PATH . '/header.php';
        include TEMPLATES_PATH . '/contact.php';
        include TEMPLATES_PATH . '/footer.php';
        break;

    case 'privacy':
        $meta_title = 'Privacy Policy - ' . SITE_NAME;
        $meta_description = 'Privacy Policy for PopcornBytes. Learn how we collect, use, and protect your data.';
        $canonical_url = SITE_URL . '/privacy-policy';
        include TEMPLATES_PATH . '/header.php';
        include TEMPLATES_PATH . '/privacy.php';
        include TEMPLATES_PATH . '/footer.php';
        break;

    case 'sitemap':
        header('Content-Type: application/xml; charset=UTF-8');
        include TEMPLATES_PATH . '/sitemap.php';
        break;

    default:
        http_response_code(404);
        $meta_title = 'Page Not Found - ' . SITE_NAME;
        $meta_description = 'The page you are looking for does not exist.';
        $canonical_url = SITE_URL;
        include TEMPLATES_PATH . '/header.php';
        include TEMPLATES_PATH . '/404.php';
        include TEMPLATES_PATH . '/footer.php';
        break;
}
