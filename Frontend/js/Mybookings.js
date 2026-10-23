/* Savouré — My Bookings (front-end only)
   Reads reservations saved by reservation.js under localStorage 'savoure_reservations'.
   Depends on users.js (global `users`) being loaded first. */
(function () {
  'use strict';

  var STORE_KEY = 'savoure_reservations';
  var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                'August', 'September', 'October', 'November', 'December'];
  var ICONS = {
    'The Revolving Restaurant': 'fa-solid fa-rotate',
    'Le Petite Chef': 'fa-solid fa-utensils',
    'Mo Bistro': 'fa-solid fa-wine-glass'
  };

  var customer = null;
  var filter = 'all';
  var pendingRef = null;

  /* ---------- Helpers ---------- */
  function $(id) { return document.getElementById(id); }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }

  function readStore(key) {
    try { return window.localStorage.getItem(key); } catch (e) { return null; }
  }

  function toast(msg, type) {
    var zone = $('toast-zone');
    var t = document.createElement('div');
    t.className = 'toast ' + (type || '');
    t.textContent = msg;
    zone.appendChild(t);
    setTimeout(function () {
      t.classList.add('fade-out');
      setTimeout(function () { t.remove(); }, 300);
    }, 3200);
  }

  /* ---------- Current customer (same logic as reservation.js) ---------- */
  function findCustomer(key) {
    if (!key || typeof users === 'undefined') return null;
    key = String(key).trim();
    if (key.charAt(0) === '{') {
      try { var o = JSON.parse(key); key = o.id || o.email || ''; } catch (e) { return null; }
    }
    key = key.replace(/^"|"$/g, '').toLowerCase();
    var list = users.customers || [];
    for (var i = 0; i < list.length; i++) {
      var c = list[i], id = c.id.toLowerCase();
      if (id === key || id === 'cust_' + key || c.email.toLowerCase() === key ||
          c.firstName.toLowerCase() === key) return c;
    }
    return null;
  }

  function resolveCustomer() {
    var params = new URLSearchParams(window.location.search);
    var tries = [
      params.get('customer'), window.SAVOURE_CUSTOMER,
      readStore('savoure_customer'), readStore('savoure_user'), readStore('currentUser'), readStore('loggedInUser')
    ];
    for (var i = 0; i < tries.length; i++) {
      var c = findCustomer(tries[i]);
      if (c) return c;
    }
    return (typeof users !== 'undefined' && users.customers && users.customers[0]) || null; // dev fallback
  }

  /* ---------- Storage ---------- */
  function loadAll() {
    try {
      var all = JSON.parse(window.localStorage.getItem(STORE_KEY) || '[]');
      return Array.isArray(all) ? all : [];
    } catch (e) { return []; }
  }

  function saveAll(all) {
    try { window.localStorage.setItem(STORE_KEY, JSON.stringify(all)); return true; }
    catch (e) { return false; }
  }

  /* ---------- Parsing saved values ----------
     reservation.js saves date/time as display text ("Friday, 23 October 2026",
     "1:00 PM – 3:00 PM"), so we parse them back. If dateISO / startHour / endHour
     are present (see the optional tweak), those are used instead. */
  function to24(h, ap) {
    h = +h % 12;
    return ap.toUpperCase() === 'PM' ? h + 12 : h;
  }

  function parseBooking(b) {
    var y, m, d, start, end, hit;

    if (b.dateISO && (hit = /^(\d{4})-(\d{2})-(\d{2})$/.exec(b.dateISO))) {
      y = +hit[1]; m = +hit[2] - 1; d = +hit[3];
    } else if ((hit = /(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/.exec(b.date || ''))) {
      d = +hit[1]; y = +hit[3];
      m = MONTHS.indexOf(hit[2].charAt(0).toUpperCase() + hit[2].slice(1).toLowerCase());
    }
    if (m === undefined || m < 0) return null;

    if (typeof b.startHour === 'number') {
      start = b.startHour;
      end = typeof b.endHour === 'number' ? b.endHour : start + 2;
    } else {
      var times = (b.time || '').match(/(\d{1,2}):\d{2}\s*(AM|PM)/gi) || [];
      var s = /(\d{1,2}):\d{2}\s*(AM|PM)/i.exec(times[0] || '');
      var e = /(\d{1,2}):\d{2}\s*(AM|PM)/i.exec(times[1] || '');
      start = s ? to24(s[1], s[2]) : 0;
      end = e ? to24(e[1], e[2]) : start + 2;
    }

    return {
      day: d, month: m, year: y,
      startsAt: new Date(y, m, d, start, 0),
      endsAt: new Date(y, m, d, end, 0)
    };
  }

  function statusOf(b, parsed) {
    if (b.status === 'cancelled') return 'cancelled';
    if (parsed && parsed.endsAt <= new Date()) return 'past';
    return 'upcoming';
  }

  /* ---------- Data for the current customer ---------- */
  function myBookings() {
    var out = [];
    loadAll().forEach(function (b) {
      if (customer && b.customerId && b.customerId !== customer.id) return;
      var parsed = parseBooking(b);
      out.push({ raw: b, parsed: parsed, status: statusOf(b, parsed) });
    });

    // Upcoming first (soonest first), then past/cancelled (most recent first)
    out.sort(function (a, b) {
      var aUp = a.status === 'upcoming', bUp = b.status === 'upcoming';
      if (aUp !== bUp) return aUp ? -1 : 1;
      var at = a.parsed ? a.parsed.startsAt.getTime() : 0;
      var bt = b.parsed ? b.parsed.startsAt.getTime() : 0;
      return aUp ? at - bt : bt - at;
    });
    return out;
  }

  /* ---------- Render ---------- */
  var BADGES = {
    upcoming:  { cls: 'badge-confirmed', label: 'Upcoming' },
    past:      { cls: 'badge-served',    label: 'Completed' },
    cancelled: { cls: 'badge-cancelled', label: 'Cancelled' }
  };

  function renderStats(list) {
    var n = { upcoming: 0, past: 0, cancelled: 0 };
    list.forEach(function (x) { n[x.status]++; });

    function card(cls, icon, iconCls, value, label) {
      return '<div class="stat-card' + cls + '"><div class="stat-top"><div class="stat-icon ' + iconCls + '">' +
        '<i class="' + icon + '"></i></div></div><div class="stat-value">' + value + '</div>' +
        '<div class="stat-label">' + label + '</div></div>';
    }
    $('bk-stats').innerHTML =
      card('', 'fa-solid fa-book-open', 'cream', list.length, 'Total bookings') +
      card(' dark', 'fa-solid fa-calendar-check', 'navy', n.upcoming, 'Upcoming') +
      card('', 'fa-solid fa-circle-check', 'gold', n.past, 'Completed') +
      card('', 'fa-solid fa-ban', 'cream', n.cancelled, 'Cancelled');
  }

  function cardHTML(x) {
    var b = x.raw, p = x.parsed, badge = BADGES[x.status];
    var icon = ICONS[b.restaurant] || 'fa-solid fa-utensils';
    var guests = b.guests + (b.guests === 1 ? ' guest' : ' guests');

    var dateBlock = p
      ? '<span class="bk-day">' + p.day + '</span><span class="bk-mon">' + MONTHS[p.month].slice(0, 3) +
        '</span><span class="bk-year">' + p.year + '</span>'
      : '<span class="bk-mon">Date unavailable</span>';

    var facts = '<li><i class="fa-regular fa-calendar"></i>' + esc(b.date || 'Date unavailable') + '</li>' +
                '<li><i class="fa-regular fa-clock"></i>' + esc(b.time || 'Time unavailable') + '</li>' +
                '<li><i class="fa-solid fa-user-group"></i>' + esc(guests) + '</li>';
    if (b.seating) {
      facts += '<li><i class="fa-solid ' + (b.seating === 'Outdoor' ? 'fa-tree' : 'fa-couch') + '"></i>' + esc(b.seating) + '</li>';
    }

    var actions = '';
    if (x.status === 'upcoming') {
      actions = '<button type="button" class="btn btn-danger btn-sm" data-cancel="' + esc(b.ref) + '">' +
                '<i class="fa-solid fa-xmark"></i> Cancel</button>';
    } else {
      actions = '<a href="Reservation.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-rotate-right"></i> Book again</a>';
    }

    return '<article class="bk-card is-' + x.status + '">' +
      '<div class="bk-date">' + dateBlock + '</div>' +
      '<div class="bk-main">' +
        '<div class="bk-top"><h3 class="bk-name">' + esc(b.restaurant) + '</h3>' +
        '<span class="badge ' + badge.cls + '"><i class="fa-solid fa-circle"></i>' + badge.label + '</span></div>' +
        '<ul class="bk-facts">' + facts + '</ul>' +
        (b.notes ? '<p class="bk-notes">' + esc(b.notes) + '</p>' : '') +
      '</div>' +
      '<div class="bk-side">' +
        '<div><span class="bk-ref-label">Reference</span><span class="bk-ref">' + esc(b.ref) + '</span></div>' +
        '<div class="bk-actions">' + actions + '</div>' +
      '</div></article>';
  }

  function emptyHTML(total) {
    var msg = total === 0
      ? { t: 'No bookings yet', p: 'Reserve a table and it will show up here.' }
      : { t: 'Nothing in this view', p: 'Try a different filter to see your other bookings.' };
    return '<div class="bk-empty"><div class="bk-empty-icon"><i class="fa-regular fa-calendar"></i></div>' +
      '<h3>' + msg.t + '</h3><p>' + msg.p + '</p>' +
      (total === 0 ? '<a href="Reservation.php" class="btn btn-gold">Make a reservation</a>' : '') + '</div>';
  }

  function render() {
    var all = myBookings();
    var shown = all.filter(function (x) { return filter === 'all' || x.status === filter; });
    renderStats(all);
    $('bk-list').innerHTML = shown.length ? shown.map(cardHTML).join('') : emptyHTML(all.length);
  }

  /* ---------- Cancel flow ---------- */
  function openCancel(ref) {
    var hit = myBookings().filter(function (x) { return x.raw.ref === ref; })[0];
    if (!hit) return;
    pendingRef = ref;
    $('cancel-text').innerHTML = 'Your booking at <strong>' + esc(hit.raw.restaurant) + '</strong> on <strong>' +
      esc(hit.raw.date) + '</strong>, ' + esc(hit.raw.time) + ' (' + esc(ref) + ') will be cancelled.';
    $('cancel-modal').classList.add('open');
    $('cancel-keep').focus();
  }

  function closeCancel() {
    pendingRef = null;
    $('cancel-modal').classList.remove('open');
  }

  function confirmCancel() {
    if (!pendingRef) return;
    var all = loadAll(), done = false;
    all.forEach(function (b) {
      if (b.ref === pendingRef) { b.status = 'cancelled'; b.cancelledAt = new Date().toISOString(); done = true; }
    });
    closeCancel();
    if (done && saveAll(all)) { toast('Reservation cancelled.', 'success'); render(); }
    else toast('Could not cancel. Please try again.', 'error');
  }

  /* ---------- Events ---------- */
  function bind() {
    $('bk-filters').addEventListener('click', function (e) {
      var btn = e.target.closest('.filter-btn');
      if (!btn) return;
      filter = btn.getAttribute('data-filter');
      Array.prototype.forEach.call($('bk-filters').children, function (c) { c.classList.toggle('active', c === btn); });
      render();
    });

    $('bk-list').addEventListener('click', function (e) {
      var btn = e.target.closest('[data-cancel]');
      if (btn) openCancel(btn.getAttribute('data-cancel'));
    });

    $('cancel-close').addEventListener('click', closeCancel);
    $('cancel-keep').addEventListener('click', closeCancel);
    $('cancel-confirm').addEventListener('click', confirmCancel);
    $('cancel-modal').addEventListener('click', function (e) { if (e.target === this) closeCancel(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeCancel(); });

    // Keep the page in sync if a booking is made or cancelled in another tab
    window.addEventListener('storage', function (e) { if (e.key === STORE_KEY) render(); });
  }

  /* ---------- Init ---------- */
  document.addEventListener('DOMContentLoaded', function () {
    customer = resolveCustomer();
    bind();
    render();
  });
})();