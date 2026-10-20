
const INITIAL_RESTAURANTS = [
    {
        id: "rest_01",
        name: "The Revolving Restaurant",
        cuisine: "International Fine Dining",
        category: "International",
        address: "Cairo Tower, Zamalek, Cairo",
        hours: "12:00 PM - 12:00 AM",
        rating: "4.9",
        description: "Experience 360-degree panoramic skyline views paired with an exquisite fusion menu crafted by world-class chefs.",
        image: "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "rest_02",
        name: "Le Petite Chef",
        cuisine: "French Contemporary",
        category: "French",
        address: "Four Seasons Hotel, Nile Plaza, Garden City, Cairo",
        hours: "05:00 PM - 11:00 PM",
        rating: "4.8",
        description: "Immerse yourself in an intimate 3D theatrical dining spectacle bringing culinary storytelling directly to your plate.",
        image: "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "rest_03",
        name: "Mo Bistro",
        cuisine: "Egyptian & Mediterranean",
        category: "Egyptian",
        address: "22 El Gezira St, Zamalek, Cairo",
        hours: "10:00 AM - 11:30 PM",
        rating: "4.7",
        description: "A cozy chic bistro offering wood-fired delicacies, artisanal pastas, and handcrafted cocktails in a relaxed atmosphere.",
        image: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80"
    }
];

const DEFAULT_FALLBACK_IMAGE = "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80";

const RESTS_STORAGE_KEY = 'savoure_restaurants';
const USERS_STORAGE_KEY = 'savoure_users';

let currentCuisineFilter = 'all';
let currentSearchQuery = '';

function getStoredRestaurants() {
    const raw = localStorage.getItem(RESTS_STORAGE_KEY);
    if (!raw) {
        localStorage.setItem(RESTS_STORAGE_KEY, JSON.stringify(INITIAL_RESTAURANTS));
        return INITIAL_RESTAURANTS;
    }
    try {
        return JSON.parse(raw);
    } catch (e) {
        return INITIAL_RESTAURANTS;
    }
}

function saveRestaurants(list) {
    localStorage.setItem(RESTS_STORAGE_KEY, JSON.stringify(list));
}

function getStoredUsers() {
    const raw = localStorage.getItem(USERS_STORAGE_KEY);
    if (raw) {
        try {
            return JSON.parse(raw);
        } catch (e) {}
    }
    return [
        { firstName: "Nour", lastName: "Khalil", role: "staff", assignedRestaurant: "The Revolving Restaurant" },
        { firstName: "Tarek", lastName: "Mansour", role: "staff", assignedRestaurant: "Le Petite Chef" },
        { firstName: "Sarah", lastName: "Jenkins", role: "staff", assignedRestaurant: "The Revolving Restaurant" },
        { firstName: "Pierre", lastName: "Dubois", role: "staff", assignedRestaurant: "Le Petite Chef" },
        { firstName: "Michael", lastName: "Ross", role: "staff", assignedRestaurant: "Mo Bistro" }
    ];
}

