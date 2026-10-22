<?php
// All staff resolution lives in staff_context.php (shared by every page).
require_once __DIR__ . '/staff_context.php';
$page = basename($_SERVER['PHP_SELF']);
?>

<style>
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
// Live sidebar badges (shared by every staff page).
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