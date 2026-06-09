<?php include 'includes/header.php'; ?>

<main>
    <section class="hero">
        <div class="container hero-content fade-in-trigger">
            <h1>Old Dhaka Biryani</h1>
            <p><?php echo __('hero_tagline'); ?></p>
            <a href="menu.php" class="btn"><?php echo __('order_now'); ?></a>
        </div>
    </section>

    <section class="home-intro">
        <div class="container">
            <div class="content-box slide-up-trigger">
                <h2 class="section-title"><?php echo __('home_intro_title'); ?></h2>
                <p><?php echo __('home_intro_p1'); ?></p>
                <p style="margin-top: 20px; font-weight: bold; color: var(--primary-color);">
                    <?php echo __('home_intro_p2'); ?>
                </p>
            </div>
        </div>
    </section>

    <section class="home-story" style="background-color: #fff;">
        <div class="container">
            <div class="footer-grid" style="align-items: center;">
                <div class="story-text slide-up-trigger">
                    <h2 class="section-title" style="text-align: left; margin-bottom: 20px;"><?php echo __('home_story_title'); ?></h2>
                    <p><?php echo __('home_story_p1'); ?></p>
                    <p style="margin-top: 15px;"><?php echo __('home_story_p2'); ?></p>
                    <p style="margin-top: 15px; font-style: italic; color: var(--light-text);"><?php echo __('home_story_p3'); ?></p>
                </div>
                <div class="story-image slide-up-trigger" style="text-align: center;">
                    <img src="assets/img/story-image.jpg" alt="Our Heritage" style="max-width: 100%; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="footer-grid" style="text-align: center;">
                <div class="feature-item slide-up-trigger">
                    <i class="fas fa-check-circle" style="font-size: 40px; color: var(--secondary-color); margin-bottom: 15px;"></i>
                    <h3><?php echo __('halal_certified'); ?></h3>
                </div>
                <div class="feature-item slide-up-trigger">
                    <i class="fas fa-leaf" style="font-size: 40px; color: var(--secondary-color); margin-bottom: 15px;"></i>
                    <h3><?php echo __('fresh_ingredients'); ?></h3>
                </div>
                <div class="feature-item slide-up-trigger">
                    <i class="fas fa-utensils" style="font-size: 40px; color: var(--secondary-color); margin-bottom: 15px;"></i>
                    <h3><?php echo __('homemade'); ?></h3>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
