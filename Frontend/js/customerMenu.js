/**
 * Savouré — Restaurant Menu + In-Page Cart (Phase 1: localStorage)
 * - Cart is namespaced per customer: savoure_cart::<customerId>
 * - Pre-orders are written to a single shared array (savoure_preorders) with the
 *   customer tag inside, so staff can see all customers for their restaurant.
 * - Pickup-only for now. Dine-in will return with the reservations flow.
 */

const MENU_DATA = {
    "The Revolving Restaurant": {
        cuisine: "International Fine Dining · Cairo Tower",
        sections: [
            {
                name: "Starters",
                icon: "fa-bowl-food",
                items: [
                    { id: "rev-s1", name: "Crab Bisque", desc: "Rich Egyptian crab bisque, cognac cream, crispy croutons.", price: 620 },
                    { id: "rev-s2", name: "Wild Mushroom Velouté", desc: "Silky wild mushroom soup, white truffle oil, chives.", price: 540 },
                    { id: "rev-s3", name: "Grilled Shrimp & Coconut Sauce", desc: "Tiger prawns, coconut cream sauce, citrus zest.", price: 780 }
                ]
            },
            {
                name: "Main Courses",
                icon: "fa-drumstick-bite",
                items: [
                    { id: "rev-m1", name: "Dry-Aged Rib Eye Steak", desc: "300g prime rib eye, truffle mashed potatoes, peppercorn jus.", price: 1650 },
                    { id: "rev-m2", name: "Pan-Seared Sea Bass", desc: "Sea bass fillet, lemon butter, sautéed spinach, crispy skin.", price: 1180 },
                    { id: "rev-m3", name: "Duck Breast", desc: "Roasted duck breast, cherry reduction, seasonal vegetables.", price: 1380 },
                    { id: "rev-m4", name: "Chicken Risotto", desc: "Creamy arborio risotto, grilled chicken, aged parmesan.", price: 950 },
                    { id: "rev-m5", name: "Grilled Salmon", desc: "Atlantic salmon, mashed potatoes, dill cream sauce.", price: 1120 }
                ]
            },
            {
                name: "Desserts",
                icon: "fa-cake-candles",
                items: [
                    { id: "rev-d1", name: "Chocolate Fondant", desc: "Warm dark chocolate cake, molten center, vanilla ice cream.", price: 420 },
                    { id: "rev-d2", name: "Apple Tart", desc: "Thin apple tart, caramel drizzle, cinnamon cream.", price: 380 }
                ]
            },
            {
                name: "Tasting Menu (Per Person)",
                icon: "fa-hat-chef",
                items: [
                    { id: "rev-t1", name: "3-Course Tasting Menu", desc: "Starter + Main + Dessert. Excludes beverages and service charge.", price: 1800 },
                    { id: "rev-t2", name: "4-Course Tasting Menu", desc: "Starter + Soup + Main + Dessert. Excludes beverages and service charge.", price: 2200 }
                ]
            }
        ]
    },

    "Le Petite Chef": {
        cuisine: "French Contemporary · Theatrical Dining",
        sections: [
            {
                name: "The Petit Chef Experience (Fixed Menus)",
                icon: "fa-hat-chef",
                items: [
                    { id: "lpc-classic", name: "Classic Menu", desc: "Buffalo Tomato Salad · Truffle Mushroom Ravioli · Beef Fillet & Vegetables · Japanese Mont Blanc", price: 2500 },
                    { id: "lpc-premium", name: "Premium Menu", desc: "Buffalo Tomato Salad · Truffle Mushroom Ravioli · Beef Fillet & Grilled Shrimp · Japanese Mont Blanc", price: 3500 },
                    { id: "lpc-veg", name: "Vegetarian Menu", desc: "Buffalo Tomato Salad · Truffle Mushroom Ravioli · Vegetable & Nut Wellington · Japanese Mont Blanc", price: 2500 }
                ]
            },
            {
                name: "Add-Ons",
                icon: "fa-plus-circle",
                items: [
                    { id: "lpc-cake", name: "Celebration Cake", desc: "Add a celebration cake to your experience. Selected during booking.", price: 1000 }
                ]
            }
        ]
    },

    "Mo Bistro": {
        cuisine: "Egyptian & Mediterranean · Casual Chic",
        sections: [
            {
                name: "Soups & Starters",
                icon: "fa-bowl-food",
                items: [
                    { id: "mo-s1", name: "Mo's Bistro Almond Soup", desc: "Signature creamy almond soup, toasted almond flakes.", price: 195 },
                    { id: "mo-s2", name: "Chicken Mushroom Soup", desc: "Hearty chicken and mushroom soup, fresh thyme.", price: 255 },
                    { id: "mo-s3", name: "Shrimp Cocktail", desc: "Chilled tiger prawns, Marie Rose sauce, lemon.", price: 420 }
                ]
            },
            {
                name: "Mains",
                icon: "fa-drumstick-bite",
                items: [
                    { id: "mo-m1", name: "Lamb Shank", desc: "Slow-braised lamb shank, spiced brown rice, rich pan jus. (Guest favourite)", price: 890 },
                    { id: "mo-m2", name: "Mushroom Risotto", desc: "Creamy wild mushroom risotto, aged parmesan, truffle oil.", price: 520 },
                    { id: "mo-m3", name: "Steak Flank", desc: "Grilled flank steak, chimichurri, roasted potatoes.", price: 780 },
                    { id: "mo-m4", name: "Chicken Parmesan", desc: "Breaded chicken breast, marinara, melted mozzarella, spaghetti.", price: 620 },
                    { id: "mo-m5", name: "Chinese Beef Noodles", desc: "Wok-tossed beef noodles, soy, ginger, spring onion.", price: 580 }
                ]
            },
            {
                name: "Desserts",
                icon: "fa-cake-candles",
                items: [
                    { id: "mo-d1", name: "Tiramisu", desc: "Classic Italian tiramisu, espresso-soaked ladyfingers, mascarpone.", price: 290 },
                    { id: "mo-d2", name: "Chocolate Fondant", desc: "Warm chocolate cake, vanilla ice cream.", price: 310 }
                ]
            }
        ]
    }
};

