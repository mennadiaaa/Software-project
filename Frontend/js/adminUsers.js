
const INITIAL_USERS = [
    {
        id: "staff_nour",
        firstName: "Nour",
        lastName: "Khalil",
        email: "nour.khalil@revolvingrestaurant.com",
        role: "staff",
        assignedRestaurant: "The Revolving Restaurant"
    },
    {
        id: "staff_tarek",
        firstName: "Tarek",
        lastName: "Mansour",
        email: "tarek.mansour@lepetitechef.com",
        role: "staff",
        assignedRestaurant: "Le Petite Chef"
    },
    {
        id: "staff_01",
        firstName: "Sarah",
        lastName: "Jenkins",
        email: "sarah.jenkins@revolvingrestaurant.com",
        role: "staff",
        assignedRestaurant: "The Revolving Restaurant"
    },
    {
        id: "staff_02",
        firstName: "Pierre",
        lastName: "Dubois",
        email: "pierre.dubois@lepetitechef.com",
        role: "staff",
        assignedRestaurant: "Le Petite Chef"
    },
    {
        id: "staff_03",
        firstName: "Michael",
        lastName: "Ross",
        email: "michael.ross@mobistro.com",
        role: "staff",
        assignedRestaurant: "Mo Bistro"
    },
    {
        id: "cust_sarah",
        firstName: "Sarah",
        lastName: "Hassan",
        email: "sarah.hassan@example.com",
        role: "customer",
        assignedRestaurant: ""
    },
    {
        id: "cust_youssef",
        firstName: "Youssef",
        lastName: "Ali",
        email: "youssef.ali@example.com",
        role: "customer",
        assignedRestaurant: ""
    },
    {
        id: "cust_01",
        firstName: "Alice",
        lastName: "Johnson",
        email: "alice.johnson@example.com",
        role: "customer",
        assignedRestaurant: ""
    },
    {
        id: "cust_02",
        firstName: "David",
        lastName: "Smith",
        email: "david.smith@example.com",
        role: "customer",
        assignedRestaurant: ""
    },
    {
        id: "admin_system",
        firstName: "System",
        lastName: "Admin",
        email: "admin@savoure.com",
        role: "admin",
        assignedRestaurant: ""
    },
    {
        id: "admin_01",
        firstName: "Alexander",
        lastName: "Vance",
        email: "alex.vance@admin.com",
        role: "admin",
        assignedRestaurant: ""
    }
];

const DEFAULT_RESTAURANT_NAMES = [
    "The Revolving Restaurant",
    "Le Petite Chef",
    "Mo Bistro"
];

const USERS_STORAGE_KEY = 'savoure_users';
const RESTS_STORAGE_KEY = 'savoure_restaurants';

let currentRoleFilter = 'all';
let currentSearchQuery = '';

function getStoredUsers() {
    const raw = localStorage.getItem(USERS_STORAGE_KEY);
    if (!raw) {
        localStorage.setItem(USERS_STORAGE_KEY, JSON.stringify(INITIAL_USERS));
        return INITIAL_USERS;
    }
    try {
        const parsed = JSON.parse(raw);
        const hasNour = parsed.some(u => u.firstName.toLowerCase() === 'nour');
        const hasTarek = parsed.some(u => u.firstName.toLowerCase() === 'tarek');
        if (!hasNour || !hasTarek) {
            localStorage.setItem(USERS_STORAGE_KEY, JSON.stringify(INITIAL_USERS));
            return INITIAL_USERS;
        }
        return parsed;
    } catch (e) {
        return INITIAL_USERS;
    }
}

function saveUsers(users) {
    localStorage.setItem(USERS_STORAGE_KEY, JSON.stringify(users));
}

function getAvailableRestaurantNames() {
    const raw = localStorage.getItem(RESTS_STORAGE_KEY);
    if (raw) {
        try {
            const list = JSON.parse(raw);
            if (Array.isArray(list) && list.length > 0) {
                return list.map(r => r.name);
            }
        } catch (e) {}
    }
    return DEFAULT_RESTAURANT_NAMES;
}

function populateRestaurantDropdowns() {
    const select = document.getElementById('user-restaurant-select');
    if (!select) return;

    const restaurants = getAvailableRestaurantNames();
    select.innerHTML = '<option value="">-- Choose Restaurant --</option>';
    restaurants.forEach(name => {
        const opt = document.createElement('option');
        opt.value = name;
        opt.textContent = name;
        select.appendChild(opt);
    });
}

