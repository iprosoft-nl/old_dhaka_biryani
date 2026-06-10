<?php
require_once 'assets/php/config.php';

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
        // Here you would save the order to a database
        // For this demo, we'll simulate success
        echo json_encode([
            'success' => true,
            'redirectUrl' => 'success.php?order_id=' . $order_id . '&type=offline'
        ]);
        exit;
    }

    // Handle Online Payments (iDEAL or Credit Card)
    try {
        /* 
        MOLLIE INTEGRATION LOGIC:
        
        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => number_format($total_amount, 2, '.', '')
            ],
            "description" => "Order #" . $order_id . " - Old Dhaka Biryani",
            "redirectUrl" => SITE_URL . "/success.php?order_id=" . $order_id,
            "webhookUrl"  => SITE_URL . "/webhook.php",
            "metadata" => [
                "order_id" => $order_id,
                "customer_details" => $data['customer'],
                "cart" => $data['cart']
            ],
            "method" => ($payment_method === 'ideal' ? \Mollie\Api\Types\PaymentMethod::IDEAL : \Mollie\Api\Types\PaymentMethod::CREDITCARD)
        ]);

        echo json_encode(['success' => true, 'redirectUrl' => $payment->getCheckoutUrl()]);
        */

        // MOCK RESPONSE for demo purposes
        echo json_encode([
            'success' => true, 
            'redirectUrl' => 'https://www.mollie.com/checkout/mock-payment-page?amount=' . $total_amount
        ]);

    } catch (Exception $e) {
        echo json_encode(['error' => 'Payment creation failed: ' . $e->getMessage()]);
    }
}
?>
