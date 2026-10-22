<?php 
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Admin accounts dataset
$admin_accounts = [
    'admin' => [
        'id'       => 'admin',
        'name'     => 'System Admin',
        'role'     => 'Super Administrator',
        'avatar'   => 'A',
        'email'    => 'admin@savoure.com',
    ]
];

// Switch account via URL query, session, or cookie
if (isset($_GET['admin']) && isset($admin_accounts[$_GET['admin']])) {
    $_SESSION['admin_account'] = $_GET['admin'];
}

$admin_id = $_GET['admin'] ?? $_SESSION['admin_account'] ?? $_COOKIE['admin_account'] ?? 'admin';
if (!isset($admin_accounts[$admin_id])) {
    $admin_id = 'admin';
}
$_SESSION['admin_account'] = $admin_id;

$current_admin = $admin_accounts[$admin_id];
$page = basename($_SERVER['PHP_SELF']); 
$admin_param = '?admin=' . urlencode($admin_id);
?>
<aside class="sidebar">
    <div class="brand">
        <div class="brand-mark">⚜</div>
        <div>
            <div class="brand-name">Savouré</div>
            <div class="brand-sub">Admin Portal</div>
        </div>
    </div>

    <nav>
        <div class="nav-label">System Control</div>

        <!-- Dashboard Link -->
        <a href="dashboard.php<?= $admin_param ?>" class="nav-link <?= $page === 'dashboard.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
            Dashboard
        </a>

        <!-- User Management -->
        <a href="user_management.php<?= $admin_param ?>" class="nav-link <?= $page === 'user_management.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-users-gear"></i></span>
            User Management
        </a>

        <!-- Restaurant Management -->
        <a href="restaurant_management.php<?= $admin_param ?>" class="nav-link <?= $page === 'restaurant_management.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-store"></i></span>
            Restaurant Management
        </a>

        <a href="analytics.php<?= $admin_param ?>" class="nav-link <?= $page === 'analytics.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-chart-pie"></i></span>
            Analytics
        </a>
    </nav>

    <div class="sidebar-foot">
        <!-- Admin Info Card -->
        <div class="staff-chip admin-chip">
            <div class="avatar"><?= htmlspecialchars($current_admin['avatar']) ?></div>
            <div>
                <span class="staff-name"><?= htmlspecialchars($current_admin['name']) ?></span>
                <span class="staff-role"><?= htmlspecialchars($current_admin['role']) ?></span>
                <span class="staff-branch"><i class="fa-solid fa-shield-halved"></i> <?= htmlspecialchars($current_admin['email']) ?></span>
            </div>
        </div>

        <!-- Logout Link -->
        <a href="../auth/login.php" class="logout-btn"
           onclick="if (confirm('Log out from Admin Portal?')) { localStorage.removeItem('savoure_current_user'); return true; } return false;">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>

<script>
function switchAdminAccount(id, ev) {
    if (ev) ev.preventDefault();
    document.cookie = "admin_account=" + encodeURIComponent(id) + ";path=/;max-age=31536000";
    try { 
        localStorage.setItem('admin_account', id); 
    } catch(e) {}
    const url = new URL(window.location.href);
    url.searchParams.set('admin', id);
    window.location.href = url.toString();
}
</script>