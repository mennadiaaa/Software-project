<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations · Souvré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .res-banner {
            display: flex; align-items: center; gap: 20px;
            background: linear-gradient(135deg, var(--navy), var(--navy-lt));
            border-radius: 14px; padding: 24px 28px;
            color: var(--ivory); margin-bottom: 28px;
            box-shadow: var(--shadow); position: relative; overflow: hidden;
        }
        .res-banner::after { content: '⚜'; position: absolute; right: 24px; top: 50%; transform: translateY(-50%); font-size: 5rem; opacity: .05; }
        .banner-icon { width: 52px; height: 52px; background: rgba(201,169,110,.18); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--gold); flex-shrink: 0; }
        .banner-title { font-family: var(--serif); font-size: 2rem; font-weight: 700; line-height: 1; }
        .banner-sub { font-size: .7rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: rgba(214,201,182,.6); margin-top: 3px; }
        .banner-stats { display: flex; gap: 28px; margin-left: auto; }
        .b-stat-val { display: block; font-family: var(--serif); font-size: 1.8rem; font-weight: 700; color: var(--gold); line-height: 1; }
        .b-stat-lbl { font-size: .6rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: rgba(214,201,182,.5); }

        .top-bar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }

        .search-wrap { position: relative; }
        .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--beige); font-size: .82rem; }
        .search-wrap input { padding: 8px 14px 8px 34px; border: 1.5px solid var(--beige); border-radius: 10px; font-family: var(--sans); font-size: .86rem; outline: none; width: 230px; transition: border-color var(--t); }
        .search-wrap input:focus { border-color: var(--navy); }

        .st-select { padding: 5px 12px; border: 1.5px solid var(--beige); border-radius: 20px; font-size: .72rem; font-weight: 700; letter-spacing: .07em; font-family: var(--sans); cursor: pointer; outline: none; background: #fff; transition: border-color var(--t); }
        .st-select:focus { border-color: var(--navy); }
    </style>
</head>
<body>

<div class="shell">
    <?php include '../../includes/staff_sidebar.php'; ?>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="page-eyebrow">Guest Scheduling</div>
                <h1 class="page-title">Reservations</h1>
            </div>
            <div class="topbar-right">
                <div class="live-clock" style="text-align:right;">
                    <span class="clock-time" id="clk"></span>
                    <span class="clock-date" id="clk-date"></span>
                </div>
            </div>
        </header>

        <main class="content">

            <?php
            // Mock reservations data per restaurant
            $all_reservations = [
                'The Revolving Restaurant' => [
                    ['R-2201','18:30','Hassan, Ahmed',   2,'T-02','+20 100 234 5678','',                      'confirmed'],
                    ['R-2202','19:00','El-Sayed, Fatima',2,'T-06','+20 100 987 6543','Garlic allergy noted',   'confirmed'],
                    ['R-2203','19:30','Mansour, Omar',   4,'T-10','+20 101 555 4433','Window seat preferred',  'confirmed'],
                    ['R-2204','20:00','Karim, Leila',    3,'T-04','+20 102 111 2233','Business dinner',        'pending'],
                    ['R-2205','20:30','Ibrahim, Khaled', 6,'T-07','+20 100 778 8900','Birthday celebration',   'confirmed'],
                    ['R-2206','21:00','Naguib, Sara',    2,'T-01','+20 101 447 5566','',                       'confirmed'],
                ],
                'Le Petite Chef' => [
                    ['R-3101','18:00','El-Masry, Mostafa', 2,'T-01','+20 109 456 7812','Anniversary dinner',       'confirmed'],
                    ['R-3102','18:45','Ghanem, Nourhan',   4,'T-03','+20 106 321 4455','Allergy: gluten-free',     'confirmed'],
                    ['R-3103','19:15','Farouk, Sherif',    2,'T-05','+20 101 887 5522','VIP guest',                'pending'],
                    ['R-3104','20:00','Khattab, Salma',    3,'T-08','+20 100 665 9933','Table near animation',     'confirmed'],
                    ['R-3105','20:30','El-Kady, Tamer',    5,'T-09','+20 102 774 1188','Family celebration',       'confirmed'],
                    ['R-3106','21:15','Radwan, Dina',      2,'T-02','+20 103 992 3344','Quiet corner requested',   'confirmed'],
                ],
            ];

            $data = $all_reservations[$assigned_restaurant] ?? $all_reservations['The Revolving Restaurant'];
            $confirmed_count = 0;
            $covers_count = 0;
            foreach ($data as $r) {
                if ($r[7] === 'confirmed') $confirmed_count++;
                $covers_count += $r[3];
            }
            ?>

            <!-- Banner -->
            <div class="res-banner">
                <div class="banner-icon"><i class="fa-solid fa-calendar-star"></i></div>
                <div>
                    <div class="banner-title">Tuesday, October 14</div>
                    <div class="banner-sub">Evening Service · <?= htmlspecialchars($assigned_restaurant) ?>, Cairo</div>
                </div>
                <div class="banner-stats">
                    <div><span class="b-stat-val"><?= count($data) ?></span><span class="b-stat-lbl">Bookings</span></div>
                    <div><span class="b-stat-val" style="color:#7FB5E8;"><?= $confirmed_count ?></span><span class="b-stat-lbl">Confirmed</span></div>
                    <div><span class="b-stat-val"><?= $covers_count ?></span><span class="b-stat-lbl">Covers</span></div>
                </div>
            </div>

            <!-- Filters + search -->
            <div class="top-bar">
                <div class="filters" style="margin-bottom:0;">
                    <button class="filter-btn active" data-f="all">All</button>
                    <button class="filter-btn" data-f="confirmed">Confirmed</button>
                    <button class="filter-btn" data-f="pending">Pending</button>
                    <button class="filter-btn" data-f="seated">Seated</button>
                    <button class="filter-btn" data-f="cancelled">Cancelled</button>
                </div>
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="search" placeholder="Search guest…" oninput="doSearch(this.value)">
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ref</th>
                            <th>Time</th>
                            <th>Guest</th>
                            <th>Party</th>
                            <th>Table</th>
                            <th>Phone</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php
                        $badges = ['confirmed'=>'badge-confirmed','pending'=>'badge-pending','seated'=>'badge-seated','cancelled'=>'badge-cancelled'];
                        foreach ($data as [$ref,$time,$guest,$party,$table,$phone,$note,$status]):
                        ?>
                        <tr id="row-<?= $ref ?>" data-status="<?= $status ?>" data-guest="<?= strtolower($guest) ?>">
                            <td class="td-muted" style="font-size:.72rem;"><?= $ref ?></td>
                            <td class="td-mono"><i class="fa-regular fa-clock" style="color:var(--beige);margin-right:4px;"></i><?= $time ?></td>
                            <td class="td-name"><?= $guest ?></td>
                            <td><i class="fa-solid fa-user-group" style="color:var(--beige);margin-right:5px;"></i><?= $party ?></td>
                            <td><strong style="color:var(--navy);"><?= $table ?></strong></td>
                            <td class="td-muted"><?= $phone ?></td>
                            <td>
                                <?php if ($note): ?>
                                <span style="font-size:.76rem;font-style:italic;color:var(--charcoal);opacity:.7;"><?= $note ?></span>
                                <?php else: ?><span class="td-muted">—</span><?php endif; ?>
                            </td>
                            <td><span class="badge <?= $badges[$status] ?>" id="bdg-<?= $ref ?>"><?= ucfirst($status) ?></span></td>
                            <td>
                                <select class="st-select" onchange="updateStatus('<?= $ref ?>', this.value)">
                                    <option value="confirmed" <?= $status==='confirmed'?'selected':'' ?>>Confirmed</option>
                                    <option value="pending"   <?= $status==='pending'  ?'selected':'' ?>>Pending</option>
                                    <option value="seated"    <?= $status==='seated'   ?'selected':'' ?>>Seated</option>
                                    <option value="cancelled" <?= $status==='cancelled'?'selected':'' ?>>Cancelled</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="divider" style="margin-top:32px;">⚜</div>
            <p style="text-align:center;font-family:var(--serif);font-size:.88rem;color:var(--beige);font-style:italic;">
                Evening service · <?= htmlspecialchars($assigned_restaurant) ?>, Cairo, Egypt
            </p>

        </main>
    </div>
</div>

<div class="toast-zone" id="toasts"></div>

<script>
(function tick() {
    const d = new Date(), p = n => String(n).padStart(2,'0');
    document.getElementById('clk').textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    document.getElementById('clk-date').textContent = d.toLocaleDateString('en-EG', { weekday:'short', month:'short', day:'numeric' });
    setTimeout(tick, 1000);
})();

// Filter buttons
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const f = this.dataset.f;
        document.querySelectorAll('#tbody tr').forEach(r => {
            r.style.display = f === 'all' || r.dataset.status === f ? '' : 'none';
        });
    });
});

function doSearch(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#tbody tr').forEach(r => {
        r.style.display = r.dataset.guest.includes(q) ? '' : 'none';
    });
}

const badgeMap = { confirmed:'badge-confirmed', pending:'badge-pending', seated:'badge-seated', cancelled:'badge-cancelled' };

function updateStatus(ref, status) {
    const badge = document.getElementById(`bdg-${ref}`);
    const row   = document.getElementById(`row-${ref}`);
    if (badge) { badge.className = `badge ${badgeMap[status]}`; badge.textContent = status.charAt(0).toUpperCase()+status.slice(1); }
    if (row)   { row.dataset.status = status; row.style.opacity = status === 'cancelled' ? '.45' : ''; }
    toast(`Reservation updated to ${status}.`, 'success');
}

function toast(msg, type='') {
    const z = document.getElementById('toasts');
    const t = document.createElement('div');
    t.className = `toast ${type}`;
    t.textContent = msg;
    z.appendChild(t);
    setTimeout(() => { t.classList.add('fade-out'); setTimeout(() => t.remove(), 300); }, 3800);
}
</script>
</body>
</html>
