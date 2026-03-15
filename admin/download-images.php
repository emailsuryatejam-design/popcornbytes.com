<?php
/**
 * PopcornBytes - Unsplash Image Downloader
 *
 * Downloads curated Unsplash photos for each blog post.
 * Uses specific hand-picked photo IDs for best results.
 *
 * You need an Unsplash Access Key. Get one free at:
 *   https://unsplash.com/oauth/applications
 *
 * Usage:
 *   php admin/download-images.php YOUR_UNSPLASH_ACCESS_KEY
 *
 * Or set the environment variable:
 *   UNSPLASH_ACCESS_KEY=your_key php admin/download-images.php
 */

$access_key = $argv[1] ?? getenv('UNSPLASH_ACCESS_KEY');

if (!$access_key) {
    echo "Usage: php admin/download-images.php YOUR_UNSPLASH_ACCESS_KEY\n";
    echo "Or:    UNSPLASH_ACCESS_KEY=your_key php admin/download-images.php\n\n";
    echo "Get a free access key at: https://unsplash.com/oauth/applications\n";
    exit(1);
}

$base_dir = dirname(__DIR__);
$images_dir = $base_dir . '/assets/images/posts';

if (!is_dir($images_dir)) {
    mkdir($images_dir, 0755, true);
    echo "Created directory: $images_dir\n";
}

// Curated Unsplash photo IDs for each blog post
// Each entry: slug => [photo_id, description, photographer]
$photos = [
    '100-things-we-lost-to-the-internet' => [
        'id' => 'PAyhLunLfxE',
        'desc' => 'Stack of old televisions by Planet Volumes',
    ],
    'why-nobody-memorizes-phone-numbers' => [
        'id' => '8gWEAAXJjtI',
        'desc' => 'Black rotary dial phone by Quino Al',
    ],
    'the-death-of-getting-lost' => [
        'id' => 'o0l-M8W_7wA',
        'desc' => 'Compass on map book page by Chris Lawton',
    ],
    'rise-and-fall-of-the-away-message' => [
        'id' => 'BF9I4IwF6xs',
        'desc' => 'Old computer with heart on screen by Allison Saeng',
    ],
    'when-boredom-was-a-feature' => [
        'id' => 'B7wNw1GRv00',
        'desc' => 'Person sitting beside window by Souvik',
    ],
    'the-lost-art-of-the-mix-tape' => [
        'id' => '1VMFqrFUYvE',
        'desc' => 'Black cassette tape on white table by henry perks',
    ],
    'when-downloading-a-song-took-all-night' => [
        'id' => 'LR_wX_klOPM',
        'desc' => 'White modem turned on by Stephen Phillips',
    ],
    'the-forgotten-joy-of-saturday-morning-cartoons' => [
        'id' => 'yKPj4oi9m74',
        'desc' => 'Old fashioned television set by Pawel Kadysz',
    ],
    '21-things-the-internet-quietly-killed' => [
        'id' => 'J6Qn9sE4aKM',
        'desc' => 'Person using smartphone and laptop by Yogas Design',
    ],
    'anemoia-why-gen-z-misses-a-world-they-never-lived-in' => [
        'id' => 'Hys5qHaDbZQ',
        'desc' => 'VHS tape lot by Chris Lawton',
    ],
    'the-dumb-phone-revolution' => [
        'id' => 'MBKavovwnw8',
        'desc' => 'Yellow Nokia phone by Vinicius Amano',
    ],
    'your-brain-on-doomscrolling' => [
        'id' => 'GWkioAj5aB4',
        'desc' => 'Person using smartphone in dark by Christian Wiediger',
    ],
    'jobs-ai-still-cant-do' => [
        'id' => 'aO4c6o4H2MI',
        'desc' => 'Person holding power tool in workshop by Adi Goldstein',
    ],
    'how-dating-apps-killed-the-meet-cute' => [
        'id' => 'Cc6gqX7GLMo',
        'desc' => 'Friends talking at a cafe by Curated Lifestyle',
    ],
    'the-analog-revival' => [
        'id' => 'bsLXJsucvxc',
        'desc' => 'Black vinyl record on turntable by Ivan Dorofeev',
    ],
    'digital-amnesia-why-you-cant-remember-anything' => [
        'id' => 'L8uRhNnkrM0',
        'desc' => 'Abstract flowing neural lines by Zoha Gohar',
    ],
    'subscription-economy-ate-your-wallet' => [
        'id' => 'oqkmdriPiHM',
        'desc' => 'Wallet with credit cards by PiggyBank',
    ],
    'the-death-of-couch-co-op' => [
        'id' => 'dwmD4XxbE1g',
        'desc' => 'Two people on couch playing video game by Oleg Ivanov',
    ],
    'denmark-banned-screens-in-classrooms' => [
        'id' => 'GxB6Pbi4Jzg',
        'desc' => 'Teacher at chalkboard with children by Austrian National Library',
    ],
    'a-sense-of-hope-we-cant-download-back' => [
        'id' => 'oK-IBmy9kQg',
        'desc' => 'Horizon at sunrise by ekrem osmanoglu',
    ],
    // Default OG image for social sharing
    'default-og' => [
        'id' => 'o0IHdkYfx0A',
        'desc' => 'Person holding popcorn and drink by Andrej Lisakov',
    ],
];

