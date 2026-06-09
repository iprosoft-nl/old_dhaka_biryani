<?php include 'includes/header.php'; ?>

<main class="menu-page">
    <div class="container">
        <div class="menu-layout">
            <div class="menu-main">
                <div class="header" style="text-align: center; margin-bottom: 40px; margin-top: 40px;">
                    <h1 id="pageTitle"><?php echo __('nav_menu'); ?></h1>
                    <p id="pageIntro"><?php echo __('hero_tagline'); ?></p>
                </div>

                <div id="menuContent">
                    <!-- Menu items will be injected here via JavaScript -->
                </div>
            </div>

            <aside class="cart-sidebar-wrapper">
                <div class="cart-sidebar">
                    <div class="cart-header">
                        <span id="cartTitle">Your Order</span>
                        <span id="cartCount" class="badge">0</span>
                    </div>
                    <div class="cart-items" id="cartItems">
                        <p style="text-align: center; color: #999; padding: 20px;" id="emptyMsg">Your cart is empty</p>
                    </div>
                    <div class="cart-summary">
                        <div class="summary-row">
                            <span><?php echo __('subtotal'); ?></span>
                            <span id="subtotalAmount">€0.00</span>
                        </div>
                        <div class="summary-row">
                            <span><?php echo __('vat'); ?></span>
                            <span id="vatAmount">€0.00</span>
                        </div>
                        <div class="total-row">
                            <span><?php echo __('total'); ?></span>
                            <span id="totalAmount">€0.00</span>
                        </div>
                    </div>
                    <p class="vat-notice" style="font-size: 11px; color: #888; margin-top: 10px; text-align: center;">
                        <?php echo __('vat_notice'); ?>
                    </p>
                    <button class="checkout-btn" id="checkoutBtn" disabled><?php echo __('order_now'); ?></button>
                </div>
            </aside>
        </div>
    </div>
</main>

<style>
    .menu-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        margin-bottom: 60px;
    }

    @media (max-width: 1000px) {
        .menu-layout {
            grid-template-columns: 1fr;
        }
        .cart-sidebar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            max-height: 60vh;
            border-radius: 20px 20px 0 0 !important;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
        }
        .cart-sidebar-wrapper {
            height: 80px; /* Space for the fixed cart button on mobile */
        }
    }

    .section-header {
        font-size: 24px;
        color: var(--accent-color);
        margin: 40px 0 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid var(--secondary-color);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: bold;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    .menu-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid #ead8bd;
        display: flex;
        flex-direction: column;
        gap: 15px;
        transition: transform 0.2s;
    }

    .menu-card:hover {
        transform: translateY(-5px);
    }

    .menu-image-wrapper {
        position: relative;
        width: 100%;
        height: 180px;
        border-radius: 15px;
        overflow: hidden;
    }

    .menu-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .halal-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.9);
        padding: 5px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #27ae60;
        font-weight: bold;
        font-size: 10px;
        border: 1px solid #27ae60;
    }

    .menu-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .menu-header h2 {
        color: var(--accent-color);
        font-size: 20px;
        line-height: 1.2;
    }

    .price-tag {
        background: var(--primary-color);
        color: white;
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: bold;
    }

    .description {
        font-size: 14px;
        color: var(--light-text);
    }

    .option-group {
        background: #faf6ef;
        border-radius: 12px;
        padding: 12px;
        border: 1px solid #eadcc6;
    }

    .section-title-sm {
        font-size: 13px;
        font-weight: bold;
        color: var(--accent-color);
        margin-bottom: 8px;
    }

    .option-group label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        cursor: pointer;
        font-size: 13px;
        border-bottom: 1px solid #eee2d0;
    }

    .option-group label:last-child {
        border-bottom: none;
    }

    .add-to-cart-btn {
        background: var(--secondary-color);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
    }

    .add-to-cart-btn:hover {
        background: var(--primary-color);
    }

    /* Cart Sidebar */
    .cart-sidebar {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #ead8bd;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .cart-header {
        font-size: 18px;
        font-weight: bold;
        color: var(--accent-color);
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge {
        background: var(--primary-color);
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
    }

    .cart-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 15px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-weight: bold;
    }

    .cart-item-details {
        font-size: 11px;
        color: #777;
    }

    .cart-item-price {
        font-weight: bold;
        margin-left: 10px;
    }

    .cart-summary {
        border-top: 2px solid var(--secondary-color);
        padding-top: 15px;
        margin-top: 10px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: bold;
        color: var(--accent-color);
        margin-top: 10px;
    }

    .checkout-btn {
        width: 100%;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 15px;
        transition: 0.3s;
    }

    .checkout-btn:hover {
        background: var(--accent-color);
    }

    .checkout-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .special-instructions {
        width: 100%;
        border-radius: 8px;
        border: 1px solid #d9c8b0;
        padding: 8px;
        font-size: 12px;
        margin-top: 10px;
        resize: vertical;
    }
