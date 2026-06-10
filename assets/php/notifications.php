<?php
require_once 'config.php';

function sendOrderNotifications($orderData) {
    $orderId = $orderData['order_id'];
    $customer = $orderData['customer'];
    $cart = $orderData['cart'];
    $total = $orderData['total'];

    // Format Order Details
    $details = "Order ID: #$orderId\n";
    $details .= "Customer: " . ($customer['first_name'] ?? '') . " " . $customer['last_name'] . "\n";
    $details .= "Phone: " . $customer['phone'] . "\n";
    $details .= "Type: " . strtoupper($orderData['order_type']) . "\n";
    if ($orderData['order_type'] === 'delivery') {
        $details .= "Address: " . $customer['address'] . ", " . $customer['city'] . " (" . $customer['postal_code'] . ")\n";
    }
    $details .= "\nItems:\n";
    foreach ($cart as $item) {
        $details .= "- " . $item['name'] . " (" . $item['details'] . ") x 1: €" . number_format($item['totalPrice'], 2) . "\n";
        if (!empty($item['note'])) $details .= "  Note: " . $item['note'] . "\n";
    }
    $details .= "\nTotal: €" . number_format($total, 2);

    // 1. Send Email
    $subject = "New Order #$orderId - Old Dhaka Biryani";
    $headers = "From: webmaster@olddhakabiryani.com";
    mail(ADMIN_EMAIL, $subject, $details, $headers);

    // 2. Send WhatsApp (Using UltraMsg example)
    $params = array(
        'token' => WHATSAPP_TOKEN,
        'to' => ADMIN_WHATSAPP,
        'body' => "🔥 *New Order Received!*\n\n" . $details
    );
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => WHATSAPP_API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($params),
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
        ),
    ));
    curl_close($curl);
}
?>
