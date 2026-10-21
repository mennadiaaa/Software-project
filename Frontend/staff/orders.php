<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders · Souvré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .orders-grid { display: grid; grid-template-columns: 1fr 1.35fr; gap: 24px; align-items: start; }
        @media (max-width: 1100px) { .orders-grid { grid-template-columns: 1fr; } }

        .btn-preparing { background: #B5860D; color: #fff; }
        .btn-preparing:hover { background: #9A7210; transform: translateY(-1px); }
        .btn-ready     { background: #0D7377; color: #fff; }
        .btn-ready:hover { background: #0a5d61; transform: translateY(-1px); }

        .served-msg { display: flex; align-items: center; gap: 8px; color: #2D7A5F; font-size: .82rem; font-weight: 700; }
        .empty-panel { padding: 40px; text-align: center; color: var(--beige); }
        .empty-panel i { font-size: 1.8rem; opacity: .4; display: block; margin-bottom: 10px; }

        /* ---------- Pre-Order Panel (new) ---------- */
        .preorder-panel { grid-column: 1 / -1; margin-top: 8px; }
        .preorder-panel .card-head { background: linear-gradient(135deg, #A8843E, #C9A96E); }
        .preorder-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; padding: 20px; }
        @media (max-width: 700px) { .preorder-grid { grid-template-columns: 1fr; } }

        .preorder-card {
            background: #fff; border: 1px solid var(--cream); border-left: 4px solid var(--gold);
            border-radius: 12px; padding: 16px 18px;
            box-shadow: 0 2px 10px rgba(31,42,68,.06);
            display: flex; flex-direction: column; gap: 10px;
            transition: all var(--t);
        }
        .preorder-card:hover { box-shadow: 0 6px 20px rgba(31,42,68,.1); transform: translateY(-2px); }
        .preorder-card.dinein  { border-left-color: #4F46E5; }
        .preorder-card.pickup  { border-left-color: #2D7A5F; }

        .po-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
        .po-id { font-family: var(--serif); font-size: 1rem; font-weight: 700; color: var(--navy); }
        .po-type-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: .6rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
        }
        .po-type-badge.pickup { background: #EAF5EF; color: #2D7A5F; }
        .po-type-badge.dinein { background: #EEF2FF; color: #4F46E5; }

        .po-meta { font-size: .72rem; color: var(--charcoal); opacity: .7; display: flex; flex-wrap: wrap; gap: 10px; }
        .po-meta i { color: var(--beige); margin-right: 3px; }

        .po-items { font-size: .78rem; padding: 8px 0; border-top: 1px solid var(--cream); border-bottom: 1px solid var(--cream); }
        .po-item { display: flex; justify-content: space-between; padding: 3px 0; gap: 8px; }
        .po-item .qty { font-weight: 700; color: var(--navy); min-width: 22px; }
        .po-item .nm  { flex: 1; color: var(--charcoal); }
        .po-item .pr  { color: var(--navy); font-weight: 600; font-size: .76rem; white-space: nowrap; }

        .po-notes { font-size: .74rem; font-style: italic; color: var(--charcoal); opacity: .75; background: var(--ivory); padding: 8px 10px; border-radius: 6px; }

        .po-total { display: flex; justify-content: space-between; align-items: center; padding-top: 4px; }
        .po-total-lbl { font-size: .66rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--beige); }
        .po-total-val { font-family: var(--serif); font-size: 1.15rem; font-weight: 700; color: var(--navy); }

        .po-actions { display: flex; gap: 8px; margin-top: 4px; }
        .po-actions .btn { flex: 1; justify-content: center; }
    </style>
</head>
<body>

<div class="shell">
    <?php include '../../includes/staff_sidebar.php'; ?>

    <?php
    $all_orders = [
        'The Revolving Restaurant' => [
            'incoming' => [
                [
                    'id' => 1042,
                    'table' => 3,
                    'time' => '20:14',
                    'items' => [
                        ['qty' => 1, 'name' => 'Chicken Caesar Salad', 'price' => '600 EGP'],
                        ['qty' => 1, 'name' => 'Grilled Lobster', 'price' => '2500 EGP'],
                        ['qty' => 2, 'name' => 'Chocolate Fondant', 'price' => '800 EGP'],
                    ],
                    'total' => '3900 EGP',
                ],
                [
                    'id' => 1043,
                    'table' => 7,
                    'time' => '20:18',
                    'items' => [
                        ['qty' => 1, 'name' => 'Shrimp Bisque', 'price' => '700 EGP'],
                        ['qty' => 1, 'name' => 'Land & Sea', 'price' => '3600 EGP'],
                        ['qty' => 1, 'name' => 'Fine Apple Tart', 'price' => '375 EGP'],
                    ],
                    'total' => '4675 EGP',
                ],
            ],
            'active' => [
                [
                    'id' => 1038,
                    'table' => 5,
                    'time' => '19:52',
                    'step' => 1,
                    'status' => 'Preparing',
                    'items' => [
                        ['qty' => 1, 'name' => 'Baked Onion Soup', 'price' => '570 EGP'],
                        ['qty' => 2, 'name' => 'Rib Eye', 'price' => '3000 EGP'],
                        ['qty' => 2, 'name' => 'Creme Brulee', 'price' => '650 EGP'],
                    ],
                    'total' => '4220 EGP',
                ],
                [
                    'id' => 1040,
                    'table' => 11,
                    'time' => '19:30',
                    'step' => 2,
                    'status' => 'Ready',
                    'items' => [
                        ['qty' => 2, 'name' => 'Truffle Mashed Potatoes', 'price' => '900 EGP'],
                        ['qty' => 1, 'name' => 'Mushroom Risotto', 'price' => '500 EGP'],
                    ],
                    'total' => '1400 EGP',
                ],
            ],
        ],
        'Le Petite Chef' => [
            'incoming' => [
                [
                    'id' => 2081,
                    'table' => 2,
                    'time' => '20:22',
                    'items' => [
                        ['qty' => 1, 'name' => 'French Onion Soup', 'price' => '550 EGP'],
                        ['qty' => 1, 'name' => 'Pan-Seared Sea Bass', 'price' => '2100 EGP'],
                        ['qty' => 2, 'name' => 'Vanilla Crème Brûlée', 'price' => '700 EGP'],
                    ],
                    'total' => '3350 EGP',
                ],
                [
                    'id' => 2084,
                    'table' => 5,
                    'time' => '20:30',
                    'items' => [
                        ['qty' => 1, 'name' => 'Shrimp Cocktail', 'price' => '680 EGP'],
                        ['qty' => 1, 'name' => 'Wagyu Beef Tenderloin', 'price' => '3800 EGP'],
                        ['qty' => 1, 'name' => 'Chocolate Soufflé', 'price' => '600 EGP'],
                    ],
                    'total' => '5080 EGP',
                ],
            ],
            'active' => [
                [
                    'id' => 2077,
                    'table' => 4,
                    'time' => '19:48',
                    'step' => 1,
                    'status' => 'Preparing',
                    'items' => [
                        ['qty' => 1, 'name' => 'Wild Mushroom Cappuccino', 'price' => '580 EGP'],
                        ['qty' => 2, 'name' => 'Duck Confit', 'price' => '2600 EGP'],
                        ['qty' => 2, 'name' => 'Tarte Tatin', 'price' => '720 EGP'],
                    ],
                    'total' => '3900 EGP',
                ],
                [
                    'id' => 2079,
                    'table' => 8,
                    'time' => '19:35',
                    'step' => 2,
                    'status' => 'Ready',
                    'items' => [
                        ['qty' => 1, 'name' => 'Truffle Risotto', 'price' => '950 EGP'],
                        ['qty' => 1, 'name' => 'Grilled Salmon Fillet', 'price' => '1800 EGP'],
                    ],
                    'total' => '2750 EGP',
                ],
            ],
        ],
    ];

    $res_orders = $all_orders[$assigned_restaurant] ?? $all_orders['The Revolving Restaurant'];
    $incoming_orders = $res_orders['incoming'];
    $active_orders   = $res_orders['active'];
    ?>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="page-eyebrow"><?= htmlspecialchars($assigned_restaurant) ?> · Kitchen Pipeline</div>
                <h1 class="page-title">Order Management</h1>
            </div>
            <div class="topbar-right">
                <span class="badge badge-new"><span id="new-count"><?= count($incoming_orders) ?></span> New</span>
                <div class="topbar-divider"></div>
                <div class="live-clock" style="text-align:right;">
                    <span class="clock-time" id="clk"></span>
                    <span class="clock-date" id="clk-date"></span>
                </div>
            </div>
        </header>

        <main class="content">
            <div class="orders-grid">

                <div class="card">
                    <div class="card-head">
                        <span class="card-head-title">
                            <i class="fa-solid fa-circle-exclamation" style="color:var(--gold);margin-right:6px;"></i>Incoming
                        </span>
                        <span class="card-count" id="in-count"><?= count($incoming_orders) ?></span>
                    </div>
                    <div id="in-list">

                        <?php foreach ($incoming_orders as $ord): ?>
                        <div class="order-card" id="ord-<?= $ord['id'] ?>">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;">
                                <div class="order-id">#<?= $ord['id'] ?></div>
                                <span class="badge badge-new">New</span>
                            </div>
                            <div class="order-meta"><i class="fa-solid fa-table-cells-large" style="margin-right:4px;"></i>Table <?= $ord['table'] ?> &nbsp;·&nbsp; <i class="fa-regular fa-clock" style="margin-right:4px;"></i><?= $ord['time'] ?></div>
                            <?php foreach ($ord['items'] as $it): ?>
                            <div class="order-item"><span class="item-qty"><?= $it['qty'] ?></span><span><?= htmlspecialchars($it['name']) ?></span><span class="item-price"><?= $it['price'] ?></span></div>
                            <?php endforeach; ?>
                            <div class="order-total"><span class="total-label">Total</span><span class="total-val"><?= $ord['total'] ?></span></div>
                            <div class="order-foot">
                                <button class="btn btn-danger btn-sm" onclick="decline(<?= $ord['id'] ?>)"><i class="fa-solid fa-xmark"></i> Decline</button>
                                <button class="btn btn-gold" onclick="accept(<?= $ord['id'] ?>, <?= $ord['table'] ?>)"><i class="fa-solid fa-check"></i> Accept</button>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <div class="card">
                    <div class="card-head">
                        <span class="card-head-title">
                            <i class="fa-solid fa-fire-flame-curved" style="color:var(--gold);margin-right:6px;"></i>Active
                        </span>
                        <span class="card-count" id="act-count"><?= count($active_orders) ?></span>
                    </div>
                    <div id="act-list">

                        <?php foreach ($active_orders as $ord): ?>
                        <div class="order-card" id="act-<?= $ord['id'] ?>" data-step="<?= $ord['step'] ?>">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;">
                                <div class="order-id">#<?= $ord['id'] ?></div>
                                <span class="badge badge-<?= $ord['step'] === 1 ? 'preparing' : 'ready' ?>" id="bdg-<?= $ord['id'] ?>"><?= $ord['status'] ?></span>
                            </div>
                            <div class="order-meta"><i class="fa-solid fa-table-cells-large" style="margin-right:4px;"></i>Table <?= $ord['table'] ?> &nbsp;·&nbsp; Accepted <?= $ord['time'] ?></div>

                            <div class="stepper">
                                <div class="step done"><div class="step-dot"><i class="fa-solid fa-check" style="font-size:.6rem;"></i></div><span class="step-name">Accepted</span></div>
                                <div class="step-line done"></div>
                                <?php if ($ord['step'] === 1): ?>
                                <div class="step active"><div class="step-dot"><i class="fa-solid fa-fire" style="font-size:.6rem;"></i></div><span class="step-name">Preparing</span></div>
                                <div class="step-line" id="ln-<?= $ord['id'] ?>-2"></div>
                                <div class="step" id="stp-ready-<?= $ord['id'] ?>"><div class="step-dot">3</div><span class="step-name">Ready</span></div>
                                <div class="step-line" id="ln-<?= $ord['id'] ?>-3"></div>
                                <div class="step" id="stp-served-<?= $ord['id'] ?>"><div class="step-dot">4</div><span class="step-name">Served</span></div>
                                <?php else: ?>
                                <div class="step done"><div class="step-dot"><i class="fa-solid fa-check" style="font-size:.6rem;"></i></div><span class="step-name">Preparing</span></div>
                                <div class="step-line done" id="ln-<?= $ord['id'] ?>-2"></div>
                                <div class="step active" id="stp-ready-<?= $ord['id'] ?>"><div class="step-dot"><i class="fa-solid fa-bell" style="font-size:.6rem;"></i></div><span class="step-name">Ready</span></div>
                                <div class="step-line" id="ln-<?= $ord['id'] ?>-3"></div>
                                <div class="step" id="stp-served-<?= $ord['id'] ?>"><div class="step-dot">4</div><span class="step-name">Served</span></div>
                                <?php endif; ?>
                            </div>

                            <?php foreach ($ord['items'] as $it): ?>
                            <div class="order-item"><span class="item-qty"><?= $it['qty'] ?></span><span><?= htmlspecialchars($it['name']) ?></span><span class="item-price"><?= $it['price'] ?></span></div>
                            <?php endforeach; ?>
                            <div class="order-total"><span class="total-label">Total</span><span class="total-val"><?= $ord['total'] ?></span></div>
                            <div class="order-foot" id="ft-<?= $ord['id'] ?>" style="margin-top:14px;">
                                <span style="font-size:.7rem;color:var(--beige);">Next:</span>
                                <?php if ($ord['step'] === 1): ?>
                                <button class="btn btn-preparing btn-sm" onclick="advance(<?= $ord['id'] ?>)">
                                    <i class="fa-solid fa-arrow-right"></i> Mark Ready
                                </button>
                                <?php else: ?>
                                <button class="btn btn-ready btn-sm" onclick="advance(<?= $ord['id'] ?>)">
                                    <i class="fa-solid fa-concierge-bell"></i> Mark Served
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!-- ============ NEW: Customer Pre-Orders ============ -->
                <div class="preorder-panel">
                    <div class="card">
                        <div class="card-head">
                            <span class="card-head-title">
                                <i class="fa-solid fa-bag-shopping" style="color:var(--navy-dk);margin-right:6px;"></i>
                                Customer Pre-Orders
                            </span>
                            <span class="card-count" id="pre-count">0</span>
                        </div>
                        <div class="preorder-grid" id="preorder-list">
                            <!-- Rendered by staffPreorders.js -->
                        </div>
                    </div>
                </div>

            </div>
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

function accept(id, table) {
    const card = document.getElementById(`ord-${id}`);
    const items = card.querySelector('.order-item') ? Array.from(card.querySelectorAll('.order-item')).map(i => i.outerHTML).join('') : '';
    const total = card.querySelector('.order-total')?.outerHTML || '';
    const now   = new Date(), p = n => String(n).padStart(2,'0');
    const time  = `${p(now.getHours())}:${p(now.getMinutes())}`;

    const el = document.createElement('div');
    el.className = 'order-card';
    el.id = `act-${id}`;
    el.dataset.step = '1';
    el.innerHTML = `
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div class="order-id">#${id}</div>
            <span class="badge badge-preparing" id="bdg-${id}">Preparing</span>
        </div>
        <div class="order-meta"><i class="fa-solid fa-table-cells-large" style="margin-right:4px;"></i>Table ${table} &nbsp;·&nbsp; Accepted ${time}</div>
        <div class="stepper">
            <div class="step done"><div class="step-dot"><i class="fa-solid fa-check" style="font-size:.6rem;"></i></div><span class="step-name">Accepted</span></div>
            <div class="step-line done"></div>
            <div class="step active"><div class="step-dot"><i class="fa-solid fa-fire" style="font-size:.6rem;"></i></div><span class="step-name">Preparing</span></div>
            <div class="step-line" id="ln-${id}-2"></div>
            <div class="step" id="stp-ready-${id}"><div class="step-dot">3</div><span class="step-name">Ready</span></div>
            <div class="step-line" id="ln-${id}-3"></div>
            <div class="step" id="stp-served-${id}"><div class="step-dot">4</div><span class="step-name">Served</span></div>
        </div>
        ${items}${total}
        <div class="order-foot" id="ft-${id}" style="margin-top:14px;">
            <span style="font-size:.7rem;color:var(--beige);">Next:</span>
            <button class="btn btn-preparing btn-sm" onclick="advance(${id})">
                <i class="fa-solid fa-arrow-right"></i> Mark Ready
            </button>
        </div>`;

    document.getElementById('act-list').appendChild(el);
    removeCard(card, 'in-list', 'in-count', 'new-count');
    document.getElementById('act-count').textContent = document.querySelectorAll('#act-list .order-card').length;
    toast(`Order #${id} sent to kitchen.`, 'success');
}

function decline(id) {
    if (!confirm(`Decline order #${id}?`)) return;
    const card = document.getElementById(`ord-${id}`);
    removeCard(card, 'in-list', 'in-count', 'new-count');
    toast(`Order #${id} declined.`);
}

function advance(id) {
    const card = document.getElementById(`act-${id}`);
    const step = +card.dataset.step;

    if (step === 1) {
        document.getElementById(`bdg-${id}`).className = 'badge badge-ready';
        document.getElementById(`bdg-${id}`).textContent = 'Ready';
        const ready = document.getElementById(`stp-ready-${id}`);
        ready.classList.add('active');
        ready.querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-bell" style="font-size:.6rem;"></i>';
        document.getElementById(`ln-${id}-2`).classList.add('done');
        const ft = document.getElementById(`ft-${id}`);
        ft.innerHTML = `<span style="font-size:.7rem;color:var(--beige);">Next:</span>
            <button class="btn btn-ready btn-sm" onclick="advance(${id})">
                <i class="fa-solid fa-concierge-bell"></i> Mark Served
            </button>`;
        card.dataset.step = '2';
        toast(`Order #${id} is ready — please serve.`, 'success');

    } else if (step === 2) {
        document.getElementById(`bdg-${id}`).className = 'badge badge-served';
        document.getElementById(`bdg-${id}`).textContent = 'Served';
        const ready  = document.getElementById(`stp-ready-${id}`);
        const served = document.getElementById(`stp-served-${id}`);
        ready.classList.replace('active','done');
        ready.querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-check" style="font-size:.6rem;"></i>';
        served.classList.add('active');
        served.querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-star" style="font-size:.6rem;"></i>';
        document.getElementById(`ln-${id}-3`).classList.add('done');
        document.getElementById(`ft-${id}`).innerHTML = `
            <span class="served-msg"><i class="fa-solid fa-circle-check fa-lg"></i> Served — complete</span>
            <button class="btn btn-ghost btn-sm" onclick="archiveCard(${id})"><i class="fa-solid fa-archive"></i> Archive</button>`;
        card.dataset.step = '3';
        toast(`Order #${id} served. Enjoy your meal!`, 'success');
    }
}

function archiveCard(id) {
    const card = document.getElementById(`act-${id}`);
    if (card) { card.style.opacity='0'; card.style.transition='opacity .3s'; setTimeout(() => { card.remove(); refreshActCount(); }, 300); }
}

function removeCard(card, listId, countId, badgeId) {
    card.style.transition = 'opacity .3s, transform .3s';
    card.style.opacity = '0'; card.style.transform = 'translateX(-16px)';
    setTimeout(() => {
        card.remove();
        const list = document.getElementById(listId);
        const remaining = list.querySelectorAll('.order-card').length;
        document.getElementById(countId).textContent = remaining;
        if (badgeId) document.getElementById(badgeId).textContent = remaining;
        if (remaining === 0) list.innerHTML = '<div class="empty-panel"><i class="fa-solid fa-check-double"></i><p style="font-family:var(--serif);font-style:italic;">All done</p></div>';
    }, 300);
}

function refreshActCount() {
    document.getElementById('act-count').textContent = document.querySelectorAll('#act-list .order-card').length;
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
<script src="../js/staffPreorders.js"></script>
</body>
</html>