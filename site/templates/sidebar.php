                <!-- Sidebar -->
                <aside class="sidebar" aria-label="Sidebar">
                    <!-- Sidebar Ad -->
                    <?php if (ADSENSE_PUBLISHER_ID && ADSENSE_SLOT_SIDEBAR): ?>
                    <div class="ad-container ad-sidebar">
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="<?= e(ADSENSE_PUBLISHER_ID) ?>"
                             data-ad-slot="<?= e(ADSENSE_SLOT_SIDEBAR) ?>"
                             data-ad-format="auto"
                             data-full-width-responsive="true"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                    <?php endif; ?>

                    <!-- Categories Widget -->
                    <div class="sidebar-widget">
                        <h3>Categories</h3>
                        <ul class="category-list">
                            <?php foreach (get_categories() as $cat): ?>
                                <li><a href="<?= e(category_url($cat)) ?>"><?= e($cat) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Popular Posts Widget -->
                    <div class="sidebar-widget">
                        <h3>Popular Posts</h3>
                        <ul class="popular-posts">
                            <?php foreach (array_slice(get_all_posts(), 0, 5) as $pp): ?>
                                <li>
                                    <a href="<?= e(post_url($pp['slug'])) ?>">
                                        <span class="popular-title"><?= e($pp['title']) ?></span>
                                        <span class="popular-date"><?= date('M j, Y', strtotime($pp['date'])) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Newsletter CTA -->
                    <div class="sidebar-widget sidebar-cta">
                        <h3>Claude Updates, Weekly</h3>
                        <p>Get our latest Claude AI guides, comparisons, and tips &mdash; no spam, unsubscribe anytime.</p>
                        <form class="newsletter-form" action="/contact" method="get">
                            <input type="email" name="email" placeholder="you@email.com" required aria-label="Email address">
                            <button type="submit" class="btn btn-primary">Subscribe Free</button>
                        </form>
                    </div>

                    <!-- Second Sidebar Ad -->
                    <?php if (ADSENSE_PUBLISHER_ID && ADSENSE_SLOT_SIDEBAR): ?>
                    <div class="ad-container ad-sidebar sticky-ad">
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="<?= e(ADSENSE_PUBLISHER_ID) ?>"
                             data-ad-slot="<?= e(ADSENSE_SLOT_SIDEBAR) ?>"
                             data-ad-format="vertical"
                             data-full-width-responsive="true"></ins>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>
                    <?php endif; ?>
                </aside>
