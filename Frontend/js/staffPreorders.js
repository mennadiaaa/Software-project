/**
 * Savouré Staff — Customer Pre-Orders Panel
 * Reads localStorage.savoure_preorders, filtered to the current staff's restaurant.
 * Writes directly into #preorder-list on orders.php.
 *
 * Storage shape (produced by customerMenu.js):
 * { id, restaurant, customer, type: 'pickup'|'dinein', items:[...], total,
 *   pickupTime?, arrivalTime?, partySize?, notes?, status, createdAt }
 */

const PREORDERS_KEY = 'savoure_preorders';

// Injected by orders.php via data attribute — see initialization at the bottom
let PREORDER_RESTAURANT = null;

function getPreOrders() {
    try { return JSON.parse(localStorage.getItem(PREORDERS_KEY)) || []; }
    catch(e) { return []; }
}

function savePreOrders(list) {
    localStorage.setItem(PREORDERS_KEY, JSON.stringify(list));
}

function escapeH(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

function renderPreOrders() {
    const list = getPreOrders().filter(o =>
        o.restaurant === PREORDER_RESTAURANT && o.status === 'pending'
    ).sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));

    const container = document.getElementById('preorder-list');
    const countEl   = document.getElementById('pre-count');
    if (!container) return;

    if (countEl) countEl.textContent = list.length;

    if (list.length === 0) {
        container.innerHTML = `
            <div class="empty-panel" style="grid-column:1/-1;">
                <i class="fa-solid fa-bag-shopping"></i>
                <p style="font-family:var(--serif);font-style:italic;">No pending pre-orders right now.</p>
            </div>`;
        return;
    }

    container.innerHTML = '';
    list.forEach(o => {
        const isPickup = o.type === 'pickup';
        const timeInfo = isPickup
            ? `<i class="fa-regular fa-clock"></i>Pickup ${escapeH(o.pickupTime || '—')}`
            : `<i class="fa-regular fa-clock"></i>Arrive ${escapeH(o.arrivalTime || '—')} · ${escapeH(o.partySize || '?')} guests`;

        const itemsHtml = o.items.map(it => `
            <div class="po-item">
                <span class="qty">×${it.qty}</span>
                <span class="nm">${escapeH(it.name)}</span>
                <span class="pr">${(it.price * it.qty).toLocaleString()} EGP</span>
            </div>`).join('');

        const notesHtml = o.notes ? `<div class="po-notes"><i class="fa-solid fa-note-sticky" style="margin-right:4px;"></i>${escapeH(o.notes)}</div>` : '';

        const card = document.createElement('div');
        card.className = `preorder-card ${o.type}`;
        card.id = `po-${o.id}`;
        card.innerHTML = `
            <div class="po-head">
                <div class="po-id">${escapeH(o.id)}</div>
                <span class="po-type-badge ${o.type}">
                    <i class="fa-solid ${isPickup ? 'fa-bag-shopping' : 'fa-utensils'}"></i>
                    ${isPickup ? 'Pickup' : 'Dine-In'}
                </span>
            </div>
            <div class="po-meta">${timeInfo} &nbsp;·&nbsp; <i class="fa-regular fa-user"></i>${escapeH(o.customer || 'guest')}</div>
            <div class="po-items">${itemsHtml}</div>
            ${notesHtml}
            <div class="po-total">
                <span class="po-total-lbl">Total</span>
                <span class="po-total-val">${o.total.toLocaleString()} EGP</span>
            </div>
            <div class="po-actions">
                <button class="btn btn-danger btn-sm" onclick="declinePreOrder('${o.id}')">
                    <i class="fa-solid fa-xmark"></i> Decline
                </button>
                <button class="btn btn-gold btn-sm" onclick="acceptPreOrder('${o.id}')">
                    <i class="fa-solid fa-check"></i> Accept
                </button>
            </div>`;
        container.appendChild(card);
    });
}

/**
 * Accept a pre-order: mark status='accepted', remove from panel,
 * and push it into the "Active" column with a stepper card.
 */
