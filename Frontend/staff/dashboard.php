<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Resolves WHICH staff member is logged in (accepts nour / staff_nour / staff_03 / ?restaurant=...)
// and exposes $staff_id, $current_staff, $assigned_restaurant, $staff_param, $SAVOURE_DATA, $pending_orders_count, $open_requests_count
require_once __DIR__ . '/../../includes/staff_context.php';

// Everything on this page is rendered here in PHP (no dependency on an external JS file).
$res_rows = $SAVOURE_DATA['reservations'][$assigned_restaurant] ?? [];
$tbl_rows = $SAVOURE_DATA['tables'][$assigned_restaurant] ?? [];
$tbl_count = ['free' => 0, 'reserved' => 0, 'occupied' => 0];
foreach ($tbl_rows as $t) { if (isset($tbl_count[$t[2]])) $tbl_count[$t[2]]++; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · Savouré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Inject Staff Scope Context into Global Window -->
    <script>
        window.SAVOURE_STAFF_ID = <?= json_encode($staff_id) ?>;
        window.SAVOURE_STAFF_RESTAURANT = <?= json_encode($assigned_restaurant) ?>;
    </script>
    
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
      <script>const REQUIRED_ROLE = 'staff';</script>
    <script src="../js/guard.js"></script>
</head>
<body>

<div class="shell">
    <?php include __DIR__ . '/../../includes/staff_sidebar.php'; ?>

    <div class="main">
        <?php include __DIR__ . '/../../includes/headerStaff.php'; ?>

        <main class="content">

            <!-- Stats Bar -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-calendar-day"></i></div>
                    </div>
                    <div class="stat-value" id="stat-reservations"><?= count($res_rows) ?></div>
                    <div class="stat-label">Today's Reservations</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon navy"><i class="fa-solid fa-table-cells-large"></i></div>
                    </div>
                    <div class="stat-value" id="stat-total-tables"><?= count($tbl_rows) ?></div>
                    <div class="stat-label">Total Tables</div>
                    <div class="mini-row">
                        <div class="mini"><span class="mini-val" id="count-free"><?= $tbl_count['free'] ?></span><span class="mini-lbl free">Free</span></div>
                        <div class="mini"><span class="mini-val" id="count-reserved"><?= $tbl_count['reserved'] ?></span><span class="mini-lbl reserved">Reserved</span></div>
                        <div class="mini"><span class="mini-val" id="count-occupied"><?= $tbl_count['occupied'] ?></span><span class="mini-lbl occupied">Occupied</span></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-utensils"></i></div>
                    </div>
                    <div class="stat-value" id="stat-orders"><?= (int)$pending_orders_count ?></div>
                    <div class="stat-label">Pending Orders</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-comment-dots"></i></div>
                    </div>
                    <div class="stat-value" id="stat-requests"><?= (int)$open_requests_count ?></div>
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
                        <a href="reservations.php<?= $staff_param ?>" class="btn btn-ghost btn-sm">View all <i class="fa-solid fa-arrow-right"></i></a>
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
                                <?php foreach ($res_rows as $r): ?>
                                <tr>
                                    <td class="td-mono"><?= htmlspecialchars($r[1]) ?></td>
                                    <td class="td-name"><?= htmlspecialchars($r[2]) ?></td>
                                    <td><i class="fa-solid fa-user-group" style="color:var(--beige);margin-right:5px;"></i><?= (int)$r[3] ?></td>
                                    <td><strong style="color:var(--navy);"><?= htmlspecialchars($r[4]) ?></strong></td>
                                    <td><span class="badge badge-<?= htmlspecialchars($r[7]) ?>"><?= ucfirst(htmlspecialchars($r[7])) ?></span></td>
                                    <td><a href="tables.php<?= $staff_param ?>" class="btn btn-ghost btn-sm">Seat</a></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (!$res_rows): ?>
                                <tr><td colspan="6" class="td-muted" style="text-align:center;padding:24px;">No reservations for tonight.</td></tr>
                                <?php endif; ?>
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
                            <a href="tables.php<?= $staff_param ?>" class="btn btn-ghost btn-sm">Manage</a>
                        </div>
                        <div class="legend">
                            <span class="legend-item"><span class="dot" style="background:#2D7A5F;"></span>Free</span>
                            <span class="legend-item"><span class="dot" style="background:#A8843E;"></span>Reserved</span>
                            <span class="legend-item"><span class="dot" style="background:#8B2635;"></span>Occupied</span>
                        </div>
                        <div class="floor-mini" id="floor-mini-grid">
                            <?php foreach ($tbl_rows as $t): ?>
                            <a href="tables.php<?= $staff_param ?>" class="table-card <?= htmlspecialchars($t[2]) ?>" title="Table <?= (int)$t[0] ?> · <?= ucfirst(htmlspecialchars($t[2])) ?>">
                                <div class="tc-num"><?= (int)$t[0] ?></div>
                                <div class="tc-seats"><?= (int)$t[1] ?> seats</div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <h2 class="section-title" style="margin-bottom:14px;">Quick Actions</h2>
                        <div class="quick-links">
                            <a href="tables.php<?= $staff_param ?>" class="qlink">
                                <div class="qlink-icon"><i class="fa-solid fa-table-cells-large"></i></div>
                                <span class="qlink-label">Tables</span>
                            </a>
                            <a href="orders.php<?= $staff_param ?>" class="qlink">
                                <div class="qlink-icon"><i class="fa-solid fa-utensils"></i></div>
                                <span class="qlink-label">Orders</span>
                            </a>
                            <a href="requests.php<?= $staff_param ?>" class="qlink">
                                <div class="qlink-icon"><i class="fa-solid fa-comment-dots"></i></div>
                                <span class="qlink-label">Requests</span>
                            </a>
                            <a href="reservations.php<?= $staff_param ?>" class="qlink">
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
<script>
    window.SAVOURE_STAFF_ID = <?= json_encode($staff_id) ?>;
    window.SAVOURE_STAFF_RESTAURANT = <?= json_encode($assigned_restaurant) ?>;

    // Live clock
    (function tick() {
        const d = new Date(), p = n => String(n).padStart(2, '0');
        const c = document.getElementById('clk'), dt = document.getElementById('clk-date');
        if (c) c.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
        if (dt) dt.textContent = d.toLocaleDateString('en-EG', { weekday: 'short', month: 'short', day: 'numeric' });
        setTimeout(tick, 1000);
    })();

    // Pending Orders = incoming orders + pending customer pre-orders for THIS restaurant
    function refreshOrderStat() {
        const el = document.getElementById('stat-orders');
        if (el && window.SavoureLive) el.textContent = window.SavoureLive.base.orders + window.SavoureLive.pendingPreorders();
    }
    document.addEventListener('DOMContentLoaded', refreshOrderStat);
    window.addEventListener('storage', refreshOrderStat);
</script>
</body>
</html>
