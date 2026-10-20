/**
 * Savouré Staff Dashboard - Local Storage Manager & UI Controller
 */

// Initial Default Data
const DEFAULT_RESERVATIONS = {
    'The Revolving Restaurant': [
        ['18:30', 'Hassan, Ahmed', 2, 'T-02', 'confirmed'],
        ['19:00', 'El-Sayed, Fatima', 2, 'T-06', 'confirmed'],
        ['19:30', 'Mansour, Omar', 4, 'T-10', 'confirmed'],
        ['20:00', 'Karim, Leila', 3, 'T-04', 'pending'],
        ['20:30', 'Ibrahim, Khaled', 6, 'T-07', 'confirmed'],
        ['21:00', 'Naguib, Sara', 2, 'T-01', 'confirmed']
    ],
    'Le Petite Chef': [
        ['18:00', 'El-Masry, Mostafa', 2, 'T-01', 'confirmed'],
        ['18:45', 'Ghanem, Nourhan', 4, 'T-03', 'confirmed'],
        ['19:15', 'Farouk, Sherif', 2, 'T-05', 'pending'],
        ['20:00', 'Khattab, Salma', 3, 'T-08', 'confirmed'],
        ['20:30', 'El-Kady, Tamer', 5, 'T-09', 'confirmed'],
        ['21:15', 'Radwan, Dina', 2, 'T-02', 'confirmed']
    ]
};

const DEFAULT_TABLES = [
    { num: 1, seats: 2, status: 'free' },
    { num: 2, seats: 4, status: 'reserved' },
    { num: 3, seats: 2, status: 'occupied' },
    { num: 4, seats: 6, status: 'free' },
    { num: 5, seats: 4, status: 'occupied' },
    { num: 6, seats: 2, status: 'reserved' },
    { num: 7, seats: 8, status: 'free' },
    { num: 8, seats: 4, status: 'occupied' },
    { num: 9, seats: 2, status: 'free' },
    { num: 10, seats: 4, status: 'reserved' },
    { num: 11, seats: 6, status: 'occupied' },
    { num: 12, seats: 2, status: 'free' }
];

const DEFAULT_STATS = {
    pendingOrders: 2,
    openRequests: 2
};

// --- Storage API Helpers ---
function loadData(key, fallback) {
    const raw = localStorage.getItem(key);
    return raw ? JSON.parse(raw) : fallback;
}

function saveData(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}

// Ensure state is initialized
if (!localStorage.getItem('savoure_reservations')) saveData('savoure_reservations', DEFAULT_RESERVATIONS);
if (!localStorage.getItem('savoure_tables')) saveData('savoure_tables', DEFAULT_TABLES);
if (!localStorage.getItem('savoure_stats')) saveData('savoure_stats', DEFAULT_STATS);
if (!localStorage.getItem('savoure_active_restaurant')) saveData('savoure_active_restaurant', 'The Revolving Restaurant');

// Main Render Logic
function renderDashboard() {
    const activeRest = loadData('savoure_active_restaurant', 'The Revolving Restaurant');
    const reservationsData = loadData('savoure_reservations', DEFAULT_RESERVATIONS);
    const tablesData = loadData('savoure_tables', DEFAULT_TABLES);
    const statsData = loadData('savoure_stats', DEFAULT_STATS);

    const currentReservations = reservationsData[activeRest] || [];

    // 1. Render Reservations Table
    const tbody = document.getElementById('reservations-tbody');
    tbody.innerHTML = '';

    currentReservations.forEach(([time, guest, party, table, status], idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="td-mono">${time}</td>
            <td class="td-name">${guest}</td>
            <td><i class="fa-solid fa-user-group" style="color:var(--beige);margin-right:5px;"></i>${party}</td>
            <td><strong style="color:var(--navy);">${table}</strong></td>
            <td><span class="badge badge-${status} badge-clickable" onclick="toggleReservationStatus(${idx})">${capitalize(status)}</span></td>
            <td><a href="tables.php" class="btn btn-ghost btn-sm">Seat</a></td>
        `;
        tbody.appendChild(tr);
    });

    // 2. Render Floor Plan Tables
    const floorGrid = document.getElementById('floor-mini-grid');
    floorGrid.innerHTML = '';

    let freeCount = 0, reservedCount = 0, occupiedCount = 0;

    tablesData.forEach((tbl) => {
        if (tbl.status === 'free') freeCount++;
        else if (tbl.status === 'reserved') reservedCount++;
        else if (tbl.status === 'occupied') occupiedCount++;

        const card = document.createElement('div');
        card.className = `table-card ${tbl.status}`;
        card.title = `Table ${tbl.num} · ${capitalize(tbl.status)} (Click to toggle)`;
        card.innerHTML = `
            <div class="tc-num">${tbl.num}</div>
            <div class="tc-seats">${tbl.seats} seats</div>
        `;
        card.onclick = () => cycleTableStatus(tbl.num);
        floorGrid.appendChild(card);
    });

    // 3. Update Stat Indicators
    animateCount(document.getElementById('stat-reservations'), currentReservations.length);
    animateCount(document.getElementById('stat-orders'), statsData.pendingOrders);
    animateCount(document.getElementById('stat-requests'), statsData.openRequests);

    document.getElementById('count-free').textContent = freeCount;
    document.getElementById('count-reserved').textContent = reservedCount;
    document.getElementById('count-occupied').textContent = occupiedCount;
}

// Utility: Capitalize string
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Utility: Animated Number Counter
function animateCount(el, target) {
    if (!el) return;
    const dur = 600;
    let start = 0;
    const increment = target / (dur / 16);

    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            el.textContent = target;
            clearInterval(timer);
        } else {
            el.textContent = Math.round(start);
        }
    }, 16);
}

// Interactive State Modifications (Persisted automatically)
function toggleReservationStatus(index) {
    const activeRest = loadData('savoure_active_restaurant', 'The Revolving Restaurant');
    const reservations = loadData('savoure_reservations', DEFAULT_RESERVATIONS);

    if (reservations[activeRest] && reservations[activeRest][index]) {
        const current = reservations[activeRest][index][4];
        reservations[activeRest][index][4] = current === 'confirmed' ? 'pending' : 'confirmed';
        saveData('savoure_reservations', reservations);
        renderDashboard();
    }
}

function cycleTableStatus(tableNum) {
    const tables = loadData('savoure_tables', DEFAULT_TABLES);
    const table = tables.find(t => t.num === tableNum);

    if (table) {
        const statuses = ['free', 'reserved', 'occupied'];
        const nextIndex = (statuses.indexOf(table.status) + 1) % statuses.length;
        table.status = statuses[nextIndex];
        saveData('savoure_tables', tables);
        renderDashboard();
    }
}

// Clock update logic
function tick() {
    const d = new Date(), p = n => String(n).padStart(2, '0');
    const clkEl = document.getElementById('clk');
    const dateEl = document.getElementById('clk-date');

    if (clkEl) clkEl.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    if (dateEl) dateEl.textContent = d.toLocaleDateString('en-EG', { weekday: 'short', month: 'short', day: 'numeric' });
}

// Lifecycle Events
document.addEventListener('DOMContentLoaded', () => {
    renderDashboard();
    tick();
    setInterval(tick, 1000);

    // Toast Notification
    setTimeout(() => {
        const z = document.getElementById('toasts');
        if (z) {
            z.innerHTML = '<div class="toast">Good evening — <strong>2 new orders</strong> are waiting.</div>';
            setTimeout(() => z.querySelector('.toast')?.remove(), 4000);
        }
    }, 800);
});