<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard · Savouré</title>
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <script>const REQUIRED_ROLE = 'admin';</script>
    <script src="../js/guard.js"></script>
</head>
<body>

<div class="shell">
    <?php include '../../includes/admin_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerAdmin.php'; ?>

        <main class="content">

            <!-- Banner -->
            <div class="admin-banner">
                <div class="admin-banner-info">
                    <div class="admin-banner-icon">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <h2 class="admin-banner-title">Welcome to Savouré Admin Control</h2>
                        <div class="admin-banner-sub">Manage restaurants, configure staff assignments, and administer user access</div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-utensils"></i></div>
                    </div>
                    <div class="stat-value" id="dash-total-rests">3</div>
                    <div class="stat-label">Active Restaurants</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <div class="stat-value" id="dash-total-users">11</div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-user-tie"></i></div>
                    </div>
                    <div class="stat-value" id="dash-staff-count">5</div>
                    <div class="stat-label">Staff On Duty</div>
                </div>

                <div class="stat-card dark">
                    <div class="stat-top">
                        <div class="stat-icon navy"><i class="fa-solid fa-shield-halved"></i></div>
                    </div>
                    <div class="stat-value">Active</div>
                    <div class="stat-label">System Status</div>
                </div>
            </div>

            <!-- Quick Access Portals -->
            <div class="section-head">
                <div>
                    <span class="section-eye">Administration</span>
                    <h2 class="section-title">Quick Portals</h2>
                </div>
            </div>

            <div class="quick-links">
                <a href="user_management.php" class="qlink">
                    <div class="qlink-icon"><i class="fa-solid fa-users-gear"></i></div>
                    <div class="qlink-label">User Management</div>
                </a>

                <a href="restaurant_management.php" class="qlink">
                    <div class="qlink-icon"><i class="fa-solid fa-store"></i></div>
                    <div class="qlink-label">Restaurant Management</div>
                </a>

                <a href="user_management.php" class="qlink">
                    <div class="qlink-icon"><i class="fa-solid fa-id-card-clip"></i></div>
                    <div class="qlink-label">Staff Assignment</div>
                </a>

                <a href="restaurant_management.php" class="qlink">
                    <div class="qlink-icon"><i class="fa-solid fa-plus"></i></div>
                    <div class="qlink-label">New Restaurant</div>
                </a>
            </div>

        </main>
    </div>
</div>

<script src="../js/adminDashboard.js"></script>
</body>
</html>
