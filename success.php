<?php 
// Enable error reporting for debugging on AwardSpace
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'assets/php/notifications.php';

$order_id = $_GET['order_id'] ?? null;
$type = $_GET['type'] ?? 'online';

$orderData = null;
$telegramMessage = '';

$debug_log = __DIR__ . '/debug.log';
file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Success page loaded for order: " . ($order_id ?? 'NONE') . "\n", FILE_APPEND);

// Fallback: If session is lost, try to read from temporary storage
$orderFile = __DIR__ . '/assets/orders/order_' . $order_id . '.json';

if ($order_id && isset($_SESSION['orders'][$order_id])) {
    $orderData = $_SESSION['orders'][$order_id];
} elseif ($order_id && file_exists($orderFile)) {
    $orderData = json_decode(file_get_contents($orderFile), true);
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Order data recovered from file storage.\n", FILE_APPEND);
}

if ($orderData) {
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Order data found. Notified status: " . ($orderData['notified'] ? 'YES' : 'NO') . "\n", FILE_APPEND);
    
    // For debugging: force notification if 'force=1' is in URL
    $force = isset($_GET['force']);
    
    // If it's an online payment and not yet notified, check status
    if (!$orderData['notified'] || $force) {
        $payment_id = $orderData['payment_id'] ?? null;
        $isPaid = ($orderData['payment_method'] === 'cash_on_delivery' || $orderData['payment_method'] === 'card_on_delivery');

        if ($payment_id && !$isPaid) {
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

        file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Payment verification result: " . ($isPaid ? 'PAID' : 'NOT PAID') . "\n", FILE_APPEND);

        if ($isPaid) {
            // 1. Send Email (Server-side)
            $emailResult = sendOrderNotifications($orderData);
            file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Email send result: " . ($emailResult ? 'SUCCESS' : 'FAILED') . "\n", FILE_APPEND);
            
            // 2. Prepare Telegram Message (for Browser-side trigger)
            $telegramMessage = buildOrderDetails($orderData);
            
            $_SESSION['orders'][$order_id]['notified'] = true;
        }
    }
} else {
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] ERROR: Order ID not found in session.\n", FILE_APPEND);
}

include 'includes/header.php'; 
?>

<main style="padding: 100px 0; text-align: center;">
    <div class="container">
        <div class="success-card" style="background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <i class="fas fa-check-circle" style="font-size: 80px; color: #27ae60; margin-bottom: 20px;"></i>
            <h1 style="color: var(--accent-color); margin-bottom: 10px;">Thank You for Your Order!</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Your order <strong>#<?php echo htmlspecialchars($order_id ?? 'N/A'); ?></strong> has been received and is being prepared with love.</p>
            <p style="color: #888; margin-bottom: 40px;">A confirmation has been sent to our kitchen. You will receive updates via Phone shortly.</p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </div>
</main>

<script>
    // Clear cart after successful order
    localStorage.removeItem('obd_cart');

    // Increment order count for returning customer discount
    let orderCount = parseInt(localStorage.getItem('obd_order_count') || '0');
    localStorage.setItem('obd_order_count', (orderCount + 1).toString());

    <?php if (!empty($telegramMessage)): ?>
    // Browser-side Telegram Trigger (Bypasses AwardSpace restrictions)
    const botToken = '<?php echo TELEGRAM_BOT_TOKEN; ?>';
    const chatId = '<?php echo TELEGRAM_CHAT_ID; ?>';
    const message = `🔥 *New Order Received!*\n\n<?php echo str_replace(["\r", "\n", "`"], ["", "\\n", "\\`"], $telegramMessage); ?>`;

    if (botToken && chatId) {
        fetch(`https://api.telegram.org/bot${botToken}/sendMessage`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                chat_id: chatId,
                text: message,
                parse_mode: 'Markdown'
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Telegram Success:', data);
            if (!data.ok) alert('Telegram API Error: ' + data.description);
        })
        .catch(error => {
            console.error('Telegram Error:', error);
            alert('Telegram Network Error: ' + error.message);
        });
    } else {
        alert('Telegram Config Missing: Bot Token or Chat ID is empty.');
    }
    <?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>
