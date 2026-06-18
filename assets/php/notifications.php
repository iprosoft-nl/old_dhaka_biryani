<?php
require_once 'config.php';

/**
 * Sends an email using custom SMTP settings (via sockets) or falls back to mail()
 */
function sendSmtpEmail($to, $subject, $messageHtml, $fromEmail, $fromName = 'Old Dhaka Biryani') {
    $host = SMTP_HOST;
    $port = SMTP_PORT;
    $user = SMTP_USER;
    $pass = SMTP_PASS;

    // If SMTP variables are not fully configured, fallback to standard mail()
    if (empty($host) || empty($user) || empty($pass)) {
        $headers = "MIME-Version: 1.0\r\n" .
                   "Content-Type: text/html; charset=UTF-8\r\n" .
                   "From: " . $fromName . " <" . $fromEmail . ">\r\n" .
                   "Reply-To: " . $fromEmail . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        return mail($to, $subject, $messageHtml, $headers);
    }

    $error_log_arr = [];

    // Establish socket connection
    $context = stream_context_create();
    $remoteSocket = ($port == 465 ? 'ssl://' : '') . $host . ':' . $port;
    
    $socket = @stream_socket_client(
        $remoteSocket,
        $errno,
        $errstr,
        15,
        STREAM_CLIENT_CONNECT,
        $context
    );

    if (!$socket) {
        error_log("SMTP connection failed to $remoteSocket: $errstr ($errno). Falling back to PHP mail().");
        $headers = "MIME-Version: 1.0\r\n" .
                   "Content-Type: text/html; charset=UTF-8\r\n" .
                   "From: " . $fromName . " <" . $fromEmail . ">\r\n" .
                   "Reply-To: " . $fromEmail . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        return mail($to, $subject, $messageHtml, $headers);
    }

    $readResponse = function($socket) use (&$error_log_arr) {
        $response = '';
        while (($str = fgets($socket, 515)) !== false) {
            $response .= $str;
            if (substr($str, 3, 1) === ' ') {
                break;
            }
        }
        $error_log_arr[] = "S: " . trim($response);
        return $response;
    };

    $readResponse($socket); // 220 Greeting

    fwrite($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
    $readResponse($socket);

    if ($port == 587) {
        fwrite($socket, "STARTTLS\r\n");
        $response = $readResponse($socket);
        if (strpos($response, '220') === 0) {
            // Enable crypto on the socket stream
            $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            }
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
            }
            
            if (!@stream_socket_enable_crypto($socket, true, $cryptoMethod)) {
                error_log("SMTP STARTTLS failed to enable crypto. Falling back to PHP mail().");
                fclose($socket);
                $headers = "MIME-Version: 1.0\r\n" .
                           "Content-Type: text/html; charset=UTF-8\r\n" .
                           "From: " . $fromName . " <" . $fromEmail . ">\r\n" .
                           "Reply-To: " . $fromEmail . "\r\n" .
                           "X-Mailer: PHP/" . phpversion();
                return mail($to, $subject, $messageHtml, $headers);
            }
            
            // Re-send EHLO after starting TLS
            fwrite($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
            $readResponse($socket);
        } else {
            error_log("SMTP STARTTLS command not accepted. Response: " . trim($response));
        }
    }

    // Login Authentication
    fwrite($socket, "AUTH LOGIN\r\n");
    $readResponse($socket);
    
    fwrite($socket, base64_encode($user) . "\r\n");
    $readResponse($socket);
    
    fwrite($socket, base64_encode($pass) . "\r\n");
    $authResponse = $readResponse($socket);
    if (strpos($authResponse, '235') !== 0) {
        error_log("SMTP Authentication failed: " . trim($authResponse) . ". Full SMTP log:\n" . implode("\n", $error_log_arr));
        fclose($socket);
        // Fallback to PHP mail()
        $headers = "MIME-Version: 1.0\r\n" .
                   "Content-Type: text/html; charset=UTF-8\r\n" .
                   "From: " . $fromName . " <" . $fromEmail . ">\r\n" .
                   "Reply-To: " . $fromEmail . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        return mail($to, $subject, $messageHtml, $headers);
    }

    // Envelope Mail From
    fwrite($socket, "MAIL FROM:<$user>\r\n");
    $readResponse($socket);

    // Recipient
    fwrite($socket, "RCPT TO:<$to>\r\n");
    $readResponse($socket);

    // Data command
    fwrite($socket, "DATA\r\n");
    $readResponse($socket);

    // Headers and Body
    $headers = [
        "MIME-Version: 1.0",
        "Content-Type: text/html; charset=UTF-8",
        "From: =?utf-8?B?" . base64_encode($fromName) . "?= <$user>",
        "Reply-To: <$fromEmail>",
        "To: <$to>",
        "Subject: =?utf-8?B?" . base64_encode($subject) . "?=",
        "Date: " . date('r'),
        "Message-ID: <" . uniqid('', true) . "@" . ($host ?: 'localhost') . ">",
        "X-Mailer: PHP/" . phpversion()
    ];

    $emailData = implode("\r\n", $headers) . "\r\n\r\n" . $messageHtml . "\r\n.\r\n";
    fwrite($socket, $emailData);
    $readResponse($socket);

    fwrite($socket, "QUIT\r\n");
    $readResponse($socket);

    fclose($socket);
    return true;
}

