/**
 * Savouré — Restaurant Menu & Pre-Order (Phase 1: localStorage)
 * Menu data is seeded here. Swap with API later without touching menu.php.
 */

const MENU_DATA = {
    "The Revolving Restaurant": {
        cuisine: "International Fine Dining",
        sections: [
            {
                name: "Starters",
                icon: "fa-bowl-food",
                items: [
                    { id: "rev-s1", name: "Mushroom Cream Soup", desc: "Velvety wild mushroom soup with truffle oil and chives.", price: 320 },
                    { id: "rev-s2", name: "Crab Bisque", desc: "Rich Egyptian crab bisque, cognac cream, crispy croutons.", price: 380 },
                    { id: "rev-s3", name: "Caesar Salad", desc: "Romaine, aged parmesan, garlic croutons, house Caesar dressing.", price: 290 }
                ]
            },
            {
                name: "Main Courses",
                icon: "fa-drumstick-bite",
                items: [
                    { id: "rev-m1", name: "Grilled Rib Eye Steak", desc: "300g prime rib eye, roasted potatoes, seasonal vegetables, peppercorn sauce.", price: 1450 },
                    { id: "rev-m2", name: "Chicken Risotto", desc: "Creamy arborio risotto with grilled chicken, parmesan, and fresh herbs.", price: 780 },
                    { id: "rev-m3", name: "Lamb Shank", desc: "Slow-cooked lamb shank, thyme jus, mashed potatoes, glazed carrots.", price: 1250 },
                    { id: "rev-m4", name: "Grilled Sea Bass", desc: "Pan-seared sea bass fillet, lemon butter, sautéed spinach.", price: 980 }
                ]
            },
            {
                name: "Desserts",
                icon: "fa-cake-candles",
                items: [
                    { id: "rev-d1", name: "Chocolate Fondant", desc: "Warm dark chocolate cake, molten center, vanilla ice cream.", price: 350 },
                    { id: "rev-d2", name: "Apple Tart", desc: "Thin apple tart, caramel drizzle, cinnamon cream.", price: 320 }
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

const CART_KEY = 'savoure_cart';
const ORDER_TYPE_KEY = 'savoure_order_type';

function getCart() {
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || {}; } catch(e) { return {}; }
}
function saveCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); }

function renderMenu() {
    const restaurant = window.SAVOURE_RESTAURANT;
    const data = MENU_DATA[restaurant];
    const container = document.getElementById('menu-container');

    if (!data) {
        container.innerHTML = `<p style="color:var(--beige);font-style:italic;">Menu coming soon for this restaurant.</p>`;
        return;
    }

    document.getElementById('menu-hero-title').textContent = restaurant;
    document.getElementById('menu-hero-cuisine').textContent = data.cuisine + ' · Cairo';

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
                    <button class="menu-item-add" title="Add to pre-order" onclick="addToCart('${item.id}', '${restaurant}', '${item.name.replace(/'/g, "\\'")}', ${item.price})">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>`;
        });
        html += `</div>`;
    });

    // Pre-order CTA
    html += `
        <div class="menu-cta">
            <div class="menu-cta-text">
                <strong>Pre-order for pickup or dine-in</strong>
                <span>Add dishes above, then choose how you'd like to enjoy your meal.</span>
            </div>
            <a href="checkout.php?restaurant=${encodeURIComponent(restaurant)}&customer=${window.SAVOURE_CUSTOMER}" class="btn btn-gold">
                <i class="fa-solid fa-bag-shopping"></i> Review Pre-Order
            </a>
        </div>`;

    container.innerHTML = html;
}

function addToCart(itemId, restaurant, itemName, price) {
    const cart = getCart();
    const key = `${restaurant}::${itemId}`;
    if (!cart[key]) {
        cart[key] = { restaurant, itemId, name: itemName, price, qty: 0 };
    }
    cart[key].qty += 1;
    saveCart(cart);
    showMenuToast(`Added: ${itemName}`);
}

function showMenuToast(msg) {
    let zone = document.querySelector('.toast-zone');
    if (!zone) {
        zone = document.createElement('div');
        zone.className = 'toast-zone';
        document.body.appendChild(zone);
    }
    const t = document.createElement('div');
    t.className = 'toast success';
    t.textContent = msg;
    zone.appendChild(t);
    setTimeout(() => { t.classList.add('fade-out'); setTimeout(() => t.remove(), 300); }, 2600);
}

document.addEventListener('DOMContentLoaded', renderMenu);