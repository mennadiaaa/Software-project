/**
 * Savouré Admin Analytics
 * - Users by role: real data from localStorage 'savoure_users' (falls back to the default seed counts)
 * - Orders & revenue: generated sample data (stable per day, so it doesn't change on refresh)
 */

const USERS_STORAGE_KEY = 'savoure_users';
const RESTS_STORAGE_KEY = 'savoure_restaurants';

const DEFAULT_RESTAURANT_NAMES = ['The Revolving Restaurant', 'Le Petite Chef', 'Mo Bistro'];

// Sample "average spend per order" (EGP) and daily order volume per restaurant
const RESTAURANT_PROFILES = {
    'The Revolving Restaurant': { avgTicket: 1100, baseOrders: 38 },
    'Le Petite Chef':           { avgTicket: 2800, baseOrders: 14 },
    'Mo Bistro':                { avgTicket: 600,  baseOrders: 52 }
};

const COLORS = {
    navy: '#1b2a4a',
    gold: '#a8843e',
    goldSoft: 'rgba(168, 132, 62, 0.15)',
    cream: '#d9c9a3',
    grid: 'rgba(0, 0, 0, 0.06)',
    text: '#555'
};
const REST_COLORS = [COLORS.navy, COLORS.gold, '#6b7fa8', '#c9a45c', '#3d5a80', '#8e6e30'];

/* ---------- Helpers ---------- */
function tickClock() {
    const clk = document.getElementById('clk');
    const clkDate = document.getElementById('clk-date');
    if (clk && clkDate) {
        const d = new Date(), p = n => String(n).padStart(2, '0');
        clk.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
        clkDate.textContent = d.toLocaleDateString('en-EG', { weekday: 'short', month: 'short', day: 'numeric' });
    }
}

// Small deterministic random generator: same seed string -> same numbers
function seededRandom(seedStr) {
    let h = 1779033703 ^ seedStr.length;
    for (let i = 0; i < seedStr.length; i++) {
        h = Math.imul(h ^ seedStr.charCodeAt(i), 3432918353);
        h = (h << 13) | (h >>> 19);
    }
    return function () {
        h = Math.imul(h ^ (h >>> 16), 2246822507);
        h = Math.imul(h ^ (h >>> 13), 3266489909);
        h ^= h >>> 16;
        return (h >>> 0) / 4294967296;
    };
}

function readJSON(key) {
    try {
        const v = JSON.parse(localStorage.getItem(key) || 'null');
        return Array.isArray(v) ? v : null;
    } catch (e) { return null; }
}

function formatNumber(n) {
    return Math.round(n).toLocaleString('en-US');
}

/* ---------- Data ---------- */
function getUserCounts() {
    const stored = readJSON(USERS_STORAGE_KEY);
    if (stored) {
        return {
            staff: stored.filter(u => u.role === 'staff').length,
            customer: stored.filter(u => u.role === 'customer').length
        };
    }
    // Same defaults as the admin user management page
    return { staff: 5, customer: 4 };
}

function getRestaurantNames() {
    const stored = readJSON(RESTS_STORAGE_KEY);
    if (stored && stored.length) return stored.map(r => r.name);
    return DEFAULT_RESTAURANT_NAMES;
}

function profileFor(name) {
    if (RESTAURANT_PROFILES[name]) return RESTAURANT_PROFILES[name];
    // Newly added restaurants get a stable sample profile derived from their name
    const rnd = seededRandom('profile:' + name);
    return { avgTicket: 500 + Math.round(rnd() * 1800), baseOrders: 12 + Math.round(rnd() * 40) };
}

