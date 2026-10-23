/**
 * Savouré Customer Dashboard & Restaurant Portal Controller
 */

// Comprehensive Dish Pool derived from MENU_DATA
const ALL_MENU_DISHES = [
    // The Revolving Restaurant
    {
        restaurant: "The Revolving Restaurant",
        dish: "Dry-Aged Rib Eye Steak",
        description: "300g prime rib eye, truffle mashed potatoes, peppercorn jus.",
        price: "1,650 EGP"
    },
    {
        restaurant: "The Revolving Restaurant",
        dish: "Pan-Seared Sea Bass",
        description: "Sea bass fillet, lemon butter, sautéed spinach, crispy skin.",
        price: "1,180 EGP"
    },
    {
        restaurant: "The Revolving Restaurant",
        dish: "Crab Bisque",
        description: "Rich Egyptian crab bisque, cognac cream, crispy croutons.",
        price: "620 EGP"
    },
    {
        restaurant: "The Revolving Restaurant",
        dish: "Grilled Shrimp & Coconut Sauce",
        description: "Tiger prawns, coconut cream sauce, citrus zest.",
        price: "780 EGP"
    },
    {
        restaurant: "The Revolving Restaurant",
        dish: "Duck Breast",
        description: "Roasted duck breast, cherry reduction, seasonal vegetables.",
        price: "1,380 EGP"
    },
    // Le Petite Chef
    {
        restaurant: "Le Petite Chef",
        dish: "Classic Menu Experience",
        description: "Buffalo Tomato Salad · Truffle Mushroom Ravioli · Beef Fillet & Vegetables · Japanese Mont Blanc",
        price: "2,500 EGP"
    },
    {
        restaurant: "Le Petite Chef",
        dish: "Premium Menu Experience",
        description: "Buffalo Tomato Salad · Truffle Mushroom Ravioli · Beef Fillet & Grilled Shrimp · Japanese Mont Blanc",
        price: "3,500 EGP"
    },
    {
        restaurant: "Le Petite Chef",
        dish: "Vegetarian Menu Experience",
        description: "Buffalo Tomato Salad · Truffle Mushroom Ravioli · Vegetable & Nut Wellington · Japanese Mont Blanc",
        price: "2,500 EGP"
    },
    // Mo Bistro
    {
        restaurant: "Mo Bistro",
        dish: "Lamb Shank",
        description: "Slow-braised lamb shank, spiced brown rice, rich pan jus. (Guest favourite)",
        price: "890 EGP"
    },
    {
        restaurant: "Mo Bistro",
        dish: "Mushroom Risotto",
        description: "Creamy wild mushroom risotto, aged parmesan, truffle oil.",
        price: "520 EGP"
    },
    {
        restaurant: "Mo Bistro",
        dish: "Steak Flank",
        description: "Grilled flank steak, chimichurri, roasted potatoes.",
        price: "780 EGP"
    },
    {
        restaurant: "Mo Bistro",
        dish: "Shrimp Cocktail",
        description: "Chilled tiger prawns, Marie Rose sauce, lemon.",
        price: "420 EGP"
    },
    {
        restaurant: "Mo Bistro",
        dish: "Chinese Beef Noodles",
        description: "Wok-tossed beef noodles, soy, ginger, spring onion.",
        price: "580 EGP"
    }
];

// Restaurant Directory Data
const RESTAURANTS = [
    {
        name: "The Revolving Restaurant",
        cuisine: "International Fine Dining",
        category: "International",
        description: "Experience 360-degree panoramic skyline views paired with an exquisite fusion menu crafted by world-class chefs.",
        image: "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80",
        hours: "12:00 PM - 12:00 AM",
        rating: "4.9"
    },
    {
        name: "Le Petite Chef",
        cuisine: "French Contemporary",
        category: "French",
        description: "Immerse yourself in an intimate 3D theatrical dining spectacle bringing culinary storytelling directly to your plate.",
        image: "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80",
        hours: "05:00 PM - 11:00 PM",
        rating: "4.8"
    },
    {
        name: "Mo Bistro",
        cuisine: "Egyptian & Mediterranean",
        category: "Egyptian",
        description: "A cozy chic bistro offering wood-fired delicacies, artisanal pastas, and handcrafted cocktails in a relaxed atmosphere.",
        image: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80",
        hours: "10:00 AM - 11:30 PM",
        rating: "4.7"
    }
];