function acceptPreOrder(id) {
    const list = getPreOrders();
    const order = list.find(o => o.id === id);
    if (!order) return;

    // Mark it accepted in storage (keeps history)
    order.status = 'accepted';
    order.acceptedAt = new Date().toISOString();
    savePreOrders(list);

    // Remove from pre-order panel
    const card = document.getElementById(`po-${id}`);
    if (card) card.remove();
    updatePreOrderCount();

    // Build an Active card reusing the existing order-card + stepper styles
    buildActiveCardFromPreOrder(order);

    // Bump Active count
    const actCountEl = document.getElementById('act-count');
    if (actCountEl) actCountEl.textContent = document.querySelectorAll('#act-list .order-card').length;

    // Toast
    const typeLabel = order.type === 'pickup' ? 'pickup' : 'dine-in';
    if (typeof toast === 'function') toast(`Pre-order ${id} accepted (${typeLabel}) — sent to kitchen.`, 'success');
}

function declinePreOrder(id) {
    if (!confirm(`Decline pre-order ${id}? The customer will not be notified in this demo.`)) return;

    const list = getPreOrders();
    const order = list.find(o => o.id === id);
    if (order) {
        order.status = 'declined';
        order.declinedAt = new Date().toISOString();
        savePreOrders(list);
    }

    const card = document.getElementById(`po-${id}`);
    if (card) {
        card.style.transition = 'opacity .3s, transform .3s';
        card.style.opacity = '0';
        card.style.transform = 'translateX(-16px)';
        setTimeout(() => { card.remove(); updatePreOrderCount(); }, 300);
    }

    if (typeof toast === 'function') toast(`Pre-order ${id} declined.`);
}

function updatePreOrderCount() {
    const remaining = document.querySelectorAll('#preorder-list .preorder-card').length;
    const countEl = document.getElementById('pre-count');
    if (countEl) countEl.textContent = remaining;

    if (remaining === 0) {
        const container = document.getElementById('preorder-list');
        if (container) {
            container.innerHTML = `
                <div class="empty-panel" style="grid-column:1/-1;">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <p style="font-family:var(--serif);font-style:italic;">No pending pre-orders right now.</p>
                </div>`;
        }
    }
}

/**
 * Create an Active card (with the same stepper used by regular orders).
 * Reuses existing CSS classes: .order-card, .stepper, .badge-preparing, etc.
 */
function buildActiveCardFromPreOrder(order) {
    const actList = document.getElementById('act-list');
    if (!actList) return;

    // Remove any "All done" empty-state placeholder
    const emptyPanel = actList.querySelector('.empty-panel');
    if (emptyPanel) emptyPanel.remove();

    const now = new Date();
    const acceptedTime = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

    const safeId = 'poact_' + order.id.replace(/[^a-zA-Z0-9]/g, '');
    const itemsHtml = order.items.map(it => `
        <div class="order-item">
            <span class="item-qty">${it.qty}</span>
            <span>${escapeH(it.name)}</span>
            <span class="item-price">${(it.price * it.qty).toLocaleString()} EGP</span>
        </div>`).join('');

    const locationText = order.type === 'pickup'
        ? `<i class="fa-solid fa-bag-shopping" style="margin-right:4px;"></i>Pickup ${escapeH(order.pickupTime || '')}`
        : `<i class="fa-solid fa-utensils" style="margin-right:4px;"></i>${escapeH(order.partySize || '?')} guests · Arrive ${escapeH(order.arrivalTime || '')}`;

    const el = document.createElement('div');
    el.className = 'order-card';
    el.id = safeId;
    el.dataset.step = '1';
    el.innerHTML = `
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div class="order-id">${escapeH(order.id)}</div>
            <span class="badge badge-preparing" id="bdg-${safeId}">Preparing</span>
        </div>
        <div class="order-meta">${locationText} &nbsp;·&nbsp; Accepted ${acceptedTime}</div>
        <div class="stepper">
            <div class="step done"><div class="step-dot"><i class="fa-solid fa-check" style="font-size:.6rem;"></i></div><span class="step-name">Accepted</span></div>
            <div class="step-line done"></div>
            <div class="step active"><div class="step-dot"><i class="fa-solid fa-fire" style="font-size:.6rem;"></i></div><span class="step-name">Preparing</span></div>
            <div class="step-line" id="ln-${safeId}-2"></div>
            <div class="step" id="stp-ready-${safeId}"><div class="step-dot">3</div><span class="step-name">Ready</span></div>
            <div class="step-line" id="ln-${safeId}-3"></div>
            <div class="step" id="stp-served-${safeId}"><div class="step-dot">4</div><span class="step-name">Served</span></div>
        </div>
        ${itemsHtml}
        <div class="order-total">
            <span class="total-label">Total</span>
            <span class="total-val">${order.total.toLocaleString()} EGP</span>
        </div>
        <div class="order-foot" id="ft-${safeId}" style="margin-top:14px;">
            <span style="font-size:.7rem;color:var(--beige);">Next:</span>
            <button class="btn btn-preparing btn-sm" onclick="advancePreOrder('${safeId}')">
                <i class="fa-solid fa-arrow-right"></i> Mark Ready
            </button>
        </div>`;

    actList.appendChild(el);
}

