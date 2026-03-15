<?php
/**
 * PopcornBytes - Site Configuration
 */

// Site settings
define('SITE_NAME', 'PopcornBytes');
define('SITE_TAGLINE', 'Your Plain-English Guide to Claude AI & the Future of Work');
define('SITE_URL', 'https://popcornbytes.com');
define('SITE_DESCRIPTION', 'PopcornBytes breaks down Claude AI, Anthropic updates, and AI productivity tools in plain English — no hype, no jargon. Real reviews, honest comparisons, practical guides.');

// Directory paths
define('BASE_PATH', dirname(__DIR__));
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('TEMPLATES_PATH', BASE_PATH . '/templates');
define('POSTS_PATH', BASE_PATH . '/posts');
define('ASSETS_PATH', BASE_PATH . '/assets');

// Pagination
define('POSTS_PER_PAGE', 10);

// Analytics & Monetization (replace with your actual IDs)
define('GOOGLE_ANALYTICS_ID', 'G-MT593HNTLP');
define('ADSENSE_PUBLISHER_ID', ''); // e.g., 'ca-pub-XXXXXXXXXXXXXXXX'
define('ADSENSE_SLOT_HEADER', ''); // Ad slot ID for header
define('ADSENSE_SLOT_SIDEBAR', ''); // Ad slot ID for sidebar
define('ADSENSE_SLOT_IN_CONTENT', ''); // Ad slot ID for in-content
define('ADSENSE_SLOT_FOOTER', ''); // Ad slot ID for footer

// Social media
define('SOCIAL_TWITTER', '');
define('SOCIAL_FACEBOOK', '');
define('SOCIAL_INSTAGRAM', '');

// Contact
define('CONTACT_EMAIL', 'hello@popcornbytes.com');

// PHP settings for Hostinger
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/error.log');

// Timezone
date_default_timezone_set('UTC');