echo "PopcornBytes - Unsplash Image Downloader\n";
echo "=========================================\n";
echo "Downloading " . count($photos) . " curated images...\n\n";

$downloaded = 0;
$skipped = 0;
$failed = 0;
$attribution_lines = [];

foreach ($photos as $slug => $config) {
    $filename = $slug . '.jpg';
    $filepath = $images_dir . '/' . $filename;

    // Skip if image already exists
    if (file_exists($filepath) && filesize($filepath) > 1000) {
        echo "  [SKIP] $filename (already exists)\n";
        $skipped++;
        continue;
    }

    $photo_id = $config['id'];
    echo "  [GET]  $slug ($photo_id)... ";

    // Fetch photo data from Unsplash API
    $api_url = "https://api.unsplash.com/photos/$photo_id";
    $ctx = stream_context_create([
        'http' => [
            'header' => "Authorization: Client-ID $access_key\r\nAccept-Version: v1\r\n",
            'timeout' => 30,
        ],
    ]);

    $response = @file_get_contents($api_url, false, $ctx);
    if (!$response) {
        echo "API ERROR\n";
        $failed++;
        continue;
    }

    $photo = json_decode($response, true);
    if (!$photo || !isset($photo['urls']['raw'])) {
        echo "INVALID RESPONSE\n";
        $failed++;
        continue;
    }

    $photographer = $photo['user']['name'] ?? 'Unknown';
    $photographer_url = $photo['user']['links']['html'] ?? '';
    $photo_url = $photo['links']['html'] ?? '';

    // Download at 800x450 (16:9 blog thumbnail) or 1200x630 (OG image)
    if ($slug === 'default-og') {
        $image_url = $photo['urls']['raw'] . '&w=1200&h=630&fit=crop&auto=format&q=80';
    } else {
        $image_url = $photo['urls']['raw'] . '&w=800&h=450&fit=crop&auto=format&q=80';
    }

    $image_data = @file_get_contents($image_url);
    if (!$image_data || strlen($image_data) < 1000) {
        echo "DOWNLOAD FAILED\n";
        $failed++;
        continue;
    }

    file_put_contents($filepath, $image_data);
    $size_kb = round(strlen($image_data) / 1024);
    echo "OK ({$size_kb} KB) - {$photographer}\n";

    // Trigger download endpoint (required by Unsplash API guidelines)
    if (isset($photo['links']['download_location'])) {
        $dl_url = $photo['links']['download_location'] . "?client_id=$access_key";
        @file_get_contents($dl_url, false, $ctx);
    }

    // Save attribution
    $attribution_lines[] = "- **{$slug}.jpg**: Photo by [{$photographer}]({$photographer_url}) on [Unsplash]({$photo_url})";

    $downloaded++;
    usleep(300000); // 0.3s rate limit
}

// Write attribution file
if (!empty($attribution_lines)) {
    $attribution_content = "# Image Attribution\n\n";
    $attribution_content .= "All images sourced from [Unsplash](https://unsplash.com) under the Unsplash License.\n\n";
    $attribution_content .= implode("\n", $attribution_lines) . "\n";
    file_put_contents($images_dir . '/ATTRIBUTION.md', $attribution_content);
}

// Copy default-og to the main images directory
$og_src = $images_dir . '/default-og.jpg';
$og_dst = $base_dir . '/assets/images/default-og.jpg';
if (file_exists($og_src) && filesize($og_src) > 1000) {
    copy($og_src, $og_dst);
    echo "\n  [COPY] default-og.jpg -> assets/images/default-og.jpg\n";
}

echo "\n=========================================\n";
echo "Done! Downloaded: $downloaded | Skipped: $skipped | Failed: $failed\n\n";

if ($downloaded > 0) {
    echo "Attribution saved: assets/images/posts/ATTRIBUTION.md\n";
    echo "Next: run 'php admin/update-image-paths.php' to update post files.\n";
}
