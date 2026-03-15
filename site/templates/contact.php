    <section class="page-section">
        <div class="container">
            <div class="page-content page-content-narrow">
                <h1>Contact Us</h1>
                <p class="lead">Got a story idea, feedback, or just want to say hi? We would love to hear from you.</p>

                <form class="contact-form" method="post" action="/contact">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required placeholder="Jane Doe">
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required placeholder="jane@example.com">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subject" name="subject">
                            <option value="general">General Inquiry</option>
                            <option value="story">Story Idea / Suggestion</option>
                            <option value="collab">Collaboration / Partnership</option>
                            <option value="ads">Advertising</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required placeholder="What's on your mind?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                </form>

                <div class="contact-alt">
                    <p>You can also reach us directly at <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></p>
                </div>
            </div>
        </div>
    </section>
