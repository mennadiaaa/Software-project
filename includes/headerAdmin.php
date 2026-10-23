<?php
$admin_page_titles = [
    'dashboard.php' => 'Admin Dashboard',
    'user_management.php' => 'User Management',
    'restaurant_management.php' => 'Restaurant Management',
    'analytics.php' => 'Analytics Overview',
    'profile.php' => 'My Profile'
];
$current_page_name = basename($_SERVER['PHP_SELF']);
$page_display_title = $admin_page_titles[$current_page_name] ?? 'Admin Portal';
?>
<header class="topbar">
    <div>
        <div class="page-eyebrow">Where every reservation matters · Cairo</div>
        <h1 class="page-title"><?= htmlspecialchars($page_display_title) ?></h1>
    </div>
    <div class="topbar-right">
        <div class="live-clock">
            <span class="clock-time" id="clk"></span>
            <span class="clock-date" id="clk-date"></span>
        </div>
    </div>
</header>