const ORDERS_KEY = 'savoure_preorders';

/* ---------- Per-customer storage keys ---------- */
function currentCustomer() {
    return window.SAVOURE_CUSTOMER || 'guest';
}
function cartKey() {
    return 'savoure_cart::' + currentCustomer();
}

/* ---------- Cart storage ---------- */
function getCart() {
    try {
        const raw = localStorage.getItem(cartKey());
        if (raw) return JSON.parse(raw);

        // Legacy migration: an old unscoped cart from before this fix
        // belongs to the *default* account (sarah). Move it once.
        if (currentCustomer() === 'sarah') {
            const legacy = localStorage.getItem('savoure_cart');
            if (legacy) {
                const parsed = JSON.parse(legacy);
                localStorage.setItem(cartKey(), JSON.stringify(parsed));
                localStorage.removeItem('savoure_cart');
                return parsed;
            }
        }
        return {};
    } catch(e) { return {}; }
}

function saveCart(cart) {
    localStorage.setItem(cartKey(), JSON.stringify(cart));
}

function notifyCartChanged() {
    window.dispatchEvent(new Event('cart-updated'));
}

function getRestaurantCart() {
    const cart = getCart();
    const restaurant = window.SAVOURE_RESTAURANT;
    const out = {};
    Object.entries(cart).forEach(([k, v]) => {
        if (v.restaurant === restaurant) out[k] = v;
    });
    return out;
}

/* ---------- Menu rendering ---------- */
function renderMenu() {
    const restaurant = window.SAVOURE_RESTAURANT;
    const data = MENU_DATA[restaurant];
    const container = document.getElementById('menu-container');

    if (!data) {
        container.innerHTML = `<p style="color:var(--beige);font-style:italic;">Menu coming soon for this restaurant.</p>`;
        return;
    }

    document.getElementById('menu-hero-title').textContent = restaurant;
    document.getElementById('menu-hero-cuisine').textContent = data.cuisine;

    let html = '';
    data.sections.forEach(section => {
        html += `<h2 class="menu-section-title"><i class="fa-solid ${section.icon}"></i> ${section.name}</h2>`;
        html += `<div class="menu-grid">`;
        section.items.forEach(item => {
            html += `
                <div class="menu-item">
                    <div class="menu-item-info">
                        <div class="menu-item-name">${item.name}</div>
                        <div class="menu-item-desc">${item.desc}</div>
                        <div class="menu-item-price">${item.price.toLocaleString()} EGP <small>per person</small></div>
                    </div>
                    <button class="menu-item-add" onclick="addToCart('${item.id}', '${item.name.replace(/'/g, "\\'")}', ${item.price})">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>`;
        });
        html += `</div>`;
    });

    container.innerHTML = html;
}

/* ---------- Add / remove / change qty ---------- */
function addToCart(itemId, itemName, price) {
    const restaurant = window.SAVOURE_RESTAURANT;
    const cart = getCart();
    const key = `${restaurant}::${itemId}`;

    if (!cart[key]) {
        cart[key] = { restaurant, itemId, name: itemName, price, qty: 0 };
    }
    cart[key].qty += 1;
    saveCart(cart);
    notifyCartChanged();

    renderCart();
    showMenuToast(`Added: ${itemName}`);
}

function changeQty(key, delta) {
    const cart = getCart();
    if (!cart[key]) return;
    cart[key].qty += delta;
    if (cart[key].qty <= 0) delete cart[key];
    saveCart(cart);
    notifyCartChanged();
    renderCart();
}

function removeLine(key) {
    const cart = getCart();
    delete cart[key];
    saveCart(cart);
    notifyCartChanged();
    renderCart();
}

function clearRestaurantCart() {
    const cart = getCart();
    const restaurant = window.SAVOURE_RESTAURANT;
    Object.keys(cart).forEach(k => {
        if (cart[k].restaurant === restaurant) delete cart[k];
    });
    saveCart(cart);
    notifyCartChanged();
    renderCart();
}

