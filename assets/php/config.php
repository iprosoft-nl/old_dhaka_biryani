<?php
// Configuration for Old Dhaka Biryani

// Load environment variables from .env file if it exists (for local development)
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments and empty lines
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        // Check if there is an '=' in the line
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Strip quotes if they surround the value
            if (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
                $value = $matches[1];
            }
            
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// 1. Mollie API Settings
define('MOLLIE_API_KEY', getenv('MOLLIE_API_KEY') ?: '');

// 2. Email Settings (SMTP)
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', (int)(getenv('SMTP_PORT') ?: 587));
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: '');

// 3. WhatsApp Settings (Using a service like UltraMsg or Twilio)
define('WHATSAPP_API_URL', getenv('WHATSAPP_API_URL') ?: '');
define('WHATSAPP_TOKEN', getenv('WHATSAPP_TOKEN') ?: '');
define('ADMIN_WHATSAPP', getenv('ADMIN_WHATSAPP') ?: '');

// 4. General Settings
define('SITE_URL', getenv('SITE_URL') ?: 'http://yourdomain.com/old_dhaka_biryani');
define('VAT_RATE', 0.09);
?>
