<?php
// Configuration for Old Dhaka Biryani

// 1. Mollie API Settings (Get your key from mollie.com)
define('MOLLIE_API_KEY', 'test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// 2. Email Settings (SMTP)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('ADMIN_EMAIL', 'madsum@gmail.com');

// 3. WhatsApp Settings (Using a service like UltraMsg or Twilio)
define('WHATSAPP_API_URL', 'https://api.ultramsg.com/instanceXXXXX/messages/chat');
define('WHATSAPP_TOKEN', 'your-token');
define('ADMIN_WHATSAPP', '+31627265759');

// 4. General Settings
define('SITE_URL', 'http://yourdomain.com/old_dhaka_biryani');
define('VAT_RATE', 0.09);
?>