/**
 * Orchestrates order notifications (WhatsApp + HTML Email)
 */
function sendOrderNotifications($orderData) {
    $orderId = $orderData['order_id'];
    $customer = $orderData['customer'];
    $cart = $orderData['cart'];

    // 1. Build details for plain text (WhatsApp)
    $customerName = trim(($customer['first_name'] ?? '') . " " . ($customer['last_name'] ?? ''));
    $customerPhone = $customer['phone'] ?? 'N/A';
    $orderType = strtolower($orderData['order_type'] ?? 'pickup');
    
    $details = "Order ID: #$orderId\n";
    $details .= "Customer: $customerName\n";
    $details .= "Phone: $customerPhone\n";
    $details .= "Type: " . strtoupper($orderType) . "\n";
    
    if ($orderType === 'delivery') {
        $details .= "Address: " . ($customer['address'] ?? '') . ", " . ($customer['city'] ?? '') . " (" . ($customer['postal_code'] ?? '') . ")\n";
        if (!empty($customer['apartment'])) {
            $details .= "Apartment: " . $customer['apartment'] . "\n";
        }
    }
    
    $details .= "\nItems:\n";
    $subtotal = 0;
    $itemsHtml = '';
    
    foreach ($cart as $item) {
        $itemPrice = floatval($item['totalPrice'] ?? 0);
        $subtotal += $itemPrice;
        $itemName = htmlspecialchars($item['name'] ?? '');
        $itemDetails = htmlspecialchars($item['details'] ?? '');
        
        $details .= "- $itemName ($itemDetails) x 1: €" . number_format($itemPrice, 2) . "\n";
        
        $noteHtml = '';
        if (!empty($item['note'])) {
            $details .= "  Note: " . $item['note'] . "\n";
            $noteHtml = '<div class="item-note">Note: ' . htmlspecialchars($item['note']) . '</div>';
        }

        $itemsHtml .= '
        <tr>
            <td>
                <strong>' . $itemName . '</strong>
                <div class="item-spec">' . $itemDetails . '</div>
                ' . $noteHtml . '
            </td>
            <td style="text-align: right; font-weight: bold; vertical-align: middle;">€' . number_format($itemPrice, 2) . '</td>
        </tr>';
    }
    
    $vat = $subtotal * 0.09;
    $grandTotal = $subtotal + $vat;
    
    $details .= "\nSubtotal: €" . number_format($subtotal, 2) . "\n";
    $details .= "VAT (9%): €" . number_format($vat, 2) . "\n";
    $details .= "Total: €" . number_format($grandTotal, 2);

    // 2. Build HTML email template
    $orderTypeBadge = $orderType === 'delivery' 
        ? '<span class="badge badge-delivery">🛵 Delivery</span>' 
        : '<span class="badge badge-pickup">🛍️ Pickup</span>';

    $paymentMethod = $orderData['payment_method'] ?? 'cash_on_delivery';
    $paymentMethodLabels = [
        'ideal' => 'iDEAL / Wero',
        'card' => 'Credit Card',
        'cash_on_delivery' => 'Cash on Delivery',
        'card_on_delivery' => 'Card on Delivery'
    ];
    $paymentLabel = $paymentMethodLabels[$paymentMethod] ?? ucfirst(str_replace('_', ' ', $paymentMethod));
    
    $isOnline = ($paymentMethod === 'ideal' || $paymentMethod === 'card');
    $paymentMethodBadge = $isOnline 
        ? '<span class="badge badge-paid">💳 ' . $paymentLabel . ' (Paid)</span>' 
        : '<span class="badge badge-cod">💵 ' . $paymentLabel . ' (COD)</span>';

    $addressHtml = '';
    if ($orderType === 'delivery') {
        $addressStr = htmlspecialchars($customer['address'] ?? '');
        if (!empty($customer['apartment'])) {
            $addressStr .= ', Apartment: ' . htmlspecialchars($customer['apartment']);
        }
        $city = htmlspecialchars($customer['city'] ?? '');
        $postalCode = htmlspecialchars($customer['postal_code'] ?? '');
        $company = !empty($customer['company']) ? '<br>Company: ' . htmlspecialchars($customer['company']) : '';
        
        $addressHtml = '
        <div class="details-row">
            <div class="details-label">Address:</div>
            <div class="details-value">' . $addressStr . '<br>' . $postalCode . ' ' . $city . $company . '</div>
        </div>';
    }

    $siteUrl = SITE_URL;
    $subtotalFormatted = number_format($subtotal, 2);
    $vatFormatted = number_format($vat, 2);
    $totalFormatted = number_format($grandTotal, 2);

    $emailHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; color: #333333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden; }
        .header { background: linear-gradient(135deg, #d35400, #e67e22); padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 26px; font-weight: 700; letter-spacing: 1px; }
        .header p { margin: 5px 0 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 30px; }
        .details-grid { display: table; width: 100%; margin-bottom: 25px; border-bottom: 1px solid #eeeeee; padding-bottom: 15px; }
        .details-row { display: table-row; }
        .details-label { display: table-cell; font-weight: bold; width: 120px; padding: 6px 0; color: #666; font-size: 14px; }
        .details-value { display: table-cell; padding: 6px 0; font-size: 14px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .items-table th { background-color: #fcfcfc; text-align: left; padding: 12px 10px; border-bottom: 2px solid #eeeeee; font-size: 14px; color: #555; }
        .items-table td { padding: 12px 10px; border-bottom: 1px solid #eeeeee; font-size: 14px; vertical-align: top; }
        .item-spec { font-size: 12px; color: #777; margin-top: 3px; }
        .item-note { font-size: 12px; color: #d35400; font-style: italic; margin-top: 3px; }
        .summary { float: right; width: 250px; margin-top: 10px; }
        .summary-row { display: table; width: 100%; margin-bottom: 8px; }
        .summary-label { display: table-cell; text-align: left; font-size: 14px; color: #666; }
        .summary-value { display: table-cell; text-align: right; font-weight: bold; font-size: 14px; }
        .summary-total { font-size: 18px; color: #d35400; border-top: 2px solid #eeeeee; padding-top: 10px; margin-top: 10px; }
        .summary-total .summary-value { font-size: 22px; }
        .footer { background: #f4f6f7; text-align: center; padding: 20px; font-size: 12px; color: #777777; border-top: 1px solid #eeeeee; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-delivery { background: #e8f4fd; color: #1da1f2; }
        .badge-pickup { background: #e8f8f5; color: #2ecc71; }
        .badge-paid { background: #eafaf1; color: #2ecc71; }
        .badge-cod { background: #fef9e7; color: #f1c40f; }
        .clearfix { clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔥 NEW ORDER RECEIVED</h1>
            <p>Old Dhaka Biryani - Merchant Dashboard</p>
        </div>
        <div class="content">
            <div class="details-grid">
                <div class="details-row">
                    <div class="details-label">Order ID:</div>
                    <div class="details-value"><strong>#{$orderId}</strong></div>
                </div>
                <div class="details-row">
                    <div class="details-label">Customer Name:</div>
                    <div class="details-value">{$customerName}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Phone Number:</div>
                    <div class="details-value">{$customerPhone}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Order Type:</div>
                    <div class="details-value">{$orderTypeBadge}</div>
                </div>
                {$addressHtml}
                <div class="details-row">
                    <div class="details-label">Payment Method:</div>
                    <div class="details-value">{$paymentMethodBadge}</div>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item Details</th>
                        <th style="text-align: right; width: 100px;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-row">
                    <div class="summary-label">Subtotal:</div>
                    <div class="summary-value">€{$subtotalFormatted}</div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">VAT (9%):</div>
                    <div class="summary-value">€{$vatFormatted}</div>
                </div>
                <div class="summary-row summary-total">
                    <div class="summary-label">Total:</div>
                    <div class="summary-value">€{$totalFormatted}</div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="footer">
            <p>&copy; {$siteUrl} Old Dhaka Biryani. All rights reserved.</p>
            <p>Sent from <a href="{$siteUrl}" style="color: #d35400; text-decoration: none;">{$siteUrl}</a></p>
        </div>
    </div>
</body>
</html>
HTML;

    // 3. Send Email (SMTP with mail fallback)
    if (!empty(ADMIN_EMAIL)) {
        $subject = "New Order #$orderId - Old Dhaka Biryani";
        sendSmtpEmail(ADMIN_EMAIL, $subject, $emailHtml, ADMIN_EMAIL, 'Old Dhaka Biryani');
    }

    // 4. Send WhatsApp (Meta Business Cloud API)
    if (!empty(WHATSAPP_PHONE_NUMBER_ID) && !empty(WHATSAPP_ACCESS_TOKEN) && !empty(ADMIN_WHATSAPP)) {
        // Strip out non-numeric characters from the admin's phone number
        $cleanPhone = preg_replace('/\D/', '', ADMIN_WHATSAPP);

        $params = array(
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'text',
            'text' => array(
                'preview_url' => false,
                'body' => "🔥 *New Order Received!*\n\n" . $details
            )
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/v20.0/" . WHATSAPP_PHONE_NUMBER_ID . "/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . WHATSAPP_ACCESS_TOKEN,
                "Content-Type: application/json"
            ),
        ));
        
        $whatsapp_response = curl_exec($curl);
        $whatsapp_http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($whatsapp_http_code !== 200) {
            error_log("WhatsApp Cloud API failed (HTTP $whatsapp_http_code): " . $whatsapp_response);
        }
    } else {
        error_log("WhatsApp API skipped: WHATSAPP_PHONE_NUMBER_ID, WHATSAPP_ACCESS_TOKEN, or ADMIN_WHATSAPP is not configured.");
    }
}
?>
