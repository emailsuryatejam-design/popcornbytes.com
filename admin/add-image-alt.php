<?php
/**
 * Add SEO-optimized image_alt field to all post files.
 */

$base_dir = dirname(__DIR__);
$posts_dir = $base_dir . '/posts';

// Descriptive alt text for each post's featured image (SEO-optimized)
$image_alts = [
    '100-things-we-lost-to-the-internet' => 'Stack of vintage televisions symbolizing technology we lost to the internet',
    'why-nobody-memorizes-phone-numbers' => 'Black rotary dial telephone representing the lost habit of memorizing phone numbers',
    'the-death-of-getting-lost' => 'Compass resting on an open map book symbolizing navigation before GPS',
    'rise-and-fall-of-the-away-message' => 'Retro computer with heart on screen representing AIM away messages and early internet communication',
    'when-boredom-was-a-feature' => 'Person sitting by a window daydreaming, representing the lost art of boredom and idle creativity',
    'the-lost-art-of-the-mix-tape' => 'Vintage cassette tape on white surface representing the personal art of making mix tapes',
    'when-downloading-a-song-took-all-night' => 'White dial-up modem representing the slow internet era of Napster and MP3 downloads',
    'the-forgotten-joy-of-saturday-morning-cartoons' => 'Old-fashioned television set evoking the nostalgia of Saturday morning cartoon rituals',
    '21-things-the-internet-quietly-killed' => 'Person using smartphone and laptop illustrating how digital devices replaced analog traditions',
    'anemoia-why-gen-z-misses-a-world-they-never-lived-in' => 'Collection of VHS tapes representing nostalgic media that Gen Z never experienced firsthand',
    'the-dumb-phone-revolution' => 'Classic yellow Nokia phone symbolizing the dumb phone movement and digital minimalism',
    'your-brain-on-doomscrolling' => 'Person scrolling smartphone in the dark illustrating the effects of doomscrolling on mental health',
    'jobs-ai-still-cant-do' => 'Craftsperson using power tools in workshop representing human skills that AI cannot replicate',
    'how-dating-apps-killed-the-meet-cute' => 'Friends having conversation at a cafe representing organic human connection before dating apps',
    'the-analog-revival' => 'Vinyl record spinning on turntable representing the analog revival trend among young generations',
    'digital-amnesia-why-you-cant-remember-anything' => 'Abstract neural network lines representing digital amnesia and cognitive offloading to technology',
    'subscription-economy-ate-your-wallet' => 'Open wallet with credit cards illustrating subscription fatigue and recurring software costs',
    'the-death-of-couch-co-op' => 'Two people playing video games on a couch representing the lost tradition of local multiplayer gaming',
    'denmark-banned-screens-in-classrooms' => 'Teacher at chalkboard with children in a classroom without digital screens',
    'a-sense-of-hope-we-cant-download-back' => 'Sunrise over the horizon symbolizing lost optimism about the future in the digital age',
];

echo "Adding image_alt to post files\n";
echo "==============================\n\n";

$updated = 0;
$skipped = 0;

foreach ($image_alts as $slug => $alt_text) {
    $file = $posts_dir . '/' . $slug . '.php';
    if (!file_exists($file)) {
        echo "[SKIP] $slug (file not found)\n";
        $skipped++;
        continue;
    }

    $content = file_get_contents($file);

    // Check if image_alt already exists
    if (strpos($content, "'image_alt'") !== false) {
        echo "[SKIP] $slug (image_alt already exists)\n";
        $skipped++;
        continue;
    }

    // Add image_alt after the image line
    $alt_escaped = str_replace("'", "\\'", $alt_text);
    $new_content = preg_replace(
        "/('image'\s*=>\s*'[^']*'),/",
        "$1,\n    'image_alt' => '$alt_escaped',",
        $content
    );

    if ($new_content !== $content) {
        file_put_contents($file, $new_content);
        echo "[ADDED] $slug\n";
        $updated++;
    } else {
        echo "[FAIL] $slug (pattern not matched)\n";
        $skipped++;
    }
}

echo "\n==============================\n";
echo "Done! Added: $updated | Skipped: $skipped\n";
