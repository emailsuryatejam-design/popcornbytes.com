<?php
/**
 * PopcornBytes - Generate SVG Placeholder Thumbnails
 *
 * Creates attractive gradient SVG thumbnails for each blog post.
 * These serve as fallbacks until real Unsplash images are downloaded.
 *
 * Usage: php admin/generate-placeholders.php
 */

$base_dir = dirname(__DIR__);
$posts_dir = $base_dir . '/posts';
$output_dir = $base_dir . '/assets/images/posts';

if (!is_dir($output_dir)) {
    mkdir($output_dir, 0755, true);
}

// Color palettes (gradient start, gradient end, accent)
$palettes = [
    ['#1d3557', '#457b9d', '#a8dadc'],
    ['#e63946', '#f4a261', '#f1faee'],
    ['#264653', '#2a9d8f', '#e9c46a'],
    ['#6d6875', '#b5838d', '#ffcdb2'],
    ['#003049', '#d62828', '#fcbf49'],
    ['#2b2d42', '#8d99ae', '#edf2f4'],
    ['#606c38', '#283618', '#dda15e'],
    ['#0077b6', '#023e8a', '#90e0ef'],
    ['#7209b7', '#3a0ca3', '#f72585'],
    ['#1b4332', '#40916c', '#b7e4c7'],
];

// Icons (simple SVG path data) mapped to categories
$icons = [
    'Nostalgia' => '<circle cx="400" cy="200" r="40" fill="none" stroke="%s" stroke-width="3" opacity="0.4"/><path d="M385 195 L395 185 L410 200 L420 190 L430 210 L370 210 Z" fill="%s" opacity="0.3"/>',
    'Technology' => '<rect x="375" y="175" width="50" height="35" rx="3" fill="none" stroke="%s" stroke-width="3" opacity="0.4"/><rect x="380" y="180" width="40" height="22" fill="%s" opacity="0.15"/><rect x="390" y="215" width="20" height="3" fill="%s" opacity="0.3"/>',
    'Internet Culture' => '<circle cx="400" cy="195" r="30" fill="none" stroke="%s" stroke-width="3" opacity="0.4"/><path d="M370 195 L430 195 M400 165 L400 225 M374 178 Q400 195 426 178 M374 212 Q400 195 426 212" fill="none" stroke="%s" stroke-width="1.5" opacity="0.25"/>',
    'Lifestyle' => '<path d="M400 175 Q415 185 415 200 Q415 215 400 225 Q385 215 385 200 Q385 185 400 175 Z" fill="%s" opacity="0.2" stroke="%s" stroke-width="2" opacity="0.4"/>',
    'default' => '<circle cx="400" cy="195" r="25" fill="%s" opacity="0.15"/><circle cx="400" cy="195" r="15" fill="%s" opacity="0.1"/>',
];

echo "PopcornBytes - Generating Placeholder Thumbnails\n";
echo "=================================================\n\n";

$files = glob($posts_dir . '/*.php');
$count = 0;

foreach ($files as $i => $file) {
    $post = include $file;
    if (!is_array($post) || !isset($post['slug'])) continue;

    $slug = $post['slug'];
    $title = $post['title'];
    $category = $post['category'] ?? 'default';
    $palette = $palettes[$i % count($palettes)];

    // Get icon SVG for this category
    $icon_template = $icons[$category] ?? $icons['default'];
    $icon_svg = sprintf($icon_template, $palette[2], $palette[2], $palette[2]);

    // Word-wrap the title for the SVG
    $words = explode(' ', $title);
    $lines = [];
    $current_line = '';
    foreach ($words as $word) {
        if (strlen($current_line . ' ' . $word) > 28) {
            $lines[] = trim($current_line);
            $current_line = $word;
        } else {
            $current_line .= ' ' . $word;
        }
    }
    $lines[] = trim($current_line);
    $lines = array_slice($lines, 0, 3); // Max 3 lines

    $title_y_start = 265;
    $title_svg = '';
    foreach ($lines as $j => $line) {
        $y = $title_y_start + ($j * 32);
        $escaped = htmlspecialchars($line, ENT_XML1, 'UTF-8');
        $title_svg .= "<text x=\"400\" y=\"$y\" text-anchor=\"middle\" font-family=\"Georgia, serif\" font-size=\"24\" font-weight=\"bold\" fill=\"white\" opacity=\"0.95\">$escaped</text>\n";
    }

    $category_escaped = htmlspecialchars(strtoupper($category), ENT_XML1, 'UTF-8');

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$palette[0]}"/>
      <stop offset="100%" style="stop-color:{$palette[1]}"/>
    </linearGradient>
  </defs>
  <rect width="800" height="450" fill="url(#bg)"/>
  <!-- Decorative circles -->
  <circle cx="650" cy="80" r="120" fill="white" opacity="0.03"/>
  <circle cx="150" cy="380" r="80" fill="white" opacity="0.03"/>
  <!-- Category icon -->
  $icon_svg
  <!-- Category label -->
  <text x="400" y="240" text-anchor="middle" font-family="Inter, sans-serif" font-size="12" font-weight="600" fill="{$palette[2]}" letter-spacing="0.15em" text-transform="uppercase">$category_escaped</text>
  <!-- Title -->
  $title_svg
  <!-- Brand -->
  <text x="400" y="420" text-anchor="middle" font-family="Inter, sans-serif" font-size="11" fill="white" opacity="0.4">POPCORNBYTES.COM</text>
</svg>
SVG;

    $svg_path = $output_dir . '/' . $slug . '.svg';
    file_put_contents($svg_path, $svg);
    echo "[OK] $slug.svg\n";
    $count++;
}

// Generate default OG image
$og_svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 630" width="1200" height="630">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#1d3557"/>
      <stop offset="100%" style="stop-color:#457b9d"/>
    </linearGradient>
  </defs>
  <rect width="1200" height="630" fill="url(#bg)"/>
  <circle cx="900" cy="120" r="200" fill="white" opacity="0.03"/>
  <circle cx="300" cy="500" r="150" fill="white" opacity="0.03"/>
  <text x="600" y="260" text-anchor="middle" font-family="Georgia, serif" font-size="64" font-weight="bold" fill="white">PopCornBytes</text>
  <line x1="450" y1="290" x2="750" y2="290" stroke="#e63946" stroke-width="3"/>
  <text x="600" y="340" text-anchor="middle" font-family="Inter, sans-serif" font-size="22" fill="white" opacity="0.8">Byte-Sized Stories From the Internet Age</text>
  <text x="600" y="520" text-anchor="middle" font-family="Inter, sans-serif" font-size="16" fill="white" opacity="0.4">popcornbytes.com</text>
</svg>
SVG;

file_put_contents($base_dir . '/assets/images/default-og.svg', $og_svg);
echo "[OK] default-og.svg\n";

echo "\n=================================================\n";
echo "Generated $count placeholder thumbnails + 1 OG image\n";
echo "Location: $output_dir/\n";
