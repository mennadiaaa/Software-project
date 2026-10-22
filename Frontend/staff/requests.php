<?php
// Resolves WHICH staff member is logged in (accepts nour / staff_nour / staff_03 / ?restaurant=...)
// and exposes $staff_id, $current_staff, $assigned_restaurant, $staff_param, $SAVOURE_DATA, $pending_orders_count, $open_requests_count
require_once __DIR__ . '/../../includes/staff_context.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests · Savouré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .tabs { display: flex; background: #fff; border: 1px solid var(--cream); border-radius: 12px; padding: 4px; width: fit-content; margin-bottom: 24px; gap: 4px; }
        .tab { padding: 7px 20px; border-radius: 9px; font-size: .76rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; cursor: pointer; transition: all var(--t); color: var(--charcoal); border: none; background: none; display: flex; align-items: center; gap: 7px; }
        .tab.active { background: var(--navy); color: var(--ivory); }
        .tab-badge { background: var(--gold); color: var(--navy-dk); font-size: .6rem; font-weight: 700; padding: 1px 6px; border-radius: 10px; }
        .tab.active .tab-badge { background: rgba(201,169,110,.35); color: rgba(214,201,182,.9); }
        .reqs { max-width: 760px; }
    </style>
</head>
<body>

<div class="shell">
    <?php include __DIR__ . '/../../includes/staff_sidebar.php'; ?>

    <?php
    $all_requests = $SAVOURE_DATA['requests'];

    $req_list = $all_requests[$assigned_restaurant] ?? $all_requests['The Revolving Restaurant'];
    $pending_count  = count(array_filter($req_list, fn($r) => $r['status'] === 'pending'));
    $answered_count = count(array_filter($req_list, fn($r) => $r['status'] === 'answered'));
    $total_count    = count($req_list);
    ?>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="page-eyebrow"><?= htmlspecialchars($assigned_restaurant) ?> · Guest Relations</div>
                <h1 class="page-title">Special Requests</h1>
            </div>
            <div class="topbar-right">
                <span class="badge badge-pending"><span id="pending-count"><?= $pending_count ?></span> Pending</span>
                <div class="topbar-divider"></div>
                <div class="live-clock" style="text-align:right;">
                    <span class="clock-time" id="clk"></span>
                    <span class="clock-date" id="clk-date"></span>
                </div>
            </div>
        </header>

        <main class="content">
            <div class="tabs">
                <button class="tab active" data-tab="all">
                    <i class="fa-solid fa-inbox"></i> All <span class="tab-badge" id="tb-all"><?= $total_count ?></span>
                </button>
                <button class="tab" data-tab="pending">
                    <i class="fa-solid fa-hourglass-half"></i> Pending <span class="tab-badge" id="tb-pending"><?= $pending_count ?></span>
                </button>
                <button class="tab" data-tab="answered">
                    <i class="fa-solid fa-check-double"></i> Answered <span class="tab-badge" id="tb-answered"><?= $answered_count ?></span>
                </button>
            </div>

            <div class="reqs" id="reqs-list">

                <?php foreach ($req_list as $req): ?>
                <div class="req-card <?= $req['status'] === 'answered' ? 'req-answered' : '' ?>" id="req-<?= $req['id'] ?>" data-status="<?= $req['status'] ?>" <?= $req['border_color'] ? 'style="border-left-color:' . $req['border_color'] . ';"' : '' ?>>
                    <div class="req-head">
                        <div>
                            <div class="req-table"><i class="fa-solid fa-table-cells-large" style="color:var(--beige);margin-right:5px;font-size:.8rem;"></i>Table <?= $req['table'] ?></div>
                            <div class="req-guest"><i class="fa-solid fa-user" style="font-size:.6rem;margin-right:3px;"></i><?= htmlspecialchars($req['guest']) ?></div>
                        </div>
                        <span class="badge badge-<?= $req['status'] ?>" id="bdg-<?= $req['id'] ?>"><?= ucfirst($req['status']) ?></span>
                    </div>
                    <div class="req-text"><?= htmlspecialchars($req['text']) ?></div>
                    <div class="req-time"><i class="fa-regular fa-clock" style="margin-right:4px;"></i><?= htmlspecialchars($req['time']) ?></div>
                    <?php if ($req['tag']): ?>
                    <div style="margin-bottom:12px;">
                        <span class="badge" style="background:<?= $req['tag']['bg'] ?>;color:<?= $req['tag']['color'] ?>;"><i class="fa-solid <?= $req['tag']['icon'] ?>" style="margin-right:4px;"></i><?= $req['tag']['name'] ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($req['status'] === 'pending'): ?>
                    <div class="req-actions" id="act-<?= $req['id'] ?>">
                        <button class="btn btn-ghost btn-sm" onclick="toggleReply(<?= $req['id'] ?>)">
                            <i class="fa-solid fa-reply"></i> Reply
                        </button>
                        <button class="btn btn-success btn-sm" onclick="markAnswered(<?= $req['id'] ?>)">
                            <i class="fa-solid fa-check"></i> Mark Answered
                        </button>
                    </div>
                    <div class="reply-area" id="rpl-<?= $req['id'] ?>">
                        <textarea id="rpl-text-<?= $req['id'] ?>" placeholder="Type your reply to the guest…"></textarea>
                        <div class="reply-actions">
                            <button class="btn btn-ghost btn-sm" onclick="toggleReply(<?= $req['id'] ?>)">Cancel</button>
                            <button class="btn btn-primary btn-sm" onclick="sendReply(<?= $req['id'] ?>)">
                                <i class="fa-solid fa-paper-plane"></i> Send & Confirm
                            </button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="replied-note">
                        <strong><i class="fa-solid fa-reply" style="margin-right:4px;"></i>Staff Reply</strong>
                        <?= htmlspecialchars($req['reply']) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

            </div>
        </main>
    </div>
