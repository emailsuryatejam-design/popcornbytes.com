<?php
/**
 * PopcornBytes - Update Image Paths
 *
 * Run this after download-images.php to automatically update all post files
 * to point to the downloaded Unsplash images.
 *
 * Usage: php admin/update-image-paths.php
 */

$base_dir = dirname(__DIR__);
$posts_dir = $base_dir . '/posts';
$images_dir = $base_dir . '/assets/images/posts';

echo "PopcornBytes - Updating Image Paths\n";
echo "====================================\n\n";

$updated = 0;
$skipped = 0;

$files = glob($posts_dir . '/*.php');
foreach ($files as $file) {
    $slug = basename($file, '.php');
    $image_path = $images_dir . '/' . $slug . '.jpg';
    $web_path = '/assets/images/posts/' . $slug . '.jpg';

    if (!file_exists($image_path) || filesize($image_path) < 1000) {
        echo "[SKIP] $slug (no downloaded image found)\n";
        $skipped++;
        continue;
    }

    $content = file_get_contents($file);

    // Replace the image path
    $new_content = preg_replace(
        "/'image'\s*=>\s*'[^']*'/",
        "'image' => '$web_path'",
        $content
    );

    if ($new_content !== $content) {
        file_put_contents($file, $new_content);
        echo "[UPDATED] $slug -> $web_path\n";
        $updated++;
    } else {
        echo "[SKIP] $slug (already up to date)\n";
        $skipped++;
    }
}

echo "\n====================================\n";
echo "Done! Updated: $updated, Skipped: $skipped\n";
