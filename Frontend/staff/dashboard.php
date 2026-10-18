<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · Souvré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .dash-grid { display: grid; grid-template-columns: 3fr 2fr; gap: 24px; }
        @media (max-width: 1100px) { .dash-grid { grid-template-columns: 1fr; } }
        .floor-mini { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; }
        .floor-mini .table-card { padding: 12px 8px; border-radius: 10px; }
        .floor-mini .tc-num { font-size: 1.4rem; }
        .floor-mini .tc-seats, .floor-mini .tc-guest { font-size: .62rem; }
        .legend { display: flex; gap: 16px; margin-bottom: 12px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 600; }
        .dot { width: 9px; height: 9px; border-radius: 50%; }
    </style>
</head>
<body>

<div class="shell">
    <?php 
    include '../../includes/staff_sidebar.php'; 
    $all_res = [
        'The Revolving Restaurant' => [
            ['18:30','Hassan, Ahmed',   2,'T-02','confirmed'],
            ['19:00','El-Sayed, Fatima',2,'T-06','confirmed'],
            ['19:30','Mansour, Omar',   4,'T-10','confirmed'],
            ['20:00','Karim, Leila',    3,'T-04','pending'],
            ['20:30','Ibrahim, Khaled', 6,'T-07','confirmed'],
            ['21:00','Naguib, Sara',    2,'T-01','confirmed'],
        ],
        'Le Petite Chef' => [
            ['18:00','El-Masry, Mostafa', 2,'T-01','confirmed'],
            ['18:45','Ghanem, Nourhan',   4,'T-03','confirmed'],
            ['19:15','Farouk, Sherif',    2,'T-05','pending'],
            ['20:00','Khattab, Salma',    3,'T-08','confirmed'],
            ['20:30','El-Kady, Tamer',    5,'T-09','confirmed'],
            ['21:15','Radwan, Dina',      2,'T-02','confirmed'],
        ],
    ];
    $res = $all_res[$assigned_restaurant] ?? $all_res['The Revolving Restaurant'];
    ?>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="page-eyebrow"><?= htmlspecialchars($assigned_restaurant) ?> · Cairo</div>
                <h1 class="page-title">Staff Dashboard</h1>
            </div>
            <div class="topbar-right">
                <div class="live-clock" style="text-align:right;">
                    <span class="clock-time" id="clk"></span>
                    <span class="clock-date" id="clk-date"></span>
                </div>
            </div>
        </header>

        <main class="content">

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-calendar-day"></i></div>
                    </div>
                    <div class="stat-value" data-target="<?= count($res) ?>">0</div>
                    <div class="stat-label">Today's Reservations</div>
                </div>

                <div class="stat-card dark">
                    <div class="stat-top">
                        <div class="stat-icon navy"><i class="fa-solid fa-table-cells-large"></i></div>
                    </div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Total Tables</div>
                    <div class="mini-row">
                        <div class="mini"><span class="mini-val">5</span><span class="mini-lbl free">Free</span></div>
                        <div class="mini"><span class="mini-val">3</span><span class="mini-lbl reserved">Reserved</span></div>
                        <div class="mini"><span class="mini-val">4</span><span class="mini-lbl occupied">Occupied</span></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-utensils"></i></div>
                    </div>
                    <div class="stat-value" data-target="2">0</div>
                    <div class="stat-label">Pending Orders</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-comment-dots"></i></div>
                    </div>
                    <div class="stat-value" data-target="2">0</div>
                    <div class="stat-label">Open Requests</div>
                </div>
            </div>

            <div class="dash-grid">
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
                            <tbody>
                                <?php
                                foreach ($res as [$time, $guest, $party, $table, $status]):
                                ?>
                                <tr>
                                    <td class="td-mono"><?= $time ?></td>
                                    <td class="td-name"><?= $guest ?></td>
                                    <td><i class="fa-solid fa-user-group" style="color:var(--beige);margin-right:5px;"></i><?= $party ?></td>
                                    <td><strong style="color:var(--navy);"><?= $table ?></strong></td>
                                    <td><span class="badge badge-<?= $status ?>"><?= ucfirst($status) ?></span></td>
                                    <td><a href="tables.php<?= $staff_param ?>" class="btn btn-ghost btn-sm">Seat</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

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
                        <div class="floor-mini">
                            <?php
                            $tables = [
                                [1,2,'free'],[2,4,'reserved'],[3,2,'occupied'],[4,6,'free'],
                                [5,4,'occupied'],[6,2,'reserved'],[7,8,'free'],[8,4,'occupied'],
                                [9,2,'free'],[10,4,'reserved'],[11,6,'occupied'],[12,2,'free'],
                            ];
                            foreach ($tables as [$n, $s, $st]):
                            ?>
                            <a href="tables.php<?= $staff_param ?>" class="table-card <?= $st ?>" title="Table <?= $n ?> · <?= ucfirst($st) ?>">
                                <div class="tc-num"><?= $n ?></div>
                                <div class="tc-seats"><?= $s ?> seats</div>
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
function tick() {
    const d = new Date(), p = n => String(n).padStart(2,'0');
    document.getElementById('clk').textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    document.getElementById('clk-date').textContent = d.toLocaleDateString('en-EG', { weekday:'short', month:'short', day:'numeric' });
}
tick(); setInterval(tick, 1000);

document.querySelectorAll('[data-target]').forEach(el => {
    const end = +el.dataset.target, dur = 900;
    let t = 0;
    const step = setInterval(() => {
        t += dur / 60;
        el.textContent = Math.min(Math.round(end * t / dur), end);
        if (t >= dur) clearInterval(step);
    }, dur / 60);
});

setTimeout(() => {
    const z = document.getElementById('toasts');
    z.innerHTML = '<div class="toast">Good evening, <?= htmlspecialchars(explode(' ', $current_staff['name'])[0]) ?> — <strong>2 new orders</strong> are waiting.</div>';
    setTimeout(() => z.querySelector('.toast')?.remove(), 4000);
}, 800);
</script>
</body>
</html>
