    </main>

    <!-- Footer Ad Slot -->
    <?php if (ADSENSE_PUBLISHER_ID && ADSENSE_SLOT_FOOTER): ?>
    <div class="ad-container ad-footer">
        <div class="container">
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="<?= e(ADSENSE_PUBLISHER_ID) ?>"
                 data-ad-slot="<?= e(ADSENSE_SLOT_FOOTER) ?>"
                 data-ad-format="horizontal"
                 data-full-width-responsive="true"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>
    </div>
    <?php endif; ?>

    <!-- Site Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <a href="/" class="footer-logo">
                        <img src="/assets/images/logo.svg" alt="<?= e(SITE_NAME) ?>" class="logo-img logo-img-footer" width="160" height="42">
                    </a>
                    <p><?= e(SITE_DESCRIPTION) ?></p>
                </div>

                <div class="footer-nav">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/about">About</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="footer-categories">
                    <h3>Categories</h3>
                    <ul>
                        <?php foreach (get_categories() as $cat): ?>
                            <li><a href="<?= e(category_url($cat)) ?>"><?= e($cat) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="footer-newsletter">
                    <h3>Weekly AI Digest</h3>
                    <p>Claude updates, honest reviews, and productivity tips &mdash; every week, free.</p>
                    <form class="newsletter-form pcb-newsletter" data-form-id="footer">
                        <input type="email" name="email" placeholder="Your email address" required aria-label="Email address">
                        <button type="submit" class="btn btn-primary">Subscribe Free</button>
                        <p class="newsletter-msg" aria-live="polite"></p>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</p>
                <div class="footer-social">
                    <?php if (SOCIAL_TWITTER): ?>
                        <a href="https://twitter.com/<?= e(SOCIAL_TWITTER) ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (SOCIAL_FACEBOOK): ?>
                        <a href="https://facebook.com/<?= e(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (SOCIAL_INSTAGRAM): ?>
                        <a href="https://instagram.com/<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Nav Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile navigation
        var toggle = document.querySelector('.nav-toggle');
        var navList = document.querySelector('.nav-list');
        if (toggle && navList) {
            toggle.addEventListener('click', function() {
                var expanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !expanded);
                navList.classList.toggle('active');
            });
        }

        // Cookie consent
        if (!localStorage.getItem('cookie_consent')) {
            var banner = document.getElementById('cookie-consent');
            if (banner) banner.style.display = 'block';
        }
    });

    function acceptCookies() {
        localStorage.setItem('cookie_consent', 'all');
        document.getElementById('cookie-consent').style.display = 'none';
    }

    function rejectCookies() {
        localStorage.setItem('cookie_consent', 'essential');
        document.getElementById('cookie-consent').style.display = 'none';
    }

    // Newsletter AJAX submission
    document.querySelectorAll('.pcb-newsletter').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = form.querySelector('button[type="submit"]');
            var msg = form.querySelector('.newsletter-msg');
            var email = form.querySelector('input[type="email"]').value;
            btn.disabled = true;
            btn.textContent = 'Subscribing...';
            msg.style.color = '';
            msg.textContent = '';
            fetch('/newsletter-subscribe.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    msg.style.color = '#22c55e';
                    msg.textContent = "You're in! Check your inbox for a welcome email.";
                    form.querySelector('input[type="email"]').value = '';
                } else {
                    msg.style.color = '#e63946';
                    msg.textContent = data.message || 'Something went wrong. Please try again.';
                }
                btn.disabled = false;
                btn.textContent = 'Subscribe Free';
            })
            .catch(function() {
                msg.style.color = '#e63946';
                msg.textContent = 'Network error. Please try again.';
                btn.disabled = false;
                btn.textContent = 'Subscribe Free';
            });
        });
    });

    // Reveal ad containers only after AdSense fills them
    (function() {
        if (typeof adsbygoogle === 'undefined') return;
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                if (m.type === 'attributes' && m.attributeName === 'data-ad-status') {
                    var ins = m.target;
                    var container = ins.closest('.ad-container');
                    if (container) {
                        if (ins.getAttribute('data-ad-status') === 'filled') {
                            container.classList.add('ad-loaded');
                        } else {
                            container.classList.remove('ad-loaded');
                        }
                    }
                }
            });
        });
        document.querySelectorAll('ins.adsbygoogle').forEach(function(ins) {
            observer.observe(ins, { attributes: true, attributeFilter: ['data-ad-status'] });
        });
    })();
    </script>
</body>
</html>