/* ---------- Cart rendering ---------- */
function renderCart() {
    const restaurantCart = getRestaurantCart();
    const lines = Object.entries(restaurantCart);

    const bodyEl = document.getElementById('cart-body');
    const countEl = document.getElementById('cart-count');
    const fabCountEl = document.getElementById('cart-fab-count');
    const totalEl = document.getElementById('cart-total');
    const placeBtn = document.getElementById('place-order-btn');

    let totalQty = 0;
    let totalPrice = 0;
    lines.forEach(([k, v]) => { totalQty += v.qty; totalPrice += v.price * v.qty; });

    countEl.textContent = totalQty;
    if (fabCountEl) fabCountEl.textContent = totalQty;
    totalEl.textContent = totalPrice.toLocaleString() + ' EGP';
    placeBtn.disabled = totalQty === 0;

    if (lines.length === 0) {
        bodyEl.innerHTML = `
            <div class="cart-empty">
                <i class="fa-solid fa-bag-shopping"></i>
                Your cart is empty.<br>Tap "Add" on any dish to begin.
            </div>`;
        return;
    }

    bodyEl.innerHTML = '';
    lines.forEach(([key, item]) => {
        const line = document.createElement('div');
        line.className = 'cart-line';
        line.innerHTML = `
            <div class="cart-line-info">
                <div class="cart-line-name">${item.name}</div>
                <div class="cart-line-price">${item.price.toLocaleString()} EGP</div>
                <div class="cart-line-qty">
                    <button class="cart-qbtn" onclick="changeQty('${key}', -1)">−</button>
                    <span class="cart-qnum">${item.qty}</span>
                    <button class="cart-qbtn" onclick="changeQty('${key}', 1)">+</button>
                </div>
            </div>
            <button class="cart-remove" onclick="removeLine('${key}')" title="Remove">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        `;
        bodyEl.appendChild(line);
    });
}

/* ---------- Place pre-order (pickup only) ---------- */
function placePreOrder() {
    const restaurantCart = getRestaurantCart();
    const lines = Object.entries(restaurantCart);

    if (lines.length === 0) return;

    let total = 0;
    const items = lines.map(([k, v]) => {
        total += v.price * v.qty;
        return { itemId: v.itemId, name: v.name, qty: v.qty, price: v.price, lineTotal: v.price * v.qty };
    });

    const order = {
        id: 'pre_' + Date.now(),
        restaurant: window.SAVOURE_RESTAURANT,
        customer: currentCustomer(),
        type: 'pickup',
        items: items,
        total: total,
        status: 'pending',
        createdAt: new Date().toISOString(),
        pickupTime: document.getElementById('pickup-time').value
    };

    // Save to shared preorders list (staff side reads this, filtered by restaurant)
    const orders = JSON.parse(localStorage.getItem(ORDERS_KEY) || '[]');
    orders.push(order);
    localStorage.setItem(ORDERS_KEY, JSON.stringify(orders));

    // Clear this restaurant's cart
    clearRestaurantCart();

    showConfirmation(order, total);
}

function showConfirmation(order, total) {
    const body = document.getElementById('cart-body');
    const foot = document.getElementById('cart-foot');

    body.innerHTML = `
        <div style="text-align:center;padding:28px 12px;">
            <div style="font-size:2.6rem;color:#2D7A5F;margin-bottom:12px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div style="font-family:var(--serif);font-size:1.3rem;font-weight:700;color:var(--navy);margin-bottom:6px;">Pre-Order Saved</div>
            <div style="font-size:.78rem;color:var(--charcoal);opacity:.7;margin-bottom:4px;">
                Order <strong>${order.id}</strong>
            </div>
            <div style="font-size:.78rem;color:var(--charcoal);opacity:.7;margin-bottom:16px;">
                Pickup at ${order.pickupTime}
            </div>
            <div style="font-family:var(--serif);font-size:1.1rem;font-weight:700;color:var(--gold-dk);">
                ${total.toLocaleString()} EGP
            </div>
        </div>`;
    foot.style.display = 'none';

    // Restore cart UI after a few seconds so they can order more
    setTimeout(() => {
        foot.style.display = 'block';
        renderCart();
    }, 5000);
}

/* ---------- Mobile cart drawer ---------- */
function openMobileCart() {
    document.getElementById('cart-panel').classList.add('mobile-open');
}
function closeMobileCart() {
    document.getElementById('cart-panel').classList.remove('mobile-open');
}

/* ---------- Toast ---------- */
function showMenuToast(msg, type = 'success') {
    let zone = document.querySelector('.toast-zone');
    if (!zone) {
        zone = document.createElement('div');
        zone.className = 'toast-zone';
        document.body.appendChild(zone);
    }
    const t = document.createElement('div');
    t.className = 'toast ' + type;
    t.textContent = msg;
    zone.appendChild(t);
    setTimeout(() => { t.classList.add('fade-out'); setTimeout(() => t.remove(), 300); }, 2600);
}

/* ---------- Init ---------- */
document.addEventListener('DOMContentLoaded', () => {
    renderMenu();
    renderCart();
});