</div>

<div class="toast-zone" id="toasts"></div>
<script>
    window.SAVOURE_STAFF_ID = <?= json_encode($staff_id) ?>;
    window.SAVOURE_STAFF_RESTAURANT = <?= json_encode($assigned_restaurant) ?>;
</script>
<script>
(function tick() {
    const d = new Date(), p = n => String(n).padStart(2,'0');
    document.getElementById('clk').textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    document.getElementById('clk-date').textContent = d.toLocaleDateString('en-EG', { weekday:'short', month:'short', day:'numeric' });
    setTimeout(tick, 1000);
})();

document.querySelectorAll('.tab').forEach(t => {
    t.addEventListener('click', function() {
        document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.tab;
        document.querySelectorAll('.req-card').forEach(c => {
            c.style.display = filter === 'all' || c.dataset.status === filter ? '' : 'none';
        });
    });
});

function toggleReply(id) {
    const area = document.getElementById(`rpl-${id}`);
    area.classList.toggle('open');
}

function sendReply(id) {
    const text = document.getElementById(`rpl-text-${id}`)?.value.trim();
    if (!text) return toast('Please write a reply first.', 'error');
    markAnswered(id, text);
}

function markAnswered(id, reply) {
    const card = document.getElementById(`req-${id}`);
    card.dataset.status = 'answered';
    card.classList.add('req-answered');
    card.style.borderLeftColor = '#2D7A5F';

    document.getElementById(`bdg-${id}`).className = 'badge badge-answered';
    document.getElementById(`bdg-${id}`).textContent = 'Answered';

    const now = new Date(), p = n => String(n).padStart(2,'0');
    const time = `${p(now.getHours())}:${p(now.getMinutes())}`;
    const note = reply || 'Request acknowledged.';

    const noteEl = document.createElement('div');
    noteEl.className = 'replied-note';
    noteEl.innerHTML = `<strong><i class="fa-solid fa-reply" style="margin-right:4px;"></i>Staff Reply · ${time}</strong>${note}`;

    const actEl = document.getElementById(`act-${id}`);
    const rplEl = document.getElementById(`rpl-${id}`);
    if (actEl) actEl.replaceWith(noteEl);
    if (rplEl) rplEl.remove();

    updateCounts();
    toast('Request marked as answered.', 'success');
}

function updateCounts() {
    const pending  = document.querySelectorAll('.req-card[data-status="pending"]').length;
    const answered = document.querySelectorAll('.req-card[data-status="answered"]').length;
    document.getElementById('pending-count').textContent = pending;
    document.getElementById('tb-all').textContent     = pending + answered;
    document.getElementById('tb-pending').textContent  = pending;
    document.getElementById('tb-answered').textContent = answered;
    const badge = document.getElementById('req-badge');
    if (badge) badge.textContent = pending;
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
