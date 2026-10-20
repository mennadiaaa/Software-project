<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · Savouré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .dash-grid { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 24px; }
        @media (max-width: 1100px) { .dash-grid { grid-template-columns: 1fr; } }
        .floor-mini { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
        .floor-mini .table-card { padding: 12px 8px; border-radius: 10px; text-align: center; width: 100%; min-width: 0; cursor: pointer; text-decoration: none; }
        .floor-mini .tc-num { font-size: 1.4rem; line-height: 1.1; }
        .floor-mini .tc-seats, .floor-mini .tc-guest { font-size: .6rem; white-space: nowrap; }
        .legend { display: flex; gap: 14px; margin-bottom: 12px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 600; }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .badge-clickable { cursor: pointer; user-select: none; }
    </style>
</head>
<body>

<div class="shell">
    <?php include '../../includes/staff_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerStaff.php'; ?>

        <main class="content">

            <!-- Stats Bar -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-calendar-day"></i></div>
                    </div>
                    <div class="stat-value" id="stat-reservations">0</div>
                    <div class="stat-label">Today's Reservations</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon navy"><i class="fa-solid fa-table-cells-large"></i></div>
                    </div>
                    <div class="stat-value" id="stat-total-tables">12</div>
                    <div class="stat-label">Total Tables</div>
                    <div class="mini-row">
                        <div class="mini"><span class="mini-val" id="count-free">0</span><span class="mini-lbl free">Free</span></div>
                        <div class="mini"><span class="mini-val" id="count-reserved">0</span><span class="mini-lbl reserved">Reserved</span></div>
                        <div class="mini"><span class="mini-val" id="count-occupied">0</span><span class="mini-lbl occupied">Occupied</span></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-utensils"></i></div>
                    </div>
                    <div class="stat-value" id="stat-orders">0</div>
                    <div class="stat-label">Pending Orders</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-comment-dots"></i></div>
                    </div>
                    <div class="stat-value" id="stat-requests">0</div>
                    <div class="stat-label">Open Requests</div>
                </div>
            </div>

            <div class="dash-grid">
                <!-- Left Column: Today's Reservations -->
                <div>
                    <div class="section-head">
                        <div>
                            <span class="section-eye">Tonight</span>
                            <h2 class="section-title">Today's Reservations</h2>
                        </div>
                        <a href="reservations.php<?= $staff_param ?? '' ?>" class="btn btn-ghost btn-sm">View all <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Guest</th>
                                    <th>Party</th>
                                    <th>Table</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="reservations-tbody">
                                <!-- Rendered dynamically via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Column: Floor Plan & Quick Links -->
                <div style="display:flex;flex-direction:column;gap:24px;">
                    <div>
                        <div class="section-head">
                            <div>
                                <span class="section-eye">Live</span>
                                <h2 class="section-title">Floor Plan</h2>
                            </div>
                            <a href="tables.php<?= $staff_param ?? '' ?>" class="btn btn-ghost btn-sm">Manage</a>
                        </div>
                        <div class="legend">
                            <span class="legend-item"><span class="dot" style="background:#2D7A5F;"></span>Free</span>
                            <span class="legend-item"><span class="dot" style="background:#A8843E;"></span>Reserved</span>
                            <span class="legend-item"><span class="dot" style="background:#8B2635;"></span>Occupied</span>
                        </div>
                        <div class="floor-mini" id="floor-mini-grid">
                            <!-- Rendered dynamically via JavaScript -->
                        </div>
                    </div>

                    <div>
                        <h2 class="section-title" style="margin-bottom:14px;">Quick Actions</h2>
                        <div class="quick-links">
                            <a href="tables.php<?= $staff_param ?? '' ?>" class="qlink">
                                <div class="qlink-icon"><i class="fa-solid fa-table-cells-large"></i></div>
                                <span class="qlink-label">Tables</span>
                            </a>
                            <a href="orders.php<?= $staff_param ?? '' ?>" class="qlink">
                                <div class="qlink-icon"><i class="fa-solid fa-utensils"></i></div>
                                <span class="qlink-label">Orders</span>
                            </a>
                            <a href="requests.php<?= $staff_param ?? '' ?>" class="qlink">
                                <div class="qlink-icon"><i class="fa-solid fa-comment-dots"></i></div>
                                <span class="qlink-label">Requests</span>
                            </a>
                            <a href="reservations.php<?= $staff_param ?? '' ?>" class="qlink">
                                <div class="qlink-icon"><i class="fa-solid fa-calendar-check"></i></div>
                                <span class="qlink-label">Bookings</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<div class="toast-zone" id="toasts"></div>

<!-- External Dashboard Logic -->
<script src="../js/staffDashboard.js"></script>
</body>
</html>
