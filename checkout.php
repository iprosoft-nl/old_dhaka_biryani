<?php include 'includes/header.php'; ?>

<main class="checkout-page">
    <div class="container">
        <div class="checkout-layout">
            <div class="checkout-main">
                <h1 class="checkout-title"><?php echo __('checkout_title'); ?></h1>

                <!-- Returning Customer Banner -->
                <div id="returningCustomerBanner" class="discount-banner" style="display: none;">
                    <div class="banner-content">
                        <img src="assets/img/discount-badge.png" alt="10% Discount" class="banner-badge">
                        <div class="banner-text">
                            <h3>Welcome Back!</h3>
                            <p>As a valued returning customer, you've unlocked a <strong>10% Loyalty Discount</strong> on this order!</p>
                        </div>
                    </div>
                </div>
                
                <form id="checkoutForm">
                    <!-- Order Type Selection -->
                    <div class="checkout-section">
                        <h2 class="section-title-sm"><?php echo __('order_type'); ?></h2>
                        <div class="order-type-options">
                            <label class="order-type-label">
                                <input type="radio" name="order_type" value="pickup" checked>
                                <div class="type-card">
                                    <i class="fas fa-store"></i>
                                    <span><?php echo __('pickup'); ?></span>
                                </div>
                            </label>
                            <label class="order-type-label">
                                <input type="radio" name="order_type" value="delivery">
                                <div class="type-card">
                                    <i class="fas fa-motorcycle"></i>
                                    <span><?php echo __('delivery'); ?></span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="checkout-section">
                        <h2 class="section-title-sm"><?php echo __('contact_info'); ?></h2>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" class="form-control" name="first_name" placeholder="<?php echo __('first_name'); ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="last_name" placeholder="<?php echo __('last_name'); ?> *" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone" placeholder="<?php echo __('phone'); ?> *" required>
                        </div>
                    </div>

                    <!-- Delivery Address Form (Hidden by default, shown when delivery selected) -->
                    <div id="deliverySection" class="checkout-section" style="display: none;">
                        <h2 class="section-title-sm"><?php echo __('delivery'); ?></h2>
                        <div class="form-group">
                            <select class="form-control" name="country">
                                <option value="Netherlands">Netherlands</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="company" placeholder="Company (optional)">
                        </div>
                        <div class="form-group search-input">
                            <input type="text" class="form-control" name="address" placeholder="<?php echo __('address'); ?> *" required>
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="apartment" placeholder="<?php echo __('apartment'); ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" class="form-control" name="postal_code" placeholder="<?php echo __('postal_code'); ?> *" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="city" placeholder="<?php echo __('city'); ?> *" required>
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="checkout-section" style="margin-top: -20px; margin-bottom: 20px;">
                        <div class="form-checkbox">
                            <label><input type="checkbox" name="save_info"> Save this information for next time</label>
                        </div>
                        <div class="form-checkbox">
                            <label><input type="checkbox" name="marketing"> Text me with news and offers</label>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="checkout-section">
                        <h2 class="section-title-sm"><?php echo __('payment_method'); ?></h2>
                        <p style="font-size: 13px; color: #666; margin-bottom: 15px;">All transactions are secure and encrypted.</p>
                        <div class="payment-options">
                            <label class="payment-option">
                                <div class="payment-option-header">
                                    <input type="radio" name="payment_method" value="ideal" checked>
                                    <span><?php echo __('ideal_wero'); ?></span>
                                    <div class="payment-icons">
                                        <img src="https://img.icons8.com/color/48/ideal.png" alt="iDEAL" width="24">
                                    </div>
                                </div>
                            </label>
                            <label class="payment-option">
                                <div class="payment-option-header">
                                    <input type="radio" name="payment_method" value="card">
                                    <span><?php echo __('credit_card'); ?></span>
                                    <div class="payment-icons">
                                        <i class="fab fa-cc-visa"></i>
                                        <i class="fab fa-cc-mastercard"></i>
                                        <i class="fab fa-cc-amex"></i>
                                    </div>
                                </div>
                            </label>
                            <label class="payment-option delivery-only-payment" style="display: none;">
                                <div class="payment-option-header">
                                    <input type="radio" name="payment_method" value="cash_on_delivery">
                                    <span><?php echo __('cash_on_delivery'); ?></span>
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </label>
                            <label class="payment-option delivery-only-payment" style="display: none;">
                                <div class="payment-option-header">
                                    <input type="radio" name="payment_method" value="card_on_delivery">
                                    <span><?php echo __('card_on_delivery'); ?></span>
                                    <i class="fas fa-credit-card"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="place-order-btn"><?php echo __('place_order'); ?></button>
                </form>
            </div>

            <aside class="checkout-sidebar">
                <div class="order-summary-box">
                    <h2 class="section-title-sm"><?php echo __('your_order'); ?></h2>
                    <div id="checkoutCartItems">
                        <!-- Items from localStorage -->
                    </div>
                    <div class="cart-summary">
                        <div class="summary-row">
                            <span><?php echo __('subtotal'); ?></span>
                            <span id="subtotalAmount">€0.00</span>
                        </div>
                        <div id="discountRow" class="summary-row" style="display: none; color: #27ae60; font-weight: bold;">
                            <span>Returning Customer Discount (10%)</span>
                            <span id="discountAmount">-€0.00</span>
                        </div>
                        <div class="summary-row">
                            <span><?php echo __('vat'); ?></span>
                            <span id="vatAmount">€0.00</span>
                        </div>
                        <div class="total-row">
                            <span><?php echo __('total'); ?></span>
                            <span id="totalAmount" style="font-size: 24px;">€0.00</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<style>
    .checkout-page { padding: 40px 0; background: #f9f9f9; min-height: 80vh; }
    .checkout-layout { display: grid; grid-template-columns: 1fr 400px; gap: 40px; }
    .checkout-main { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .checkout-title { font-size: 28px; margin-bottom: 30px; color: var(--accent-color); }
    
    /* Discount Banner Styles */
    .discount-banner { background: linear-gradient(135deg, #fff9f4 0%, #fff0e6 100%); border: 2px dashed #e67e22; border-radius: 15px; padding: 20px; margin-bottom: 30px; position: relative; overflow: hidden; animation: pulse 2s infinite; }
    .banner-content { display: flex; align-items: center; gap: 20px; position: relative; z-index: 1; }
    .banner-badge { width: 80px; height: 80px; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1)); }
    .banner-text h3 { margin: 0; color: #d35400; font-size: 20px; }
    .banner-text p { margin: 5px 0 0; color: #555; font-size: 14px; }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(230, 126, 34, 0.4); }
        70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(230, 126, 34, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(230, 126, 34, 0); }
    }

    .checkout-section { margin-bottom: 40px; }
    .section-title-sm { font-size: 18px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    
    /* Order Type Options */
    .order-type-options { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .order-type-label { cursor: pointer; }
    .order-type-label input { display: none; }
    .type-card { border: 2px solid #eee; padding: 20px; border-radius: 12px; text-align: center; transition: all 0.3s; }
    .type-card i { font-size: 24px; display: block; margin-bottom: 10px; color: #666; }
    .order-type-label input:checked + .type-card { border-color: var(--primary-color); background: #fff9f4; }
    .order-type-label input:checked + .type-card i { color: var(--primary-color); }

    /* Form Styles */
    .form-group { margin-bottom: 15px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
    .search-input { position: relative; }
    .search-input i { position: absolute; right: 15px; top: 15px; color: #999; }
    .form-checkbox { margin-bottom: 10px; font-size: 14px; color: #666; }

    /* Payment Options */
    .payment-options { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
    .payment-option { display: block; cursor: pointer; border-bottom: 1px solid #eee; }
    .payment-option:last-child { border-bottom: none; }
    .payment-option-header { padding: 15px; display: flex; align-items: center; gap: 15px; font-size: 14px; font-weight: 600; }
    .payment-icons { margin-left: auto; display: flex; gap: 10px; color: #666; font-size: 20px; }
    .payment-option input:checked + span { color: var(--primary-color); }
    .payment-option:has(input:checked) { background: #fcfcfc; }

    /* Sidebar */
    .checkout-sidebar { position: sticky; top: 100px; height: fit-content; }
    .order-summary-box { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .checkout-item { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; font-size: 14px; }
    .checkout-item-info { flex: 1; }
    .checkout-item-name { font-weight: bold; }
    .checkout-item-details { font-size: 12px; color: #777; }
    .checkout-item-price { font-weight: bold; margin-left: 15px; }

    .place-order-btn { width: 100%; background: var(--primary-color); color: white; border: none; padding: 18px; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: 0.3s; }
    .place-order-btn:hover { background: var(--accent-color); }

    @media (max-width: 1000px) {
        .checkout-layout { grid-template-columns: 1fr; }
        .checkout-sidebar { order: -1; position: static; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const orderTypeRadios = document.querySelectorAll('input[name="order_type"]');
        const deliverySection = document.getElementById('deliverySection');
        const deliveryOnlyPayments = document.querySelectorAll('.delivery-only-payment');
        const cartItemsContainer = document.getElementById('checkoutCartItems');
        const subtotalEl = document.getElementById('subtotalAmount');
        const vatEl = document.getElementById('vatAmount');
        const totalEl = document.getElementById('totalAmount');

        // Load cart from localStorage (passed from menu.php)
        let cart = JSON.parse(localStorage.getItem('obd_cart')) || [];
        const vatRate = 0.09;

        function updateSummary() {
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p style="text-align: center; color: #999;">Your cart is empty</p>';
                return;
            }

            cartItemsContainer.innerHTML = '';
            let subtotal = 0;

            cart.forEach(item => {
                subtotal += item.totalPrice;
                const itemEl = document.createElement('div');
                itemEl.className = 'checkout-item';
                itemEl.innerHTML = `
                    <div class="checkout-item-info">
                        <div class="checkout-item-name">${item.name}</div>
                        <div class="checkout-item-details">${item.details}</div>
                    </div>
                    <div class="checkout-item-price">€${item.totalPrice.toFixed(2)}</div>
                `;
                cartItemsContainer.appendChild(itemEl);
            });

            // Apply 10% discount if order count > 0
            const orderCount = parseInt(localStorage.getItem('obd_order_count') || '0');
            let discountAmount = 0;
            const discountRow = document.getElementById('discountRow');
            const discountAmountEl = document.getElementById('discountAmount');

            if (orderCount > 0) {
                discountAmount = subtotal * 0.10;
                discountRow.style.display = 'flex';
                discountAmountEl.textContent = `-€${discountAmount.toFixed(2)}`;
                document.getElementById('returningCustomerBanner').style.display = 'block';
            } else {
                discountRow.style.display = 'none';
                document.getElementById('returningCustomerBanner').style.display = 'none';
            }

            const subtotalAfterDiscount = subtotal - discountAmount;
            const vatAmount = subtotalAfterDiscount * vatRate;
            const grandTotal = subtotalAfterDiscount + vatAmount;

            subtotalEl.textContent = `€${subtotal.toFixed(2)}`;
            vatEl.textContent = `€${vatAmount.toFixed(2)}`;
            totalEl.textContent = `€${grandTotal.toFixed(2)}`;
        }

        function toggleDeliveryFields(isDelivery) {
            if (isDelivery) {
                deliverySection.style.display = 'block';
                deliveryOnlyPayments.forEach(p => p.style.display = 'block');
                
                // Enable fields and make required ones required
                deliverySection.querySelectorAll('input, select').forEach(field => {
                    field.removeAttribute('disabled');
                    if (['address', 'postal_code', 'city'].includes(field.name)) {
                        field.setAttribute('required', '');
                    }
                });
            } else {
                deliverySection.style.display = 'none';
                deliveryOnlyPayments.forEach(p => p.style.display = 'none');
                
                // Disable fields and remove required attribute
                deliverySection.querySelectorAll('input, select').forEach(field => {
                    field.setAttribute('disabled', '');
                    field.removeAttribute('required');
                });
                
                // Reset payment if a delivery-only one was selected
                const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
                if (selectedPayment && (selectedPayment.value === 'cash_on_delivery' || selectedPayment.value === 'card_on_delivery')) {
                    document.querySelector('input[name="payment_method"][value="ideal"]').checked = true;
                }
            }
        }

        orderTypeRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                toggleDeliveryFields(e.target.value === 'delivery');
            });
        });

        document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            const formData = new FormData(e.target);
            const customerData = Object.fromEntries(formData.entries());
            const orderType = formData.get('order_type');
            const paymentMethod = formData.get('payment_method');

            // Calculate totals with discount
            let subtotal = 0;
            cart.forEach(item => subtotal += item.totalPrice);
            
            const orderCount = parseInt(localStorage.getItem('obd_order_count') || '0');
            const discount = orderCount > 0 ? (subtotal * 0.10) : 0;
            const total = (subtotal - discount) + ((subtotal - discount) * 0.09);

            const orderPayload = {
                customer: customerData,
                cart: cart,
                total: total,
                discount: discount,
                order_type: orderType,
                payment_method: paymentMethod
            };

            try {
                const response = await fetch('process-payment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(orderPayload)
                });

                const result = await response.json();

                if (result.success && result.redirectUrl) {
                    window.location.href = result.redirectUrl;
                } else {
                    alert('Error: ' + (result.error || 'Something went wrong'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Checkout Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });

        // Initialize based on the default selected order type
        const initialOrderType = document.querySelector('input[name="order_type"]:checked').value;
        toggleDeliveryFields(initialOrderType === 'delivery');

        updateSummary();
    });
</script>

<?php include 'includes/footer.php'; ?>