function renderUsersTable() {
    const users = getStoredUsers();
    const availableRestaurants = getAvailableRestaurantNames();
    const tbody = document.getElementById('users-tbody');
    const emptyState = document.getElementById('users-empty-state');
    const tableWrap = document.getElementById('users-table-wrap');

    if (!tbody) return;

    const filtered = users.filter(user => {
        const matchesRole = currentRoleFilter === 'all' || user.role === currentRoleFilter;
        const fullName = `${user.firstName} ${user.lastName}`.toLowerCase();
        const email = user.email.toLowerCase();
        const rest = (user.assignedRestaurant || '').toLowerCase();
        const q = currentSearchQuery.toLowerCase();
        const matchesSearch = fullName.includes(q) || email.includes(q) || rest.includes(q);
        return matchesRole && matchesSearch;
    });

    updateSummaryStats(users);

    if (filtered.length === 0) {
        tbody.innerHTML = '';
        if (tableWrap) tableWrap.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
        return;
    }

    if (tableWrap) tableWrap.style.display = 'block';
    if (emptyState) emptyState.style.display = 'none';

    tbody.innerHTML = '';

    filtered.forEach(user => {
        const tr = document.createElement('tr');

        const initial = (user.firstName ? user.firstName.charAt(0) : 'U').toUpperCase();

        let restaurantCellHtml = '';
        if (user.role === 'staff') {
            let optionsHtml = `<option value="">-- Unassigned --</option>`;
            availableRestaurants.forEach(rName => {
                const isSelected = user.assignedRestaurant === rName ? 'selected' : '';
                optionsHtml += `<option value="${escapeHtml(rName)}" ${isSelected}>${escapeHtml(rName)}</option>`;
            });
            restaurantCellHtml = `
                <select class="table-select" onchange="handleAssignRestaurant('${escapeHtml(user.id)}', this.value)">
                    ${optionsHtml}
                </select>
            `;
        } else {
            restaurantCellHtml = `<span class="restaurant-na-tag">Not Applicable</span>`;
        }

        tr.innerHTML = `
            <td>
                <div class="user-identity">
                    <div class="user-avatar-circle ${user.role}">${initial}</div>
                    <div>
                        <div class="user-full-name">${escapeHtml(user.firstName)} ${escapeHtml(user.lastName)}</div>
                        <div class="user-id-sub">${escapeHtml(user.id)}</div>
                    </div>
                </div>
            </td>
            <td>${escapeHtml(user.email)}</td>
            <td><span class="badge badge-${user.role}">${capitalize(user.role)}</span></td>
            <td>${restaurantCellHtml}</td>
            <td>
                <div class="action-cell">
                    <button type="button" class="btn-icon-danger" title="Delete User" onclick="handleDeleteUser('${escapeHtml(user.id)}', '${escapeHtml(user.firstName)} ${escapeHtml(user.lastName)}')">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function updateSummaryStats(users) {
    const totalEl = document.getElementById('stat-total-users');
    const staffEl = document.getElementById('stat-staff-count');
    const custEl = document.getElementById('stat-customer-count');
    const adminEl = document.getElementById('stat-admin-count');

    const total = users.length;
    const staff = users.filter(u => u.role === 'staff').length;
    const cust = users.filter(u => u.role === 'customer').length;
    const admin = users.filter(u => u.role === 'admin').length;

    if (totalEl) totalEl.textContent = total;
    if (staffEl) staffEl.textContent = staff;
    if (custEl) custEl.textContent = cust;
    if (adminEl) adminEl.textContent = admin;
}

function handleAssignRestaurant(userId, newRestaurant) {
    const users = getStoredUsers();
    const target = users.find(u => u.id === userId);
    if (!target) return;

    target.assignedRestaurant = newRestaurant;
    saveUsers(users);

    const restDisplay = newRestaurant ? `to ${newRestaurant}` : 'as unassigned';
    showToast(`${target.firstName} ${target.lastName} assigned ${restDisplay}`, 'success');
}

function handleDeleteUser(userId, userName) {
    if (!confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
        return;
    }

    const users = getStoredUsers();
    const updatedUsers = users.filter(u => u.id !== userId);
    saveUsers(updatedUsers);

    renderUsersTable();
    showToast(`User "${userName}" has been successfully deleted.`, 'success');
}

function handleAddUserSubmit(event) {
    event.preventDefault();

    const firstName = document.getElementById('user-first-name').value.trim();
    const lastName = document.getElementById('user-last-name').value.trim();
    const email = document.getElementById('user-email').value.trim();
    const role = document.getElementById('user-role').value;
    const restaurantSelect = document.getElementById('user-restaurant-select');
    const assignedRestaurant = (role === 'staff' && restaurantSelect) ? restaurantSelect.value : '';

    if (!firstName || !lastName || !email) {
        showToast('Please fill in all required fields.', 'error');
        return;
    }

    const users = getStoredUsers();

    const duplicate = users.find(u => u.email.toLowerCase() === email.toLowerCase());
    if (duplicate) {
        showToast('A user with this email address already exists.', 'error');
        return;
    }

    const prefix = role === 'staff' ? 'staff' : (role === 'admin' ? 'admin' : 'cust');
    const newId = `${prefix}_${Date.now().toString().slice(-4)}`;

    const newUser = {
        id: newId,
        firstName: firstName,
        lastName: lastName,
        email: email,
        role: role,
        assignedRestaurant: assignedRestaurant
    };

    users.push(newUser);
    saveUsers(users);

    document.getElementById('add-user-form').reset();
    closeAddUserModal();

    renderUsersTable();
    showToast(`User ${firstName} ${lastName} added successfully!`, 'success');
}

function handleRoleChange(selectedRole) {
    const restaurantRow = document.getElementById('form-group-restaurant');
    if (!restaurantRow) return;

    if (selectedRole === 'staff') {
        restaurantRow.style.display = 'block';
    } else {
        restaurantRow.style.display = 'none';
        const restSelect = document.getElementById('user-restaurant-select');
        if (restSelect) restSelect.value = '';
    }
}

function openAddUserModal() {
    populateRestaurantDropdowns();
    const modal = document.getElementById('add-user-modal');
    if (modal) modal.classList.add('open');
    handleRoleChange(document.getElementById('user-role').value);
}

function closeAddUserModal() {
    const modal = document.getElementById('add-user-modal');
    if (modal) modal.classList.remove('open');
}

function filterUsersByRole(role, btnElement) {
    currentRoleFilter = role;
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    if (btnElement) btnElement.classList.add('active');
    renderUsersTable();
}

function handleUserSearch(query) {
    currentSearchQuery = query;
    renderUsersTable();
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

    populateRestaurantDropdowns();
    renderUsersTable();

    const form = document.getElementById('add-user-form');
    if (form) {
        form.addEventListener('submit', handleAddUserSubmit);
    }

    const modal = document.getElementById('add-user-modal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeAddUserModal();
        });
    }
});
