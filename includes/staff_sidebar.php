<?php
// All staff resolution lives in staff_context.php (shared by every page).
require_once __DIR__ . '/staff_context.php';
$page = basename($_SERVER['PHP_SELF']);
?>

<style>
/* Account switcher: 4 pills fit on one row, never overflow the sidebar */
.sidebar .account-pills { display: grid !important; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 4px; }
.sidebar .acc-pill { min-width: 0; padding: 6px 4px !important; font-size: .66rem !important; justify-content: center; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sidebar .acc-pill .acc-dot { display: none; }
/* Keep the footer visible: menu scrolls if the window is short */
.sidebar nav { min-height: 0; overflow-y: auto; }
.sidebar .nav-badge { margin-left: auto; }
</style>

<script>
    // Sync PHP active restaurant to JS context
    window.SAVOURE_STAFF_RESTAURANT = <?= json_encode($assigned_restaurant) ?>;
    try { localStorage.setItem('savoure_active_restaurant', <?= json_encode($assigned_restaurant) ?>); } catch(e){}
</script>

<aside class="sidebar">
    <div class="brand">
        <div class="brand-mark">⚜</div>
        <div>
            <div class="brand-name">Savouré</div>
            <div class="restaurant-name"><?= htmlspecialchars($current_staff['restaurant']) ?></div>
            <div class="brand-sub">Staff Portal</div>
        </div>
    </div>

    <nav>
        <div class="nav-label">Menu</div>

        <a href="dashboard.php<?= $staff_param ?>" class="nav-link <?= $page === 'dashboard.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-gauge-high"></i></span>
            Dashboard
        </a>
        <a href="tables.php<?= $staff_param ?>" class="nav-link <?= $page === 'tables.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-table-cells-large"></i></span>
            Manage Tables
        </a>
        <a href="orders.php<?= $staff_param ?>" class="nav-link <?= $page === 'orders.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-utensils"></i></span>
            Orders
            <span class="nav-badge" id="orders-badge"><?= (int)$pending_orders_count ?></span>
        </a>
        <a href="requests.php<?= $staff_param ?>" class="nav-link <?= $page === 'requests.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-comment-dots"></i></span>
            Requests
            <span class="nav-badge" id="req-badge"><?= (int)$open_requests_count ?></span>
        </a>
        <a href="reservations.php<?= $staff_param ?>" class="nav-link <?= $page === 'reservations.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-calendar-check"></i></span>
            Reservations
            <span class="nav-badge" id="res-badge"><?= (int)$pending_reservations_count ?></span>
        </a>
    </nav>

   <div class="sidebar-foot">
        <div class="account-switch">
            <div class="account-switch-title">Staff Account</div>
            <div class="account-pills">
                <a href="?staff=nour" onclick="switchStaffAccount('nour', event)" class="acc-pill <?= $staff_id === 'nour' ? 'active' : '' ?>" title="The Revolving Restaurant">
                    <span class="acc-dot"></span> Nour
                </a>
                <a href="?staff=tarek" onclick="switchStaffAccount('tarek', event)" class="acc-pill <?= $staff_id === 'tarek' ? 'active' : '' ?>" title="Le Petite Chef">
                    <span class="acc-dot"></span> Tarek
                </a>
                <a href="?staff=michael" onclick="switchStaffAccount('michael', event)" class="acc-pill <?= $staff_id === 'michael' ? 'active' : '' ?>" title="Mo Bistro">
                    <span class="acc-dot"></span> Michael
                </a>
                <a href="?staff=pierre" onclick="switchStaffAccount('pierre', event)" class="acc-pill <?= $staff_id === 'pierre' ? 'active' : '' ?>" title="Le Petite Chef">
                    <span class="acc-dot"></span> Pierre
                </a>
            </div>
        </div> 

        <div class="staff-chip">
            <div class="avatar"><?= htmlspecialchars($current_staff['avatar']) ?></div>
            <div>
                <span class="staff-name"><?= htmlspecialchars($current_staff['name']) ?></span>
                <span class="staff-role"><?= htmlspecialchars($current_staff['role']) ?></span>
                <span class="staff-branch"><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($assigned_restaurant) ?></span>
            </div>
        </div>
      <a href="../auth/login.php" class="logout-btn"
   onclick="if (confirm('Log out?')) { localStorage.removeItem('savoure_current_user'); return true; } return false;">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
</a>
    </div>
</aside>

<script>
/**
 * Live sidebar badges (shared by every staff page).
 *  Orders       = incoming orders + pending customer pre-orders (localStorage.savoure_preorders)
 *  Requests     = pending requests
 *  Reservations = reservations still "pending"
 * On a page that lists the items, the badge counts what is really on screen (so it drops the moment
 * you accept / answer / confirm); on other pages it starts from the server data.
 * A badge with 0 is hidden.
 */
(function () {
    const REST = <?= json_encode($assigned_restaurant) ?>;
    const BASE = <?= json_encode([
        'orders'       => (int)$pending_orders_count,
        'requests'     => (int)$open_requests_count,
        'reservations' => (int)$pending_reservations_count,
    ]) ?>;

    function pendingPreorders() {
        try {
            const list = JSON.parse(localStorage.getItem('savoure_preorders')) || [];
            return list.filter(o => o && o.restaurant === REST && o.status === 'pending').length;
        } catch (e) { return 0; }
    }
    const count = sel => document.querySelectorAll(sel).length;
    function setBadge(id, n) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = n;
        el.style.display = n > 0 ? '' : 'none';
    }
    function refresh() {
        const onOrders = document.getElementById('in-list');
        setBadge('orders-badge', (onOrders ? count('#in-list .order-card') : BASE.orders) + pendingPreorders());

        const onReq = document.getElementById('reqs-list');
        setBadge('req-badge', onReq ? count('.req-card[data-status="pending"]') : BASE.requests);

        const onRes = document.querySelector('.res-banner') && document.getElementById('tbody');
        setBadge('res-badge', onRes ? count('#tbody tr[data-status="pending"]') : BASE.reservations);
    }
    window.SavoureLive = { base: BASE, pendingPreorders: pendingPreorders, refresh: refresh };
    document.addEventListener('DOMContentLoaded', refresh);
    window.addEventListener('storage', refresh);
    setInterval(refresh, 1000);
})();
</script>

<script>
function switchStaffAccount(id, ev) {
    if (ev) ev.preventDefault();
    document.cookie = "staff_account=" + encodeURIComponent(id) + ";path=/;max-age=31536000";
    try { 
        localStorage.setItem('staff_account', id); 
        const rests = {
            'nour': 'The Revolving Restaurant',
            'tarek': 'Le Petite Chef',
            'michael': 'Mo Bistro',
            'pierre': 'Le Petite Chef'
        };
        if (rests[id]) localStorage.setItem('savoure_active_restaurant', rests[id]);
    } catch(e){}
    const url = new URL(window.location.href);
    url.searchParams.set('staff', id);
    window.location.href = url.toString();
}
</script>
