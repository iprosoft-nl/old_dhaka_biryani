<?php include 'includes/header.php'; ?>

<main>
    <section class="about-hero" style="background: var(--accent-color); color: white; padding: 60px 0; text-align: center;">
        <div class="container fade-in-trigger">
            <h1><?php echo __('about_title'); ?></h1>
            <p><?php echo __('about_subtitle'); ?></p>
        </div>
    </section>

    <section class="about-content">
        <div class="container">
            <div class="content-box slide-up-trigger">
                <p><?php echo __('home_intro_p1'); ?></p>
                <div class="video-container" style="margin: 40px 0; position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                    <iframe 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        src="https://www.youtube.com/embed/fxgsptvXGDg" 
                        title="Old Dhaka Biryani Story" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <p><?php echo __('home_story_p1'); ?></p>
                <p style="margin-top: 15px;"><?php echo __('home_story_p2'); ?></p>
            </div>
        </div>
    </section>

    <section class="chef-section" style="background-color: #fff;">
        <div class="container">
            <div class="footer-grid" style="align-items: center;">
                <div class="chef-image slide-up-trigger">
                    <img src="assets/img/chef.jpg" alt="Our Chef" style="max-width: 100%; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                </div>
                <div class="chef-text slide-up-trigger">
                    <h2 class="section-title" style="text-align: left; margin-bottom: 20px;"><?php echo __('chef_title'); ?></h2>
                    <p><?php echo __('chef_story'); ?></p>
                    <ul style="margin-top: 20px; list-style: none;">
                        <li style="margin-bottom: 10px;"><i class="fas fa-check" style="color: var(--primary-color); margin-right: 10px;"></i> <?php echo __('homemade'); ?></li>
                        <li style="margin-bottom: 10px;"><i class="fas fa-check" style="color: var(--primary-color); margin-right: 10px;"></i> <?php echo __('fresh_ingredients'); ?></li>
                        <li style="margin-bottom: 10px;"><i class="fas fa-check" style="color: var(--primary-color); margin-right: 10px;"></i> <?php echo __('halal_certified'); ?></li>
                        <li style="margin-bottom: 10px;"><i class="fas fa-motorcycle" style="color: var(--primary-color); margin-right: 10px;"></i> <?php echo __('delivery_info'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
