<?php
/*
 * Template Name: Supplier Directory (Admin Only)
 * Offline-ready HTML directory of all lodge/supplier details.
 * Access: /supplier-directory/ as an admin.
 */

if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Not authorised.' );
}

// Fetch all lodges
$lodges = get_posts([
    'post_type'      => 'lodge',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

// Fetch all parks for lookup
$parks_raw = get_posts([
    'post_type'      => 'park',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
]);
$parks = [];
foreach ( $parks_raw as $p ) {
    $parks[ $p->ID ] = [
        'name'    => $p->post_title,
        'country' => get_field( 'country', $p->ID ) ?: '',
        'region'  => get_field( 'region',  $p->ID ) ?: '',
        'url'     => get_permalink( $p->ID ),
    ];
}

// Group lodges by park
$by_park = [];
foreach ( $lodges as $lodge ) {
    $f       = get_fields( $lodge->ID );
    $park_obj = $f['park'] ?? null;
    $park_id  = $park_obj ? $park_obj->ID : 0;
    $park_name = $park_id && isset( $parks[ $park_id ] ) ? $parks[ $park_id ]['name'] : 'Unassigned';

    // Amenity terms
    $amenity_terms = get_the_terms( $lodge->ID, 'amenity' ) ?: [];
    $amenities     = array_map( fn($t) => $t->name, $amenity_terms );

    // Category
    $cat_terms = get_the_terms( $lodge->ID, 'lodge_category' ) ?: [];
    $category  = $cat_terms ? $cat_terms[0]->name : '';

    $by_park[ $park_name ][] = [
        'id'               => $lodge->ID,
        'name'             => $lodge->post_title,
        'slug'             => $lodge->post_name,
        'url'              => get_permalink( $lodge->ID ),
        'category'         => $category,
        'park'             => $park_name,
        'country'          => $park_id && isset( $parks[ $park_id ] ) ? $parks[ $park_id ]['country'] : '',
        'region'           => $park_id && isset( $parks[ $park_id ] ) ? $parks[ $park_id ]['region'] : '',
        'rating'           => $f['rating_overall'] ?? null,
        'price_low'        => $f['price_low_season_usd'] ?? null,
        'price_high'       => $f['price_high_season_usd'] ?? null,
        'phone'            => $f['phone'] ?? '',
        'email'            => $f['email'] ?? '',
        'official_url'     => $f['official_url'] ?? '',
        'booking_url'      => $f['booking_url'] ?? '',
        'capacity_rooms'   => $f['capacity_rooms'] ?? null,
        'min_nights'       => $f['min_nights'] ?? null,
        'child_friendly'   => $f['child_friendly'] ?? null,
        'nearest_airstrip' => $f['nearest_airstrip'] ?? '',
        'transfer_mins'    => $f['transfer_time_mins'] ?? null,
        'amenities'        => $amenities,
        'featured'         => $f['featured'] ?? false,
    ];
}

ksort( $by_park );
$total = count( $lodges );
$date  = date( 'j F Y' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PopCornBytes — Supplier Directory <?= esc_html( $date ) ?></title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 14px; color: #1a1a1a; background: #f5f4f0; }

  .dir-header { background: #1a1a1a; color: #fff; padding: 32px 40px; display: flex; justify-content: space-between; align-items: flex-end; }
  .dir-header h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.5px; }
  .dir-header h1 span { color: #f4a261; }
  .dir-header p { font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 4px; }
  .dir-meta { text-align: right; font-size: 12px; color: rgba(255,255,255,0.45); }

  .dir-toc { background: #fff; border-bottom: 1px solid #e5e5e5; padding: 16px 40px; display: flex; flex-wrap: wrap; gap: 8px 20px; }
  .dir-toc a { font-size: 13px; color: #2563eb; text-decoration: none; white-space: nowrap; }
  .dir-toc a:hover { text-decoration: underline; }

  .dir-body { max-width: 1280px; margin: 0 auto; padding: 32px 40px; }

  .park-section { margin-bottom: 48px; }
  .park-heading { display: flex; align-items: baseline; gap: 12px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid #1a1a1a; }
  .park-heading h2 { font-size: 19px; font-weight: 700; }
  .park-heading .park-meta { font-size: 12px; color: #666; }
  .park-heading .park-count { font-size: 12px; background: #f4a261; color: #fff; padding: 2px 8px; border-radius: 99px; font-weight: 600; margin-left: auto; }

  table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
  thead tr { background: #f8f8f6; }
  th { padding: 10px 12px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #555; border-bottom: 1px solid #e5e5e5; white-space: nowrap; }
  td { padding: 11px 12px; vertical-align: top; border-bottom: 1px solid #f0f0f0; font-size: 13px; line-height: 1.4; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #fafaf8; }

  .lodge-name { font-weight: 600; color: #1a1a1a; }
  .lodge-name a { color: inherit; text-decoration: none; }
  .lodge-name a:hover { color: #f4a261; }
  .featured-badge { display: inline-block; font-size: 10px; background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 4px; margin-left: 6px; font-weight: 600; vertical-align: middle; }
  .category-badge { display: inline-block; font-size: 10px; background: #ede9fe; color: #5b21b6; padding: 1px 6px; border-radius: 4px; font-weight: 600; }

  .rating { display: inline-flex; align-items: center; gap: 4px; }
  .rating-val { font-weight: 700; color: #1a1a1a; }
  .rating-stars { color: #f4a261; font-size: 11px; letter-spacing: -1px; }

  .price { font-weight: 600; color: #166534; white-space: nowrap; }
  .price-range { font-size: 11px; color: #555; }

  .contact a { display: block; color: #2563eb; text-decoration: none; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
  .contact a:hover { text-decoration: underline; }

  .amenity-list { display: flex; flex-wrap: wrap; gap: 3px; }
  .amenity-chip { font-size: 10px; background: #f0f0f0; color: #444; padding: 1px 6px; border-radius: 4px; white-space: nowrap; }

  .facts { font-size: 12px; color: #444; }
  .facts span { display: block; }

  .na { color: #bbb; font-size: 12px; }

  .no-print { }
  .print-btn { position: fixed; bottom: 24px; right: 24px; background: #1a1a1a; color: #fff; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 100; }
  .print-btn:hover { background: #333; }

  @media print {
    body { background: #fff; font-size: 12px; }
    .dir-header { padding: 16px 20px; }
    .dir-toc, .print-btn { display: none; }
    .dir-body { padding: 16px 20px; }
    .park-section { page-break-inside: avoid; margin-bottom: 28px; }
    table { box-shadow: none; border: 1px solid #ddd; }
    td, th { padding: 7px 8px; }
  }

  @media (max-width: 900px) {
    .dir-header, .dir-toc, .dir-body { padding-left: 16px; padding-right: 16px; }
    table { display: block; overflow-x: auto; }
  }
</style>
</head>
<body>

<div class="dir-header">
  <div>
    <h1><span>PopCornBytes</span> Supplier Directory</h1>
    <p>Africa's Greatest Safari Lodges — Internal Reference</p>
  </div>
  <div class="dir-meta">
    Generated: <?= esc_html( $date ) ?><br>
    <?= esc_html( $total ) ?> lodges · <?= count( $by_park ) ?> parks
  </div>
</div>

<nav class="dir-toc no-print">
  <?php foreach ( array_keys( $by_park ) as $park_name ): ?>
    <a href="#park-<?= esc_attr( sanitize_title( $park_name ) ) ?>"><?= esc_html( $park_name ) ?> (<?= count( $by_park[ $park_name ] ) ?>)</a>
  <?php endforeach; ?>
</nav>

<div class="dir-body">
  <?php foreach ( $by_park as $park_name => $lodge_list ): ?>
  <?php
    // Get country/region for this park
    $first = $lodge_list[0];
    $park_location = array_filter([$first['country'], $first['region']]);
  ?>
  <section class="park-section" id="park-<?= esc_attr( sanitize_title( $park_name ) ) ?>">
    <div class="park-heading">
      <h2><?= esc_html( $park_name ) ?></h2>
      <?php if ( $park_location ): ?>
        <span class="park-meta"><?= esc_html( implode( ' · ', $park_location ) ) ?></span>
      <?php endif; ?>
      <span class="park-count"><?= count( $lodge_list ) ?> lodges</span>
    </div>

    <table>
      <thead>
        <tr>
          <th style="min-width:180px">Lodge</th>
          <th>Category</th>
          <th>Rating</th>
          <th>Price (USD/night pp)</th>
          <th>Contact</th>
          <th>Links</th>
          <th>Logistics</th>
          <th>Amenities</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $lodge_list as $l ): ?>
        <tr>
          <td>
            <div class="lodge-name">
              <a href="<?= esc_url( $l['url'] ) ?>" target="_blank"><?= esc_html( $l['name'] ) ?></a>
              <?php if ( $l['featured'] ): ?><span class="featured-badge">Featured</span><?php endif; ?>
            </div>
          </td>
          <td>
            <?php if ( $l['category'] ): ?>
              <span class="category-badge"><?= esc_html( $l['category'] ) ?></span>
            <?php else: ?>
              <span class="na">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ( $l['rating'] ): ?>
              <div class="rating">
                <span class="rating-val"><?= number_format( $l['rating'], 1 ) ?></span>
                <span class="rating-stars">
                  <?php
                    $stars = round( $l['rating'] / 2 );
                    echo str_repeat( '★', $stars ) . str_repeat( '☆', 5 - $stars );
                  ?>
                </span>
                <span style="font-size:11px;color:#888">/10</span>
              </div>
            <?php else: ?>
              <span class="na">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ( $l['price_low'] ): ?>
              <span class="price">$<?= number_format( $l['price_low'] ) ?></span>
              <?php if ( $l['price_high'] ): ?>
                <div class="price-range">High: $<?= number_format( $l['price_high'] ) ?></div>
              <?php endif; ?>
            <?php else: ?>
              <span class="na">POA</span>
            <?php endif; ?>
          </td>
          <td class="contact">
            <?php if ( $l['phone'] ): ?>
              <a href="tel:<?= esc_attr( preg_replace('/[^+\d]/', '', $l['phone']) ) ?>"><?= esc_html( $l['phone'] ) ?></a>
            <?php endif; ?>
            <?php if ( $l['email'] ): ?>
              <a href="mailto:<?= esc_attr( $l['email'] ) ?>"><?= esc_html( $l['email'] ) ?></a>
            <?php endif; ?>
            <?php if ( !$l['phone'] && !$l['email'] ): ?>
              <span class="na">—</span>
            <?php endif; ?>
          </td>
          <td class="contact">
            <?php if ( $l['official_url'] ): ?>
              <a href="<?= esc_url( $l['official_url'] ) ?>" target="_blank" rel="noopener">Official ↗</a>
            <?php endif; ?>
            <?php if ( $l['booking_url'] ): ?>
              <a href="<?= esc_url( $l['booking_url'] ) ?>" target="_blank" rel="noopener">Book ↗</a>
            <?php endif; ?>
            <?php if ( !$l['official_url'] && !$l['booking_url'] ): ?>
              <span class="na">—</span>
            <?php endif; ?>
          </td>
          <td class="facts">
            <?php if ( $l['capacity_rooms'] ): ?>
              <span><?= intval( $l['capacity_rooms'] ) ?> rooms/tents</span>
            <?php endif; ?>
            <?php if ( $l['min_nights'] ): ?>
              <span>Min <?= intval( $l['min_nights'] ) ?> nights</span>
            <?php endif; ?>
            <?php if ( isset( $l['child_friendly'] ) && $l['child_friendly'] !== null ): ?>
              <span><?= $l['child_friendly'] ? '✓ Child friendly' : '✗ Adults only' ?></span>
            <?php endif; ?>
            <?php if ( $l['nearest_airstrip'] ): ?>
              <span>✈ <?= esc_html( $l['nearest_airstrip'] ) ?></span>
            <?php endif; ?>
            <?php if ( $l['transfer_mins'] ): ?>
              <span>🚗 <?= intval( $l['transfer_mins'] ) ?> min transfer</span>
            <?php endif; ?>
            <?php if ( !$l['capacity_rooms'] && !$l['min_nights'] && !$l['nearest_airstrip'] && !$l['transfer_mins'] ): ?>
              <span class="na">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ( $l['amenities'] ): ?>
              <div class="amenity-list">
                <?php foreach ( $l['amenities'] as $a ): ?>
                  <span class="amenity-chip"><?= esc_html( $a ) ?></span>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <span class="na">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
  <?php endforeach; ?>
</div>

<button class="print-btn no-print" onclick="window.print()">🖨 Print / Save PDF</button>

</body>
</html>
