<?php 
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// 2 Staff accounts assigned to restaurants
$staff_accounts = [
    'nour' => [
        'id'         => 'nour',
        'name'       => 'Nour Khalil',
        'role'       => 'Floor Manager',
        'avatar'     => 'N',
        'restaurant' => 'The Revolving Restaurant',
    ],
    'tarek' => [
        'id'         => 'tarek',
        'name'       => 'Tarek Mansour',
        'role'       => 'Floor Manager',
        'avatar'     => 'T',
        'restaurant' => 'Le Petite Chef',
    ],
];

if (isset($_GET['staff']) && isset($staff_accounts[$_GET['staff']])) {
    $_SESSION['staff_account'] = $_GET['staff'];
} elseif (isset($_GET['restaurant'])) {
    $_SESSION['staff_account'] = ($_GET['restaurant'] === 'Le Petite Chef') ? 'tarek' : 'nour';
}

$staff_id = $_GET['staff'] ?? $_SESSION['staff_account'] ?? $_COOKIE['staff_account'] ?? 'nour';
if (!isset($staff_accounts[$staff_id])) {
    $staff_id = 'nour';
}
$_SESSION['staff_account'] = $staff_id;

$current_staff = $staff_accounts[$staff_id];
$assigned_restaurant = $current_staff['restaurant'];
$page = basename($_SERVER['PHP_SELF']); 
$staff_param = '?staff=' . urlencode($staff_id);
?>
<aside class="sidebar">
    <div class="brand">
        <div class="brand-mark">⚜</div>
        <div>
            <div class="brand-name">Souvré</div>
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
            <span class="nav-badge" id="orders-badge">2</span>
        </a>
        <a href="requests.php<?= $staff_param ?>" class="nav-link <?= $page === 'requests.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-comment-dots"></i></span>
            Requests
            <span class="nav-badge" id="req-badge">2</span>
        </a>
        <a href="reservations.php<?= $staff_param ?>" class="nav-link <?= $page === 'reservations.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-calendar-check"></i></span>
            Reservations
        </a>
    </nav>

    <div class="sidebar-foot">
        <div class="account-switch">
            <div class="account-switch-title">Staff Account</div>
            <div class="account-pills">
                <a href="?staff=nour" onclick="switchStaffAccount('nour', event)" class="acc-pill <?= $staff_id === 'nour' ? 'active' : '' ?>" title="The Revolving Restaurant">
                    <span class="acc-dot"></span> Nour K.
                </a>
                <a href="?staff=tarek" onclick="switchStaffAccount('tarek', event)" class="acc-pill <?= $staff_id === 'tarek' ? 'active' : '' ?>" title="Le Petite Chef">
                    <span class="acc-dot"></span> Tarek M.
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
        <a href="#" class="logout-btn" onclick="return confirm('Log out?')">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>

<script>
function switchStaffAccount(id, ev) {
    if (ev) ev.preventDefault();
    document.cookie = "staff_account=" + encodeURIComponent(id) + ";path=/;max-age=31536000";
    try { localStorage.setItem('staff_account', id); } catch(e){}
    const url = new URL(window.location.href);
    url.searchParams.set('staff', id);
    window.location.href = url.toString();
}
</script>
