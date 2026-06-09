<?php include 'includes/header.php'; ?>

<main>
    <section class="contact-hero" style="background: var(--accent-color); color: white; padding: 60px 0; text-align: center;">
        <div class="container fade-in-trigger">
            <h1><?php echo __('contact_title'); ?></h1>
            <p>We'd love to hear from you!</p>
        </div>
    </section>

    <section class="contact-info">
        <div class="container">
            <div class="footer-grid">
                <div class="contact-details slide-up-trigger">
                    <div class="content-box">
                        <h2 class="section-title" style="text-align: left; margin-bottom: 20px;">Get In Touch</h2>
                        <p style="margin-bottom: 15px;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color); width: 25px;"></i> Buurmalsenlaan 20, 5043XG Tilburg</p>
                        <p style="margin-bottom: 15px;"><i class="fas fa-phone" style="color: var(--primary-color); width: 25px;"></i> <a href="tel:+31627265759" style="text-decoration: none; color: inherit;">+31 627265759</a></p>
                        <p style="margin-bottom: 15px;"><i class="fas fa-envelope" style="color: var(--primary-color); width: 25px;"></i> <a href="mailto:madsum@gmail.com" style="text-decoration: none; color: inherit;">madsum@gmail.com</a></p>
                        <p style="margin-bottom: 25px;"><i class="fab fa-whatsapp" style="color: #25d366; width: 25px;"></i> <a href="https://wa.me/31627265759" target="_blank" style="text-decoration: none; color: inherit;">WhatsApp Us</a></p>
                        
                        <h3 style="margin-bottom: 15px; color: var(--accent-color);"><?php echo __('opening_hours'); ?></h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 5px 0; font-weight: 600;"><?php echo __('mon_thu'); ?>:</td>
                                <td style="padding: 5px 0; text-align: right;">12:00 – 23:00</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; font-weight: 600;"><?php echo __('fri_sat'); ?>:</td>
                                <td style="padding: 5px 0; text-align: right;">12:00 – 00:00</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; font-weight: 600;"><?php echo __('sun'); ?>:</td>
                                <td style="padding: 5px 0; text-align: right;">12:00 – 23:00</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="contact-map slide-up-trigger">
                    <div class="content-box" style="padding: 10px; height: 100%;">
                        <iframe 
                            src="https://maps.google.com/maps?q=Buurmalsenlaan%2020%2C%205043XG%20Tilburg&t=m&z=15&output=embed" 
                            width="100%" 
                            height="450" 
                            style="border:0; border-radius: 10px;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