// Current customer ID — injected by each page via window.SAVOURE_CUSTOMER.
function currentCustomerParam() {
    return encodeURIComponent(window.SAVOURE_CUSTOMER || 'sarah');
}

// Live Clock Handler
function tick() {
    const clk = document.getElementById('clk');
    const clkDate = document.getElementById('clk-date');
    
    if (clk && clkDate) {
        const d = new Date(), p = n => String(n).padStart(2, '0');
        clk.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
        clkDate.textContent = d.toLocaleDateString('en-EG', { weekday: 'short', month: 'short', day: 'numeric' });
    }
}

/**
 * Computes 3 dishes based on the current 2-hour interval.
 */
function getRotatedRecommendations() {
    const TWO_HOURS_MS = 1000 * 60 * 60 * 2; // 7,200,000 ms
    const timeBlockIndex = Math.floor(Date.now() / TWO_HOURS_MS);

    const numberOfRecs = 3;
    const selectedDishes = [];

    for (let i = 0; i < numberOfRecs; i++) {
        const index = (timeBlockIndex + i) % ALL_MENU_DISHES.length;
        selectedDishes.push(ALL_MENU_DISHES[index]);
    }

    return selectedDishes;
}

// Render Recommendation Cards (Dashboard View)
function renderRecommendations() {
    const grid = document.getElementById('recs-grid');
    if (!grid) return;

    const currentRecs = getRotatedRecommendations();

    grid.innerHTML = '';
    currentRecs.forEach(rec => {
        const card = document.createElement('div');
        card.className = 'rec-card';
        const menuUrl = `menu.php?restaurant=${encodeURIComponent(rec.restaurant)}&customer=${currentCustomerParam()}`;
        card.innerHTML = `
            <div>
                <span class="rec-tag">${rec.restaurant}</span>
                <h3 class="rec-title">${rec.dish}</h3>
                <p class="rec-desc">${rec.description}</p>
            </div>
            <div class="rec-footer">
                <span class="rec-price">${rec.price}</span>
                <button class="rec-btn" onclick="window.location.href='${menuUrl}'">Order Now</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Render Restaurant Cards with Category Filter Support
function renderRestaurantCards() {
    const grid = document.getElementById('restaurants-grid');
    if (!grid) return;

    const filterCategory = grid.getAttribute('data-category');
    
    const listToRender = filterCategory 
        ? RESTAURANTS.filter(r => r.category.toLowerCase() === filterCategory.toLowerCase())
        : RESTAURANTS;

    grid.innerHTML = '';

    if (listToRender.length === 0) {
        grid.innerHTML = `<p style="grid-column: 1/-1; color: #888; font-size: 0.9rem;">No restaurants available in this category.</p>`;
        return;
    }

    listToRender.forEach(rest => {
        const card = document.createElement('div');
        card.className = 'rest-card';
        const custParam = currentCustomerParam();
        card.innerHTML = `
            <div class="rest-img-wrapper">
                <img src="${rest.image}" alt="${rest.name}" class="rest-img">
                <span class="rest-cuisine-badge">${rest.cuisine}</span>
            </div>
            <div class="rest-body">
                <div>
                    <h3 class="rest-title">${rest.name}</h3>
                    <p class="rest-desc">${rest.description}</p>
                </div>
                <div>
                    <div class="rest-info-row">
                        <div class="rest-info-item">
                            <i class="fa-solid fa-clock"></i>
                            <span>${rest.hours}</span>
                        </div>
                        <div class="rest-info-item">
                            <i class="fa-solid fa-star"></i>
                            <span>${rest.rating}</span>
                        </div>
                    </div>
                    <div class="rest-footer">
                        <a href="menu.php?restaurant=${encodeURIComponent(rest.name)}&customer=${custParam}" class="btn-rest btn-rest-outline">View Menu</a>
                       <a href="Reservation.php?restaurant=${encodeURIComponent(rest.name)}&customer=${custParam}" class="btn-rest btn-rest-primary">Reserve Table</a>
                    </div>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Unified Initialization Event
document.addEventListener('DOMContentLoaded', () => {
    tick();
    setInterval(tick, 1000);
    
    renderRecommendations();
    renderRestaurantCards();

    // Check every 60 seconds to re-render if the 2-hour window shifted while open
    setInterval(renderRecommendations, 60000);
});