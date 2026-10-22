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
    <title>Tables · Savouré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="shell">
    <?php include __DIR__ . '/../../includes/staff_sidebar.php'; ?>

    <?php
    $all_tables = $SAVOURE_DATA['tables'];
    $tables = $all_tables[$assigned_restaurant] ?? $all_tables['The Revolving Restaurant'];
    $cnt_free = count(array_filter($tables, fn($t) => $t[2] === 'free'));
    $cnt_reserved = count(array_filter($tables, fn($t) => $t[2] === 'reserved'));
    $cnt_occupied = count(array_filter($tables, fn($t) => $t[2] === 'occupied'));
    ?>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="page-eyebrow"><?= htmlspecialchars($assigned_restaurant) ?> · Floor Management</div>
                <h1 class="page-title">Manage Tables</h1>
            </div>
            <div class="topbar-right">
                <span class="badge badge-free"><span id="cnt-free"><?= $cnt_free ?></span> Free</span>
                <span class="badge badge-reserved"><span id="cnt-res"><?= $cnt_reserved ?></span> Reserved</span>
                <span class="badge badge-occupied"><span id="cnt-occ"><?= $cnt_occupied ?></span> Occupied</span>
                <div class="topbar-divider"></div>
                <div class="live-clock" style="text-align:right;">
                    <span class="clock-time" id="clk"></span>
                    <span class="clock-date" id="clk-date"></span>
                </div>
            </div>
        </header>

        <main class="content">
            <div class="filters">
                <button class="filter-btn active" data-f="all">All Tables</button>
                <button class="filter-btn" data-f="free">Free</button>
                <button class="filter-btn" data-f="reserved">Reserved</button>
                <button class="filter-btn" data-f="occupied">Occupied</button>
            </div>

            <div class="floor-grid" id="grid">
                <?php
                $icons = ['free'=>'check-circle','reserved'=>'clock','occupied'=>'users'];

                foreach ($tables as [$n, $seats, $st, $guest]):
                ?>
                <div class="table-card <?= $st ?>" id="tc-<?= $n ?>" data-status="<?= $st ?>" data-n="<?= $n ?>">
                    <div style="position:absolute;top:8px;right:10px;font-size:.72rem;">
                        <i class="fa-solid fa-<?= $icons[$st] ?>" style="color:<?= $st==='free'?'#2D7A5F':($st==='reserved'?'#A8843E':'#8B2635') ?>;"></i>
                    </div>
                    <div class="tc-num"><?= $n ?></div>
                    <div class="tc-seats"><i class="fa-solid fa-chair" style="margin-right:3px;"></i><?= $seats ?> seats</div>
                    <span class="badge badge-<?= $st ?>" style="margin:6px auto;"><?= ucfirst($st) ?></span>
                    <?php if ($guest): ?>
                    <div class="tc-guest"><i class="fa-solid fa-user" style="font-size:.6rem;margin-right:3px;"></i><?= $guest ?></div>
                    <?php endif; ?>
                    <div style="margin-top:12px;">
                        <button class="btn btn-ghost btn-sm" style="width:100%;"
                                onclick="openModal(<?= $n ?>, '<?= $st ?>', '<?= $guest ?>')">
                            <i class="fa-solid fa-pen"></i> Update
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>

<div class="modal-backdrop" id="modal">
    <div class="modal">
        <div class="modal-head">
            <h2 class="modal-title">Update Table <span id="m-num"></span></h2>
            <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Status</label>
                <div class="status-radios">
                    <input type="radio" name="st" id="r-free"     class="status-radio" value="free">
                    <label for="r-free"     class="status-radio-label"><i class="fa-solid fa-check-circle"></i> Free</label>
                    <input type="radio" name="st" id="r-reserved" class="status-radio" value="reserved">
                    <label for="r-reserved" class="status-radio-label"><i class="fa-solid fa-clock"></i> Reserved</label>
                    <input type="radio" name="st" id="r-occupied" class="status-radio" value="occupied">
                    <label for="r-occupied" class="status-radio-label"><i class="fa-solid fa-users"></i> Occupied</label>
                </div>
            </div>
            <div class="form-group" id="guest-field">
                <label class="form-label" for="m-guest">Guest Name</label>
                <input type="text" id="m-guest" class="form-control" placeholder="e.g. Hassan, Ahmed">
            </div>
            <div class="form-group">
                <label class="form-label" for="m-note">Note <span style="font-weight:400;text-transform:none;font-size:.78rem;color:var(--beige);">(optional)</span></label>
                <input type="text" id="m-note" class="form-control" placeholder="Allergies, special setup, etc.">
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveTable()"><i class="fa-solid fa-floppy-disk"></i> Save</button>
        </div>
    </div>
