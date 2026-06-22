<?php
require_once 'assets/php/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// In a real scenario, you'd use Composer to load Mollie: 
// require "vendor/autoload.php";
// $mollie = new \Mollie\Api\MollieApiClient();
// $mollie->setApiKey(MOLLIE_API_KEY);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['cart'])) {
        echo json_encode(['error' => 'Invalid order data']);
        exit;
    }

    $order_id = time(); // Generate a unique order ID
    $total_amount = $data['total'];
    $payment_method = $data['payment_method'];

    // Handle Cash or Card on Delivery (No online payment needed)
    if ($payment_method === 'cash_on_delivery' || $payment_method === 'card_on_delivery') {
        $orderData = [
            'order_id' => $order_id,
            'customer' => $data['customer'],
            'cart' => $data['cart'],
            'total' => $total_amount,
            'order_type' => $data['order_type'],
            'payment_method' => $payment_method
        ];

        require_once 'assets/php/notifications.php';
        sendOrderNotifications($orderData);

        $_SESSION['orders'][$order_id] = $orderData;
        $_SESSION['orders'][$order_id]['notified'] = true;

        // Save to file as fallback for session loss on AwardSpace
        $orderDir = __DIR__ . '/assets/orders';
        if (!is_dir($orderDir)) mkdir($orderDir, 0777, true);
        file_put_contents($orderDir . '/order_' . $order_id . '.json', json_encode($_SESSION['orders'][$order_id]));

        echo json_encode([
            'success' => true,
            'redirectUrl' => 'success.php?order_id=' . $order_id . '&type=offline'
        ]);
        exit;
    }

    // Handle Online Payments (iDEAL or Credit Card)
    try {
        // Map payment methods to Mollie expected values
        $mollieMethod = null;
        if ($payment_method === 'ideal') {
            $mollieMethod = 'ideal';
        } elseif ($payment_method === 'card') {
            $mollieMethod = 'creditcard';
        }

        $redirectUrl = SITE_URL . "/success.php?order_id=" . $order_id;

        // Create payment on Mollie using cURL (bypassing the need for Composer / SDK)
        $url = 'https://api.mollie.com/v2/payments';
        $payload = [
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format($total_amount, 2, '.', '')
            ],
            'description' => 'Order #' . $order_id . ' - Old Dhaka Biryani',
            'redirectUrl' => $redirectUrl,
            'metadata' => [
                'order_id' => $order_id
            ]
        ];

        if ($mollieMethod) {
            $payload['method'] = $mollieMethod;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . MOLLIE_API_KEY,
            'Content-Type: application/json'
        ]);

        $responseJson = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($responseJson, true);

        if ($httpCode !== 201) {
            $errorMessage = $response['detail'] ?? ($response['title'] ?? 'Mollie API error');
            throw new Exception($errorMessage);
        }

        $checkoutUrl = $response['_links']['checkout']['href'] ?? null;
        if (!$checkoutUrl) {
            throw new Exception('Mollie checkout URL not found in API response.');
        }

        // Save order data to session (online payment verification required on success page)
        $orderData = [
            'order_id' => $order_id,
            'customer' => $data['customer'],
            'cart' => $data['cart'],
            'total' => $total_amount,
            'order_type' => $data['order_type'],
            'payment_method' => $payment_method,
            'payment_id' => $response['id'],
            'notified' => false
        ];
        $_SESSION['orders'][$order_id] = $orderData;

        // Save to file as fallback for session loss on AwardSpace
        $orderDir = __DIR__ . '/assets/orders';
        if (!is_dir($orderDir)) mkdir($orderDir, 0777, true);
        file_put_contents($orderDir . '/order_' . $order_id . '.json', json_encode($orderData));

        echo json_encode([
            'success' => true,
            'redirectUrl' => $checkoutUrl
        ]);

    } catch (Exception $e) {
        echo json_encode(['error' => 'Payment creation failed: ' . $e->getMessage()]);
    }
}
?>
