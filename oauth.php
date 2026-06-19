<?php
require_once 'assets/php/config.php';

// Dynamically determine the redirect URI pointing back to this file
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$redirectUri = $protocol . $host . strtok($_SERVER["REQUEST_URI"], '?');

// Config path for .env file
$envFilePath = __DIR__ . '/.env';

// Helper function to update .env key-value pair
function update_env_value($filePath, $key, $value) {
    if (!file_exists($filePath)) {
        return false;
    }
    $content = file_get_contents($filePath);
    $pattern = '/^(' . preg_quote($key, '/') . '\s*=)(.*)$/m';
    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, '${1}' . $value, $content);
    } else {
        // Trim trailing newlines and append
        $content = rtrim($content) . "\n" . $key . "=" . $value . "\n";
    }
    return file_put_contents($filePath, $content) !== false;
}

$error = '';
$success = '';
$tokenInfo = null;

// Determine if we need to request or exchange token
$code = $_GET['code'] ?? '';
$action = $_GET['action'] ?? '';

if ($code) {
    // Stage 1: Exchange code for short-lived access token
    $tokenUrl = "https://graph.facebook.com/v20.0/oauth/access_token";
    $params = [
        'client_id' => WHATSAPP_APP_ID,
        'redirect_uri' => $redirectUri,
        'client_secret' => WHATSAPP_APP_SECRET,
        'code' => $code
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For compatibility with local certificates
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $shortLivedToken = $data['access_token'] ?? '';

        if ($shortLivedToken) {
            // Stage 2: Exchange short-lived token for long-lived access token (60 days)
            $exchangeUrl = "https://graph.facebook.com/v20.0/oauth/access_token";
            $exchangeParams = [
                'grant_type' => 'fb_exchange_token',
                'client_id' => WHATSAPP_APP_ID,
                'client_secret' => WHATSAPP_APP_SECRET,
                'fb_exchange_token' => $shortLivedToken
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $exchangeUrl . '?' . http_build_query($exchangeParams));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $exchangeResponse = curl_exec($ch);
            $exchangeHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($exchangeHttpCode === 200) {
                $exchangeData = json_decode($exchangeResponse, true);
                $longLivedToken = $exchangeData['access_token'] ?? '';

                if ($longLivedToken) {
                    // Stage 3: Save the token in .env file
                    if (update_env_value($envFilePath, 'WHATSAPP_ACCESS_TOKEN', $longLivedToken)) {
                        $success = "Successfully renewed the WhatsApp Access Token! The local .env file has been updated automatically.";
                        $tokenInfo = $exchangeData;
                    } else {
                        $error = "Token was retrieved successfully but we failed to write it to the .env file. Please check write permissions for: " . realpath($envFilePath);
                    }
                } else {
                    $error = "Failed to retrieve the long-lived access token from Meta response.";
                }
            } else {
                $error = "Meta API failed during long-lived token exchange (HTTP $exchangeHttpCode): " . htmlspecialchars($exchangeResponse);
            }
        } else {
            $error = "Failed to extract access token from the initial Meta OAuth response.";
        }
    } else {
        $error = "Meta API failed during authorization code exchange (HTTP $httpCode): " . htmlspecialchars($response);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp OAuth Helper - Old Dhaka Biryani</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8b5a2b;
            --secondary-color: #b88a4a;
            --accent-color: #7b3f00;
            --bg-color: #f6f1e8;
            --text-color: #2f241c;
            --light-text: #5e4a38;
            --white: #ffffff;
            --success-color: #27ae60;
            --error-color: #c0392b;
            --border-radius: 16px;
            --box-shadow: 0 10px 30px rgba(123, 63, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            text-align: center;
            border: 1px solid rgba(184, 138, 74, 0.15);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(123, 63, 0, 0.1);
        }

        .logo {
            font-size: 26px;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 25px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
        }

        .icon-whatsapp {
            background-color: #25d366;
            color: white;
        }

        .icon-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .icon-error {
            background-color: rgba(192, 57, 43, 0.1);
            color: var(--error-color);
        }

        h2 {
            margin-bottom: 15px;
            color: var(--accent-color);
            font-weight: 600;
        }

        p {
            color: var(--light-text);
            margin-bottom: 25px;
            font-size: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 30px;
            background-color: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(139, 90, 43, 0.2);
        }

        .btn:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(123, 63, 0, 0.3);
        }

        .btn-facebook {
            background-color: #1877f2;
            box-shadow: 0 4px 6px rgba(24, 119, 242, 0.2);
        }

        .btn-facebook:hover {
            background-color: #166fe5;
            box-shadow: 0 6px 12px rgba(22, 111, 229, 0.3);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: left;
            font-size: 14px;
        }

        .alert-success {
            background-color: rgba(39, 174, 96, 0.08);
            border-left: 4px solid var(--success-color);
            color: #1e7e34;
        }

        .alert-error {
            background-color: rgba(192, 57, 43, 0.08);
            border-left: 4px solid var(--error-color);
            color: #bd2130;
        }

        .info-box {
            background: rgba(184, 138, 74, 0.05);
            border: 1px solid rgba(184, 138, 74, 0.15);
            border-radius: 8px;
            padding: 20px;
            text-align: left;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .info-title {
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed rgba(184, 138, 74, 0.15);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--light-text);
            font-weight: 500;
        }

        .info-value {
            color: var(--text-color);
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            max-width: 60%;
        }

        .debug-steps {
            list-style: none;
            padding-left: 0;
            margin-top: 10px;
        }

        .debug-steps li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 8px;
            font-size: 13.5px;
            color: var(--light-text);
        }

        .debug-steps li::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--secondary-color);
        }

        .footer-note {
            margin-top: 25px;
            font-size: 12px;
            color: #a8937e;
        }

        .footer-note a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="logo">Old Dhaka Biryani</div>

        <?php if ($success): ?>
            <div class="icon-wrapper icon-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Authentication Renewed!</h2>
            <p>Your WhatsApp sender integration is now fully authorized and authenticated.</p>

            <div class="alert alert-success">
                <i class="fas fa-info-circle"></i> <?php echo $success; ?>
            </div>

            <?php if ($tokenInfo): ?>
                <div class="info-box">
                    <div class="info-title"><i class="fas fa-key"></i> Token Exchange Details</div>
                    <div class="info-item">
                        <span class="info-label">Token Type:</span>
                        <span class="info-value"><?php echo htmlspecialchars($tokenInfo['token_type'] ?? 'Bearer'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Expires In:</span>
                        <span class="info-value">
                            <?php 
                            if (isset($tokenInfo['expires_in'])) {
                                $days = round($tokenInfo['expires_in'] / 86400);
                                echo htmlspecialchars($days) . " days (" . htmlspecialchars($tokenInfo['expires_in']) . " seconds)";
                            } else {
                                echo "Does not expire (or permanent)";
                            }
                            ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">App ID:</span>
                        <span class="info-value"><?php echo htmlspecialchars(WHATSAPP_APP_ID); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <a href="index.php" class="btn"><i class="fas fa-arrow-left"></i> Go to Homepage</a>

        <?php elseif ($error): ?>
            <div class="icon-wrapper icon-error">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>Failed to Renew Token</h2>
            <p>We encountered an error during the authentication flow.</p>

            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>

            <div class="info-box" style="background: rgba(192, 57, 43, 0.02); border-color: rgba(192, 57, 43, 0.15)">
                <div class="info-title" style="color: var(--error-color)"><i class="fas fa-tools"></i> Trouleshooting Steps</div>
                <ul class="debug-steps">
                    <li>Verify that the App ID and App Secret in your <code>.env</code> file are correct.</li>
                    <li>Ensure this redirect URI is added to your Facebook Developer portal settings:
                        <br><strong style="font-family: monospace; display: block; margin-top: 5px; color: var(--text-color);"><?php echo htmlspecialchars($redirectUri); ?></strong>
                    </li>
                    <li>Ensure you are generating the token with the correct user context.</li>
                </ul>
            </div>

            <a href="oauth.php" class="btn"><i class="fas fa-redo"></i> Try Again</a>

        <?php else: ?>
            <div class="icon-wrapper icon-whatsapp">
                <i class="fab fa-whatsapp"></i>
            </div>
            <h2>WhatsApp API Authentication</h2>
            <p>Easily authenticate and generate a long-lived 60-day access token for the WhatsApp Business Cloud API.</p>

            <?php
            $missingConfigs = [];
            if (empty(WHATSAPP_APP_ID)) $missingConfigs[] = 'WHATSAPP_APP_ID';
            if (empty(WHATSAPP_APP_SECRET)) $missingConfigs[] = 'WHATSAPP_APP_SECRET';
            ?>

            <?php if (!empty($missingConfigs)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <strong>Configuration Error:</strong>
                    The following keys are missing in your <code>.env</code> file: <strong><?php echo implode(', ', $missingConfigs); ?></strong>. Please configure them before continuing.
                </div>
            <?php else: ?>
                <div class="info-box">
                    <div class="info-title"><i class="fas fa-cog"></i> Configuration Found</div>
                    <div class="info-item">
                        <span class="info-label">Meta App ID:</span>
                        <span class="info-value"><?php echo htmlspecialchars(WHATSAPP_APP_ID); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Redirect URI:</span>
                        <span class="info-value"><?php echo htmlspecialchars($redirectUri); ?></span>
                    </div>
                </div>

                <?php
                // Generate the Facebook OAuth authorization URL
                $authUrl = "https://www.facebook.com/v20.0/dialog/oauth?" . http_build_query([
                    'client_id' => WHATSAPP_APP_ID,
                    'redirect_uri' => $redirectUri,
                    'scope' => 'whatsapp_business_messaging,whatsapp_business_management',
                    'response_type' => 'code'
                ]);
                ?>

                <a href="<?php echo htmlspecialchars($authUrl); ?>" class="btn btn-facebook">
                    <i class="fab fa-facebook"></i> Login with Facebook
                </a>
            <?php endif; ?>

        <?php endif; ?>

        <div class="footer-note">
            Need a permanent system token instead? Check <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started" target="_blank">Meta Developer Docs</a>
        </div>
    </div>

</body>
</html>