function renderRestaurants() {
    const restaurants = getStoredRestaurants();
    const users = getStoredUsers();
    const grid = document.getElementById('restaurants-grid');
    const emptyState = document.getElementById('restaurants-empty-state');

    if (!grid) return;

    const filtered = restaurants.filter(r => {
        const matchesCategory = currentCuisineFilter === 'all' || 
            (r.category && r.category.toLowerCase() === currentCuisineFilter.toLowerCase()) ||
            (r.cuisine && r.cuisine.toLowerCase().includes(currentCuisineFilter.toLowerCase()));

        const q = currentSearchQuery.toLowerCase();
        const matchesSearch = r.name.toLowerCase().includes(q) ||
            r.cuisine.toLowerCase().includes(q) ||
            (r.address && r.address.toLowerCase().includes(q));

        return matchesCategory && matchesSearch;
    });

    updateRestaurantStats(restaurants, users);

    if (filtered.length === 0) {
        grid.innerHTML = '';
        if (emptyState) emptyState.style.display = 'block';
        return;
    }

    if (emptyState) emptyState.style.display = 'none';
    grid.innerHTML = '';

    filtered.forEach(r => {
        const assignedStaff = users.filter(u => u.role === 'staff' && u.assignedRestaurant === r.name);
        const staffCountText = assignedStaff.length === 1 ? '1 Staff Member' : `${assignedStaff.length} Staff Members`;
        const staffNamesText = assignedStaff.length > 0 
            ? assignedStaff.map(s => `${s.firstName} ${s.lastName}`).join(', ')
            : 'No staff assigned yet';

        const card = document.createElement('div');
        card.className = 'admin-rest-card';
        card.innerHTML = `
            <div class="admin-rest-img-wrapper">
                <img src="${escapeHtml(r.image || DEFAULT_FALLBACK_IMAGE)}" alt="${escapeHtml(r.name)}" class="admin-rest-img" onerror="this.src='${DEFAULT_FALLBACK_IMAGE}'">
                <span class="admin-rest-cuisine-badge">${escapeHtml(r.cuisine)}</span>
            </div>
            <div class="admin-rest-body">
                <h3 class="admin-rest-name">${escapeHtml(r.name)}</h3>
                <p class="admin-rest-desc">${escapeHtml(r.description || 'Exclusive dining destination offering curated culinary experiences.')}</p>

                <div class="admin-rest-details">
                    <div class="admin-detail-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>${escapeHtml(r.address || 'Cairo, Egypt')}</span>
                    </div>
                    <div class="admin-detail-item">
                        <i class="fa-solid fa-clock"></i>
                        <span>${escapeHtml(r.hours || '12:00 PM - 12:00 AM')}</span>
                    </div>
                </div>

                <div class="admin-staff-preview">
                    <div class="admin-staff-count">
                        <i class="fa-solid fa-user-tie"></i>
                        <span>${staffCountText}</span>
                    </div>
                    <span class="admin-staff-names" title="${escapeHtml(staffNamesText)}">${escapeHtml(staffNamesText)}</span>
                </div>

                <div class="admin-rest-footer">
                    <a href="user_management.php" class="btn btn-ghost btn-sm" title="Assign Staff in User Management">
                        <i class="fa-solid fa-users"></i> Staff
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="handleDeleteRestaurant('${escapeHtml(r.id)}', '${escapeHtml(r.name)}')">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

function updateRestaurantStats(restaurants, users) {
    const totalEl = document.getElementById('stat-total-restaurants');
    const cuisinesEl = document.getElementById('stat-total-cuisines');
    const staffEl = document.getElementById('stat-assigned-staff');

    if (totalEl) totalEl.textContent = restaurants.length;

    if (cuisinesEl) {
        const uniqueCuisines = new Set(restaurants.map(r => r.category || r.cuisine));
        cuisinesEl.textContent = uniqueCuisines.size;
    }

    if (staffEl) {
        const assignedStaffCount = users.filter(u => u.role === 'staff' && u.assignedRestaurant).length;
        staffEl.textContent = assignedStaffCount;
    }
}

function handleAddRestaurantSubmit(event) {
    event.preventDefault();

    const name = document.getElementById('rest-name').value.trim();
    const cuisine = document.getElementById('rest-cuisine').value.trim();
    const category = document.getElementById('rest-category').value;
    const address = document.getElementById('rest-address').value.trim();
    const hours = document.getElementById('rest-hours').value.trim();
    const description = document.getElementById('rest-desc').value.trim();
    const image = document.getElementById('rest-image').value.trim() || DEFAULT_FALLBACK_IMAGE;

    if (!name || !cuisine || !address) {
        showToast('Please provide the restaurant name, cuisine, and address.', 'error');
        return;
    }

    const restaurants = getStoredRestaurants();

    const exists = restaurants.find(r => r.name.toLowerCase() === name.toLowerCase());
    if (exists) {
        showToast('A restaurant with this name already exists.', 'error');
        return;
    }

    const newRestaurant = {
        id: `rest_${Date.now().toString().slice(-4)}`,
        name: name,
        cuisine: cuisine,
        category: category || "International",
        address: address,
        hours: hours || "12:00 PM - 12:00 AM",
        rating: "5.0",
        description: description || "Freshly opened culinary dining destination at Savouré.",
        image: image
    };

    restaurants.push(newRestaurant);
    saveRestaurants(restaurants);

    document.getElementById('add-restaurant-form').reset();
    closeAddRestaurantModal();

    renderRestaurants();
    showToast(`Restaurant "${name}" created successfully!`, 'success');
}

function handleDeleteRestaurant(restId, restName) {
    if (!confirm(`Are you sure you want to delete "${restName}"? Any assigned staff will become unassigned.`)) {
        return;
    }

    const restaurants = getStoredRestaurants();
    const updated = restaurants.filter(r => r.id !== restId);
    saveRestaurants(updated);

    const usersRaw = localStorage.getItem(USERS_STORAGE_KEY);
    if (usersRaw) {
        try {
            const users = JSON.parse(usersRaw);
            let modified = false;
            users.forEach(u => {
                if (u.role === 'staff' && u.assignedRestaurant === restName) {
                    u.assignedRestaurant = '';
                    modified = true;
                }
            });
            if (modified) {
                localStorage.setItem(USERS_STORAGE_KEY, JSON.stringify(users));
            }
        } catch (e) {}
    }

    renderRestaurants();
    showToast(`"${restName}" has been removed from the directory.`, 'success');
}

function openAddRestaurantModal() {
    const modal = document.getElementById('add-restaurant-modal');
    if (modal) modal.classList.add('open');
}

function closeAddRestaurantModal() {
    const modal = document.getElementById('add-restaurant-modal');
    if (modal) modal.classList.remove('open');
}

function filterRestaurantsByCuisine(category, btnElement) {
    currentCuisineFilter = category;
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    if (btnElement) btnElement.classList.add('active');
    renderRestaurants();
}

function handleRestaurantSearch(query) {
    currentSearchQuery = query;
    renderRestaurants();
}

function tickClock() {
    const clk = document.getElementById('clk');
    const clkDate = document.getElementById('clk-date');
    if (clk && clkDate) {
        const d = new Date(), p = n => String(n).padStart(2, '0');
        clk.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
        clkDate.textContent = d.toLocaleDateString('en-EG', { weekday: 'short', month: 'short', day: 'numeric' });
    }
}

function showToast(message, type = 'success') {
    let zone = document.querySelector('.toast-zone');
    if (!zone) {
        zone = document.createElement('div');
        zone.className = 'toast-zone';
        document.body.appendChild(zone);
    }
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    zone.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3200);
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

document.addEventListener('DOMContentLoaded', () => {
    setInterval(tickClock, 1000);
    tickClock();

    renderRestaurants();

    const form = document.getElementById('add-restaurant-form');
    if (form) {
        form.addEventListener('submit', handleAddRestaurantSubmit);
    }

    const modal = document.getElementById('add-restaurant-modal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeAddRestaurantModal();
        });
    }
});
