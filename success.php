<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'assets/php/notifications.php';

$order_id = $_GET['order_id'] ?? null;
$type = $_GET['type'] ?? 'online';

if ($order_id && isset($_SESSION['orders'][$order_id])) {
    $orderData = $_SESSION['orders'][$order_id];
    
    // If it's an online payment and not yet notified, check status
    if (!$orderData['notified']) {
        $payment_id = $orderData['payment_id'] ?? null;
        $isPaid = false;

        if ($payment_id) {
            $ch = curl_init('https://api.mollie.com/v2/payments/' . $payment_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . MOLLIE_API_KEY
            ]);
            $resJson = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $res = json_decode($resJson, true);
                if (isset($res['status']) && $res['status'] === 'paid') {
                    $isPaid = true;
                }
            }
        }

        if ($isPaid) {
            sendOrderNotifications($orderData);
            $_SESSION['orders'][$order_id]['notified'] = true;
        }
    }
}

include 'includes/header.php'; 
?>

<main style="padding: 100px 0; text-align: center;">
    <div class="container">
        <div class="success-card" style="background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <i class="fas fa-check-circle" style="font-size: 80px; color: #27ae60; margin-bottom: 20px;"></i>
            <h1 style="color: var(--accent-color); margin-bottom: 10px;">Thank You for Your Order!</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Your order <strong>#<?php echo htmlspecialchars($order_id ?? 'N/A'); ?></strong> has been received and is being prepared with love.</p>
            <p style="color: #888; margin-bottom: 40px;">A confirmation has been sent to our kitchen. You will receive updates via WhatsApp/Phone shortly.</p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </div>
</main>

<script>
    // Clear cart after successful order
    localStorage.removeItem('obd_cart');
</script>

<?php include 'includes/footer.php'; ?>