/**
 * Same stepper advance logic as regular orders, but self-contained
 * so it doesn't depend on the inline `advance()` function.
 */
function advancePreOrder(safeId) {
    const card = document.getElementById(safeId);
    if (!card) return;
    const step = +card.dataset.step;

    if (step === 1) {
        const b = document.getElementById(`bdg-${safeId}`);
        b.className = 'badge badge-ready';
        b.textContent = 'Ready';

        const ready = document.getElementById(`stp-ready-${safeId}`);
        ready.classList.add('active');
        ready.querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-bell" style="font-size:.6rem;"></i>';
        document.getElementById(`ln-${safeId}-2`).classList.add('done');

        document.getElementById(`ft-${safeId}`).innerHTML = `
            <span style="font-size:.7rem;color:var(--beige);">Next:</span>
            <button class="btn btn-ready btn-sm" onclick="advancePreOrder('${safeId}')">
                <i class="fa-solid fa-concierge-bell"></i> Mark Served
            </button>`;
        card.dataset.step = '2';
        if (typeof toast === 'function') toast('Pre-order ready — please serve.', 'success');

    } else if (step === 2) {
        const b = document.getElementById(`bdg-${safeId}`);
        b.className = 'badge badge-served';
        b.textContent = 'Served';

        const ready  = document.getElementById(`stp-ready-${safeId}`);
        const served = document.getElementById(`stp-served-${safeId}`);
        ready.classList.replace('active', 'done');
        ready.querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-check" style="font-size:.6rem;"></i>';
        served.classList.add('active');
        served.querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-star" style="font-size:.6rem;"></i>';
        document.getElementById(`ln-${safeId}-3`).classList.add('done');

        document.getElementById(`ft-${safeId}`).innerHTML = `
            <span class="served-msg"><i class="fa-solid fa-circle-check fa-lg"></i> Served — complete</span>
            <button class="btn btn-ghost btn-sm" onclick="archivePreOrder('${safeId}')">
                <i class="fa-solid fa-archive"></i> Archive
            </button>`;
        card.dataset.step = '3';
        if (typeof toast === 'function') toast('Pre-order served.', 'success');
    }
}

function archivePreOrder(safeId) {
    const card = document.getElementById(safeId);
    if (!card) return;
    card.style.opacity = '0';
    card.style.transition = 'opacity .3s';
    setTimeout(() => {
        card.remove();
        const actCountEl = document.getElementById('act-count');
        if (actCountEl) actCountEl.textContent = document.querySelectorAll('#act-list .order-card').length;
    }, 300);
}

// Keep the panel live if customer adds a pre-order in another tab
window.addEventListener('storage', (e) => {
    if (e.key === PREORDERS_KEY) renderPreOrders();
});

// Init — order.php sets this via a data attribute or inline script
document.addEventListener('DOMContentLoaded', () => {
    // Read the restaurant from the injected window var (see orders.php hook below)
    PREORDER_RESTAURANT = window.SAVOURE_STAFF_RESTAURANT || null;

    if (!PREORDER_RESTAURANT) {
        // Fallback: read the brand <div class="restaurant-name"> text
        const brandEl = document.querySelector('.sidebar .restaurant-name');
        if (brandEl) PREORDER_RESTAURANT = brandEl.textContent.trim();
    }

    if (PREORDER_RESTAURANT) {
        renderPreOrders();
    } else {
        console.warn('[staffPreorders] Could not determine assigned restaurant — panel disabled.');
    }
});