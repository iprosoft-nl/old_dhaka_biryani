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

// Helper function to safely read environment variables (handles cases where putenv() is disabled on shared hosting)
if (!function_exists('get_env_var')) {
    function get_env_var($key, $default = '') {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return $_SERVER[$key];
        }
        $val = getenv($key);
        return ($val !== false && $val !== '') ? $val : $default;
    }
}

// 1. Mollie API Settings
define('MOLLIE_API_KEY', get_env_var('MOLLIE_API_KEY'));

// 2. Email Settings (SMTP)
define('SMTP_HOST', get_env_var('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', (int)get_env_var('SMTP_PORT', 587));
define('SMTP_USER', get_env_var('SMTP_USER'));
define('SMTP_PASS', get_env_var('SMTP_PASS'));
define('ADMIN_EMAIL', get_env_var('ADMIN_EMAIL'));

// 3. WhatsApp Settings (Meta WhatsApp Business Cloud API)
define('WHATSAPP_PHONE_NUMBER_ID', get_env_var('WHATSAPP_PHONE_NUMBER_ID'));
define('WHATSAPP_ACCESS_TOKEN', get_env_var('WHATSAPP_ACCESS_TOKEN'));
define('WHATSAPP_APP_ID', get_env_var('WHATSAPP_APP_ID'));
define('WHATSAPP_APP_SECRET', get_env_var('WHATSAPP_APP_SECRET'));
define('ADMIN_WHATSAPP', get_env_var('ADMIN_WHATSAPP'));

// 4. General Settings
define('SITE_URL', get_env_var('SITE_URL', 'https://olddhakabiryani.site.je/'));
define('VAT_RATE', 0.09);
?>
