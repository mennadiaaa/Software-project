
function tickClock() {
    const clk = document.getElementById('clk');
    const clkDate = document.getElementById('clk-date');
    if (clk && clkDate) {
        const d = new Date(), p = n => String(n).padStart(2, '0');
        clk.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
        clkDate.textContent = d.toLocaleDateString('en-EG', { weekday: 'short', month: 'short', day: 'numeric' });
    }
}

function loadDashboardMetrics() {
    const usersRaw = localStorage.getItem('savoure_users');
    const restsRaw = localStorage.getItem('savoure_restaurants');

    let userCount = 11;
    let staffCount = 5;
    let restCount = 3;

    if (usersRaw) {
        try {
            const users = JSON.parse(usersRaw);
            userCount = users.length;
            staffCount = users.filter(u => u.role === 'staff').length;
        } catch (e) {}
    }

    if (restsRaw) {
        try {
            const rests = JSON.parse(restsRaw);
            restCount = rests.length;
        } catch (e) {}
    }

    const totalUsersEl = document.getElementById('dash-total-users');
    const staffCountEl = document.getElementById('dash-staff-count');
    const restCountEl = document.getElementById('dash-total-rests');

    if (totalUsersEl) totalUsersEl.textContent = userCount;
    if (staffCountEl) staffCountEl.textContent = staffCount;
    if (restCountEl) restCountEl.textContent = restCount;
}

document.addEventListener('DOMContentLoaded', () => {
    setInterval(tickClock, 1000);
    tickClock();
    loadDashboardMetrics();
});
