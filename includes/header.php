<?php require_once 'assets/php/lang.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Old Dhaka Biryani - <?php echo __('nav_home'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <nav class="container">
            <div class="logo">
                <a href="index.php">Old Dhaka Biryani</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php"><?php echo __('nav_home'); ?></a></li>
                <li><a href="about.php"><?php echo __('nav_about'); ?></a></li>
                <li><a href="menu.php"><?php echo __('nav_menu'); ?></a></li>
                <li><a href="contact.php"><?php echo __('nav_contact'); ?></a></li>
            </ul>
            <div class="header-actions">
                <div class="lang-switch">
                    <a href="?lang=en" class="<?php echo $current_lang == 'en' ? 'active' : ''; ?>">EN</a>
                    <a href="?lang=nl" class="<?php echo $current_lang == 'nl' ? 'active' : ''; ?>">NL</a>
                </div>
                <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
            </div>
        </nav>
    </header>