</div>

<div class="toast-zone" id="toasts"></div>
<script>
    window.SAVOURE_STAFF_ID = <?= json_encode($staff_id) ?>;
    window.SAVOURE_STAFF_RESTAURANT = <?= json_encode($assigned_restaurant) ?>;
</script>
<script>
let currentTable = null;

(function tick() {
    const d = new Date(), p = n => String(n).padStart(2,'0');
    document.getElementById('clk').textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    document.getElementById('clk-date').textContent = d.toLocaleDateString('en-EG', { weekday:'short', month:'short', day:'numeric' });
    setTimeout(tick, 1000);
})();

document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const f = this.dataset.f;
        document.querySelectorAll('.table-card').forEach(c => {
            c.style.display = (f === 'all' || c.dataset.status === f) ? '' : 'none';
        });
    });
});

function openModal(n, status, guest) {
    currentTable = n;
    document.getElementById('m-num').textContent = n;
    document.getElementById('m-guest').value = guest || '';
    document.getElementById('m-note').value = '';
    document.querySelector(`input[value="${status}"]`).checked = true;
    toggleGuest(status);
    document.getElementById('modal').classList.add('open');
}

function closeModal() {
    document.getElementById('modal').classList.remove('open');
}

function toggleGuest(st) {
    document.getElementById('guest-field').style.display = st === 'free' ? 'none' : '';
}

document.querySelectorAll('input[name="st"]').forEach(r => r.addEventListener('change', () => toggleGuest(r.value)));

document.getElementById('modal').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });

function saveTable() {
    const st    = document.querySelector('input[name="st"]:checked')?.value;
    const guest = document.getElementById('m-guest').value.trim();
    if (!st) return toast('Please select a status.', 'error');

    const card = document.getElementById(`tc-${currentTable}`);
    card.className = `table-card ${st}`;
    card.dataset.status = st;

    const icons = { free: 'check-circle', reserved: 'clock', occupied: 'users' };
    const colors = { free: '#2D7A5F', reserved: '#A8843E', occupied: '#8B2635' };

    card.querySelector('[style*="position:absolute"]').innerHTML =
        `<i class="fa-solid fa-${icons[st]}" style="color:${colors[st]};"></i>`;

    card.querySelector('.badge').className = `badge badge-${st}`;
    card.querySelector('.badge').textContent = st.charAt(0).toUpperCase() + st.slice(1);

    let guestEl = card.querySelector('.tc-guest');
    if (guest) {
        if (!guestEl) { guestEl = document.createElement('div'); guestEl.className = 'tc-guest'; card.querySelector('.badge').after(guestEl); }
        guestEl.innerHTML = `<i class="fa-solid fa-user" style="font-size:.6rem;margin-right:3px;"></i>${guest}`;
    } else if (guestEl) guestEl.remove();

    updateCounts();
    closeModal();
    toast(`Table ${currentTable} updated to ${st}.`, 'success');
}

function updateCounts() {
    const cards = document.querySelectorAll('.table-card');
    const c = { free:0, reserved:0, occupied:0 };
    cards.forEach(card => c[card.dataset.status]++);
    document.getElementById('cnt-free').textContent = c.free;
    document.getElementById('cnt-res').textContent  = c.reserved;
    document.getElementById('cnt-occ').textContent  = c.occupied;
}

function toast(msg, type='') {
    const z = document.getElementById('toasts');
    const t = document.createElement('div');
    t.className = `toast ${type}`;
    t.textContent = msg;
    z.appendChild(t);
    setTimeout(() => { t.classList.add('fade-out'); setTimeout(() => t.remove(), 300); }, 3500);
}
</script>
</body>
</html>