</style>

<script>
    const currentLang = '<?php echo $current_lang; ?>';
    
    const menuData = {
        en: {
            sizeTitle: "Size (Required)",
            spiceTitle: "Spice Level",
            extrasTitle: "EXTRAS",
            instructions: "Special Instructions",
            placeholder: "Example: Less spicy, no onion...",
            addToCart: "Add to Order",
            emptyCart: "Your cart is empty",
            sizes: [
                ["Small", 0],
                ["Medium", 3.00],
                ["Large", 6.00],
                ["Family", 10.00]
            ],
            spices: ["Mild", "Traditional", "Bangladeshi Hot"],
            sections: [
                {
                    title: "Traditional Biryanis",
                    showSize: true,
                    items: [
                        { 
                            id: 1,
                            name: "Chicken Biryani", 
                            price: 13.95, 
                            image: "assets/img/chicken.jpg", 
                            desc: "Tender slow-cooked chicken layered with aromatic basmati rice, caramelized onions, and traditional Old Dhaka spices.",
                            extras: [["None", 0], ["Extra Chicken", 3.50], ["Extra Rice", 2.00]]
                        },
                        { 
                            id: 2,
                            name: "Lamb Biryani", 
                            price: 16.95, 
                            image: "assets/img/lamb.jpg", 
                            desc: "Tender slow-cooked lamb with saffron basmati rice, golden onions, and rich Old Dhaka flavors.",
                            extras: [["None", 0], ["Extra Lamb", 4.50], ["Extra Rice", 2.00]]
                        },
                        { 
                            id: 3,
                            name: "Beef Biryani", 
                            price: 17.95, 
                            image: "assets/img/beef.jpg", 
                            desc: "Slow-braised beef layered with fragrant basmati rice and bold traditional Old Dhaka spices.",
                            extras: [["None", 0], ["Extra Beef", 5.50], ["Extra Rice", 2.00]]
                        }
                    ]
                },
                {
                    title: "COMBO DEALS",
                    showSize: false,
                    items: [
                        { 
                            id: 4,
                            name: "FAMILY BIRYANI BOX", 
                            price: 42.95, 
                            image: "assets/img/family-box.jpg", 
                            desc: "2 Chicken Biryani, 1 Lamb Biryani, 2 drinks.",
                            extras: [["None", 0], ["Extra Chicken", 3.50], ["Extra Lamb", 4.50], ["Extra Rice", 2.00]]
                        },
                        { 
                            id: 5,
                            name: "STUDENT DEAL", 
                            price: 15.95, 
                            image: "assets/img/student-deal.jpg", 
                            desc: "Chicken Biryani and 1 drink.",
                            extras: [["None", 0], ["Extra Chicken", 3.50], ["Extra Rice", 2.00]]
                        }
                    ]
                }
            ]
        },
        nl: {
            sizeTitle: "Grootte (Verplicht)",
            spiceTitle: "Kruidenniveau",
            extrasTitle: "EXTRA'S",
            instructions: "Speciale Instructies",
            placeholder: "Bijv: Minder pittig, geen ui...",
            addToCart: "Toevoegen",
            emptyCart: "Uw winkelwagen is leeg",
            sizes: [
                ["Klein", 0],
                ["Medium", 3.00],
                ["Groot", 6.00],
                ["Familie", 10.00]
            ],
            spices: ["Mild", "Traditioneel", "Bengaals Pittig"],
            sections: [
                {
                    title: "Traditionele Biryanis",
                    showSize: true,
                    items: [
                        { 
                            id: 1,
                            name: "Kip Biryani", 
                            price: 13.95, 
                            image: "assets/img/chicken.jpg", 
                            desc: "Malse langzaam gegaarde kip gelaagd met aromatische basmati rijst, gekarameliseerde uien en traditionele Old Dhaka kruiden.",
                            extras: [["Geen", 0], ["Extra Kip", 3.50], ["Extra Rijst", 2.00]]
                        },
                        { 
                            id: 2,
                            name: "Lams Biryani", 
                            price: 16.95, 
                            image: "assets/img/lamb.jpg", 
                            desc: "Mals langzaam gegaard lamsvlees met saffraan basmati rijst, gouden uien en rijke Old Dhaka smaken.",
                            extras: [["Geen", 0], ["Extra Lam", 4.50], ["Extra Rijst", 2.00]]
                        },
                        { 
                            id: 3,
                            name: "Rund Biryani", 
                            price: 17.95, 
                            image: "assets/img/beef.jpg", 
                            desc: "Langzaam gestoofd rundvlees gelaagd met geurige basmati rijst en krachtige traditionele Old Dhaka kruiden.",
                            extras: [["Geen", 0], ["Extra Rund", 5.50], ["Extra Rijst", 2.00]]
                        }
                    ]
                },
                {
                    title: "COMBO DEALS",
                    showSize: false,
                    items: [
                        { 
                            id: 4,
                            name: "FAMILIE BIRYANI BOX", 
                            price: 42.95, 
                            image: "assets/img/family-box.jpg", 
                            desc: "2 Kip Biryani, 1 Lams Biryani, 2 drankjes.",
                            extras: [["Geen", 0], ["Extra Kip", 3.50], ["Extra Lam", 4.50], ["Extra Rijst", 2.00]]
                        },
                        { 
                            id: 5,
                            name: "STUDENTEN DEAL", 
                            price: 15.95, 
                            image: "assets/img/student-deal.jpg", 
                            desc: "Kip Biryani en 1 drankje.",
                            extras: [["Geen", 0], ["Extra Kip", 3.50], ["Extra Rijst", 2.00]]
                        }
                    ]
                }
            ]
        }
    };

    let cart = [];
    const vatRate = 0.09;

    function renderMenu() {
        const langData = menuData[currentLang];
        const menuContainer = document.getElementById('menuContent');
        menuContainer.innerHTML = '';

        langData.sections.forEach(section => {
            const sectionEl = document.createElement('div');
            sectionEl.innerHTML = `<h2 class="section-header">${section.title}</h2>`;
            
            const grid = document.createElement('div');
            grid.className = 'menu-grid';

            section.items.forEach(item => {
                const card = document.createElement('div');
                card.className = 'menu-card';
                
                let sizeHtml = '';
                if (section.showSize) {
                    sizeHtml = `
                        <div class="option-group">
                            <div class="section-title-sm">${langData.sizeTitle}</div>
                            ${langData.sizes.map((s, idx) => `
                                <label>
                                    <span><input type="radio" name="size-${item.id}" value="${s[1]}" ${idx === 0 ? 'checked' : ''}> ${s[0]}</span>
                                    <span>+€${s[1].toFixed(2)}</span>
                                </label>
                            `).join('')}
                        </div>
                    `;
                }

                card.innerHTML = `
                    <div class="menu-image-wrapper">
                        <img src="${item.image}" alt="${item.name}" class="menu-image" onerror="this.src='https://via.placeholder.com/400x250?text=${item.name}'">
                        <div class="halal-badge">HALAL</div>
                    </div>
                    <div class="menu-header">
                        <h2>${item.name}</h2>
                        <span class="price-tag">€${item.price.toFixed(2)}</span>
                    </div>
                    <p class="description">${item.desc}</p>
                    
                    ${sizeHtml}

                    <div class="option-group">
                        <div class="section-title-sm">${langData.spiceTitle}</div>
                        ${langData.spices.map((spice, idx) => `
                            <label>
                                <span><input type="radio" name="spice-${item.id}" value="${spice}" ${idx === 1 ? 'checked' : ''}> ${spice}</span>
                            </label>
                        `).join('')}
                    </div>

                    <div class="option-group">
                        <div class="section-title-sm">${langData.extrasTitle}</div>
                        ${item.extras.map((extra, idx) => `
                            <label>
                                <span><input type="radio" name="extra-${item.id}" value="${extra[1]}" ${idx === 0 ? 'checked' : ''}> ${extra[0]}</span>
                                <span>+€${extra[1].toFixed(2)}</span>
                            </label>
                        `).join('')}
                    </div>

                    <textarea class="special-instructions" id="note-${item.id}" placeholder="${langData.placeholder}"></textarea>
                    
                    <button class="add-to-cart-btn" onclick="addToCart(${item.id})">${langData.addToCart}</button>
                `;
                grid.appendChild(card);
            });
            sectionEl.appendChild(grid);
            menuContainer.appendChild(sectionEl);
        });
    }

    function addToCart(itemId) {
        const langData = menuData[currentLang];
        let item;
        for (const section of langData.sections) {
            item = section.items.find(i => i.id === itemId);
            if (item) break;
        }

        const sizeRadio = document.querySelector(`input[name="size-${itemId}"]:checked`);
        const spiceRadio = document.querySelector(`input[name="spice-${itemId}"]:checked`);
        const extraRadio = document.querySelector(`input[name="extra-${itemId}"]:checked`);
        const note = document.getElementById(`note-${itemId}`).value;

        const sizePrice = sizeRadio ? parseFloat(sizeRadio.value) : 0;
        const extraPrice = extraRadio ? parseFloat(extraRadio.value) : 0;
        
        const sizeName = sizeRadio ? sizeRadio.parentElement.textContent.trim().split('+')[0] : '';
        const spiceName = spiceRadio ? spiceRadio.parentElement.textContent.trim() : '';
        const extraName = extraRadio ? extraRadio.parentElement.textContent.trim().split('+')[0] : '';

        const cartItem = {
            id: Date.now(),
            name: item.name,
            basePrice: item.price,
            sizePrice: sizePrice,
            extraPrice: extraPrice,
            totalPrice: item.price + sizePrice + extraPrice,
            details: `${sizeName ? sizeName + ', ' : ''}${spiceName}${extraName !== 'None' && extraName !== 'Geen' ? ', ' + extraName : ''}`,
            note: note
        };

        cart.push(cartItem);
        updateCartUI();
    }

    function updateCartUI() {
        const cartItemsContainer = document.getElementById('cartItems');
        const emptyMsg = document.getElementById('emptyMsg');
        const cartCount = document.getElementById('cartCount');
        const subtotalEl = document.getElementById('subtotalAmount');
        const vatEl = document.getElementById('vatAmount');
        const totalEl = document.getElementById('totalAmount');
        const checkoutBtn = document.getElementById('checkoutBtn');

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `<p style="text-align: center; color: #999; padding: 20px;" id="emptyMsg">${menuData[currentLang].emptyCart}</p>`;
            cartCount.textContent = '0';
            subtotalEl.textContent = '€0.00';
            vatEl.textContent = '€0.00';
            totalEl.textContent = '€0.00';
            checkoutBtn.disabled = true;
            return;
        }

        cartItemsContainer.innerHTML = '';
        let subtotal = 0;

        cart.forEach((item, index) => {
            subtotal += item.totalPrice;
            const itemEl = document.createElement('div');
            itemEl.className = 'cart-item';
            itemEl.innerHTML = `
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-details">${item.details}</div>
                    ${item.note ? `<div class="cart-item-details" style="font-style: italic;">"${item.note}"</div>` : ''}
                </div>
                <div class="cart-item-price">€${item.totalPrice.toFixed(2)}</div>
                <button onclick="removeFromCart(${index})" style="background:none; border:none; color:red; cursor:pointer; margin-left:10px;"><i class="fas fa-times"></i></button>
            `;
            cartItemsContainer.appendChild(itemEl);
        });

        const vatAmount = subtotal * vatRate;
        const grandTotal = subtotal + vatAmount;

        cartCount.textContent = cart.length;
        subtotalEl.textContent = `€${subtotal.toFixed(2)}`;
        vatEl.textContent = `€${vatAmount.toFixed(2)}`;
        totalEl.textContent = `€${grandTotal.toFixed(2)}`;
        checkoutBtn.disabled = false;
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
    }

    // Initialize
    renderMenu();
</script>

<?php include 'includes/footer.php'; ?>
