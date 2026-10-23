<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics · Savouré Admin</title>
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .chart-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px; }
        @media (max-width: 1100px) { .chart-grid { grid-template-columns: 1fr; } }

        .chart-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 14px;
            padding: 20px 22px 22px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            min-width: 0;
        }
        .chart-card.full { margin-bottom: 24px; }
        .chart-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .chart-title { font-size: 1.05rem; font-weight: 700; color: #1b2a4a; margin: 0; }
        .chart-sub { font-size: 0.78rem; color: #777; margin: 3px 0 0; }
        .chart-note {
            font-size: 0.68rem; font-weight: 600; color: #a8843e;
            background: rgba(168, 132, 62, 0.12); padding: 4px 9px; border-radius: 20px; white-space: nowrap;
        }
        .chart-box { position: relative; height: 320px; }
        .chart-box.short { height: 280px; }
        .role-summary { display: flex; justify-content: center; gap: 22px; margin-top: 14px; font-size: 0.8rem; color: #444; }
        .role-summary span { display: flex; align-items: center; gap: 6px; }
        .role-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    </style>
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
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <h2 class="admin-banner-title">Analytics</h2>
                        <div class="admin-banner-sub">Track user activity, daily orders, and restaurant revenue</div>
                    </div>
                </div>
            </div>

            <!-- Summary stats -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <div class="stat-value" id="an-active-users">0</div>
                    <div class="stat-label">Active Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-receipt"></i></div>
                    </div>
                    <div class="stat-value" id="an-total-orders">0</div>
                    <div class="stat-label">Orders (30 days)</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-coins"></i></div>
                    </div>
                    <div class="stat-value" id="an-total-revenue">0</div>
                    <div class="stat-label">Revenue (EGP)</div>
                </div>

                <div class="stat-card dark">
                    <div class="stat-top">
                        <div class="stat-icon navy"><i class="fa-solid fa-trophy"></i></div>
                    </div>
                    <div class="stat-value" id="an-top-rest" style="font-size: 1.15rem;">-</div>
                    <div class="stat-label">Top Earning Restaurant</div>
                </div>
            </div>

            <!-- Orders + Users by role -->
            <div class="section-head">
                <div>
                    <span class="section-eye">Overview</span>
                    <h2 class="section-title">Activity</h2>
                </div>
            </div>

            <div class="chart-grid">
                <div class="chart-card">
                    <div class="chart-head">
                        <div>
                            <h3 class="chart-title">Orders in the last 30 days</h3>
                            <p class="chart-sub">Total orders placed per day across all restaurants</p>
                        </div>
                        <span class="chart-note">Sample data</span>
                    </div>
                    <div class="chart-box"><canvas id="orders-chart"></canvas></div>
                </div>

                <div class="chart-card">
                    <div class="chart-head">
                        <div>
                            <h3 class="chart-title">Active users by role</h3>
                            <p class="chart-sub">Staff and customer accounts</p>
                        </div>
                    </div>
                    <div class="chart-box short"><canvas id="users-chart"></canvas></div>
                    <div class="role-summary" id="role-summary"></div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="chart-card full">
                <div class="chart-head">
                    <div>
                        <h3 class="chart-title">Revenue by restaurant</h3>
                        <p class="chart-sub">Total revenue over the last 30 days, in EGP</p>
                    </div>
                    <span class="chart-note">Sample data</span>
                </div>
                <div class="chart-box"><canvas id="revenue-chart"></canvas></div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="../js/adminAnalytics.js"></script>
</body>
</html>