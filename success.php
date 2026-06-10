<?php 
require_once 'assets/php/notifications.php';
include 'includes/header.php'; 

$order_id = $_GET['order_id'] ?? 'N/A';
?>

<main style="padding: 100px 0; text-align: center;">
    <div class="container">
        <div class="success-card" style="background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <i class="fas fa-check-circle" style="font-size: 80px; color: #27ae60; margin-bottom: 20px;"></i>
            <h1 style="color: var(--accent-color); margin-bottom: 10px;">Thank You for Your Order!</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Your order <strong>#<?php echo $order_id; ?></strong> has been received and is being prepared with love.</p>
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
