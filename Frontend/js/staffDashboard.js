/**
 * Savouré Staff Dashboard - Self-Contained Controller
 */

// Per-Restaurant Default Reservations Data
const DASHBOARD_RESERVATIONS = {
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
    ],
    'Mo Bistro': [
        ['17:30', 'Ali, Youssef', 2, 'T-01', 'confirmed'],
        ['18:15', 'Soliman, Mariam', 4, 'T-04', 'confirmed'],
        ['19:00', 'Hamed, Ziad', 3, 'T-03', 'pending'],
        ['20:15', 'Abdellatif, Reem', 5, 'T-08', 'confirmed'],
        ['21:00', 'Fawzy, Karim', 2, 'T-02', 'confirmed']
    ]
};

// Per-Restaurant Default Floor Plans
const DASHBOARD_TABLES = {
    'The Revolving Restaurant': [
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
    ],
    'Le Petite Chef': [
        { num: 1, seats: 2, status: 'reserved' },
        { num: 2, seats: 4, status: 'free' },
        { num: 3, seats: 2, status: 'occupied' },
        { num: 4, seats: 6, status: 'free' },
        { num: 5, seats: 4, status: 'reserved' },
        { num: 6, seats: 2, status: 'occupied' },
        { num: 7, seats: 8, status: 'free' },
        { num: 8, seats: 4, status: 'occupied' },
        { num: 9, seats: 2, status: 'free' },
        { num: 10, seats: 4, status: 'reserved' },
        { num: 11, seats: 6, status: 'occupied' },
        { num: 12, seats: 2, status: 'free' }
    ],
    'Mo Bistro': [
        { num: 1, seats: 2, status: 'occupied' },
        { num: 2, seats: 4, status: 'reserved' },
        { num: 3, seats: 2, status: 'occupied' },
        { num: 4, seats: 6, status: 'occupied' },
        { num: 5, seats: 4, status: 'free' },
        { num: 6, seats: 2, status: 'free' },
        { num: 7, seats: 8, status: 'free' },
        { num: 8, seats: 4, status: 'reserved' },
        { num: 9, seats: 2, status: 'free' },
        { num: 10, seats: 4, status: 'free' },
        { num: 11, seats: 6, status: 'free' },
        { num: 12, seats: 2, status: 'free' }
    ]
};

function capitalize(str) {
    return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
}

function renderDashboard() {
    // Read the active restaurant set by PHP, fallback to Revolving Restaurant
    const activeRest = window.SAVOURE_STAFF_RESTAURANT || 'The Revolving Restaurant';
    const staffId = window.SAVOURE_STAFF_ID || 'nour';

    // Extract restaurant specific data directly
    // Prefer live data injected by dashboard.php (same source as the other staff pages);
    // fall back to the built-in demo data only if it is missing.
    const injected = window.SAVOURE_DASHBOARD || {};
    const currentReservations = injected.reservations || DASHBOARD_RESERVATIONS[activeRest] || DASHBOARD_RESERVATIONS['The Revolving Restaurant'];
    const currentTables = injected.tables || DASHBOARD_TABLES[activeRest] || DASHBOARD_TABLES['The Revolving Restaurant'];

    // 1. Render Reservations Table
    const tbody = document.getElementById('reservations-tbody');
    if (tbody) {
        tbody.innerHTML = '';
        currentReservations.forEach(([time, guest, party, table, status]) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="td-mono">${time}</td>
                <td class="td-name">${guest}</td>
                <td><i class="fa-solid fa-user-group" style="color:var(--beige);margin-right:5px;"></i>${party}</td>
                <td><strong style="color:var(--navy);">${table}</strong></td>
                <td><span class="badge badge-${status}">${capitalize(status)}</span></td>
                <td><a href="tables.php?staff=${encodeURIComponent(staffId)}" class="btn btn-ghost btn-sm">Seat</a></td>
            `;
            tbody.appendChild(tr);
        });
    }

    // 2. Render Floor Plan Tables
    const floorGrid = document.getElementById('floor-mini-grid');
    let freeCount = 0, reservedCount = 0, occupiedCount = 0;

    if (floorGrid) {
        floorGrid.innerHTML = '';
        currentTables.forEach((tbl) => {
            if (tbl.status === 'free') freeCount++;
            else if (tbl.status === 'reserved') reservedCount++;
            else if (tbl.status === 'occupied') occupiedCount++;

            const card = document.createElement('div');
            card.className = `table-card ${tbl.status}`;
            card.title = `Table ${tbl.num} · ${capitalize(tbl.status)}`;
            card.innerHTML = `
                <div class="tc-num">${tbl.num}</div>
                <div class="tc-seats">${tbl.seats} seats</div>
            `;
            floorGrid.appendChild(card);
        });
    }

    // 3. Update Stat Numbers
    const resStatEl = document.getElementById('stat-reservations');
    const ordersStatEl = document.getElementById('stat-orders');
    const requestsStatEl = document.getElementById('stat-requests');

    if (resStatEl) resStatEl.textContent = currentReservations.length;
    if (ordersStatEl) ordersStatEl.textContent = injected.pendingOrders != null ? injected.pendingOrders : 0;
    if (requestsStatEl) requestsStatEl.textContent = injected.openRequests != null ? injected.openRequests : 0;
    const totalEl = document.getElementById('stat-total-tables');
    if (totalEl) totalEl.textContent = currentTables.length;

    if (document.getElementById('count-free')) document.getElementById('count-free').textContent = freeCount;
    if (document.getElementById('count-reserved')) document.getElementById('count-reserved').textContent = reservedCount;
    if (document.getElementById('count-occupied')) document.getElementById('count-occupied').textContent = occupiedCount;
}

function tick() {
    const d = new Date(), p = n => String(n).padStart(2, '0');
    const clkEl = document.getElementById('clk');
    const dateEl = document.getElementById('clk-date');

    if (clkEl) clkEl.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    if (dateEl) dateEl.textContent = d.toLocaleDateString('en-EG', { weekday: 'short', month: 'short', day: 'numeric' });
}


document.addEventListener('DOMContentLoaded', () => {
    renderDashboard();
    tick();
    setInterval(tick, 1000);
});