// 30 days of sample orders, per restaurant. Weekends (Fri/Sat in Egypt) are busier.
function buildSampleData() {
    const names = getRestaurantNames();
    const labels = [];
    const dailyTotals = [];
    const perRestOrders = {};
    names.forEach(n => perRestOrders[n] = 0);

    const today = new Date();
    for (let i = 29; i >= 0; i--) {
        const d = new Date(today.getFullYear(), today.getMonth(), today.getDate() - i);
        const dayKey = d.toISOString().slice(0, 10);
        const dow = d.getDay(); // 5 = Fri, 6 = Sat
        const weekendBoost = (dow === 5 || dow === 6) ? 1.35 : (dow === 4 ? 1.12 : 1);

        let dayTotal = 0;
        names.forEach(name => {
            const rnd = seededRandom(name + dayKey);
            const base = profileFor(name).baseOrders;
            const orders = Math.max(0, Math.round(base * weekendBoost * (0.8 + rnd() * 0.4)));
            perRestOrders[name] += orders;
            dayTotal += orders;
        });

        labels.push(d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        dailyTotals.push(dayTotal);
    }

    const revenue = names.map(name => perRestOrders[name] * profileFor(name).avgTicket);
    return { names, labels, dailyTotals, revenue };
}

/* ---------- Charts ---------- */
function baseScales(yTitle, yFormatter) {
    return {
        x: {
            grid: { display: false },
            ticks: { color: COLORS.text, maxRotation: 0, autoSkipPadding: 14, font: { size: 11 } }
        },
        y: {
            beginAtZero: true,
            grid: { color: COLORS.grid },
            border: { display: false },
            title: { display: !!yTitle, text: yTitle, color: COLORS.text, font: { size: 11 } },
            ticks: { color: COLORS.text, font: { size: 11 }, callback: yFormatter }
        }
    };
}

function renderOrdersChart(data) {
    new Chart(document.getElementById('orders-chart'), {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Orders',
                data: data.dailyTotals,
                borderColor: COLORS.navy,
                backgroundColor: COLORS.goldSoft,
                fill: true,
                tension: 0.35,
                borderWidth: 2.5,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: COLORS.gold
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} orders` } }
            },
            scales: baseScales('Orders per day')
        }
    });
}

function renderUsersChart(counts) {
    const total = counts.staff + counts.customer;

    new Chart(document.getElementById('users-chart'), {
        type: 'doughnut',
        data: {
            labels: ['Staff', 'Customers'],
            datasets: [{
                data: [counts.staff, counts.customer],
                backgroundColor: [COLORS.navy, COLORS.gold],
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const pct = total ? Math.round((ctx.parsed / total) * 100) : 0;
                            return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    const summary = document.getElementById('role-summary');
    if (summary) {
        summary.innerHTML =
            `<span><i class="role-dot" style="background:${COLORS.navy}"></i>Staff: <strong>${counts.staff}</strong></span>` +
            `<span><i class="role-dot" style="background:${COLORS.gold}"></i>Customers: <strong>${counts.customer}</strong></span>`;
    }
}

function renderRevenueChart(data) {
    new Chart(document.getElementById('revenue-chart'), {
        type: 'bar',
        data: {
            labels: data.names,
            datasets: [{
                label: 'Revenue (EGP)',
                data: data.revenue,
                backgroundColor: data.names.map((_, i) => REST_COLORS[i % REST_COLORS.length]),
                borderRadius: 8,
                maxBarThickness: 90
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${formatNumber(ctx.parsed.y)} EGP` } }
            },
            scales: baseScales('Revenue (EGP)', v => (v >= 1000 ? v / 1000 + 'k' : v))
        }
    });
}

/* ---------- Summary stats ---------- */
function renderSummary(data, counts) {
    const totalOrders = data.dailyTotals.reduce((a, b) => a + b, 0);
    const totalRevenue = data.revenue.reduce((a, b) => a + b, 0);
    const topIndex = data.revenue.indexOf(Math.max.apply(null, data.revenue));

    const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    set('an-active-users', counts.staff + counts.customer);
    set('an-total-orders', formatNumber(totalOrders));
    set('an-total-revenue', formatNumber(totalRevenue));
    set('an-top-rest', data.names[topIndex] || '-');
}

/* ---------- Init ---------- */
document.addEventListener('DOMContentLoaded', () => {
    setInterval(tickClock, 1000);
    tickClock();

    if (typeof Chart === 'undefined') {
        console.error('Chart.js failed to load. Check your internet connection or the CDN link.');
        return;
    }

    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;

    const counts = getUserCounts();
    const data = buildSampleData();

    renderSummary(data, counts);
    renderOrdersChart(data);
    renderUsersChart(counts);
    renderRevenueChart(data);
});