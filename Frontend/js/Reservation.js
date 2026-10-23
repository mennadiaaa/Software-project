/* Savouré — customer reservation flow (front-end only)
   Depends on users.js (global `users`) being loaded first. */
(function () {
  'use strict';

  /* ---------- Config ---------- */
  var RESTAURANTS = [
    { name: 'The Revolving Restaurant', icon: 'fa-solid fa-rotate', seating: false,
      desc: 'Dine while the city turns slowly around you.' },
    { name: 'Le Petite Chef', icon: 'fa-solid fa-utensils', seating: false,
      desc: 'An intimate table with refined, chef-led plates.' },
    { name: 'Mo Bistro', icon: 'fa-solid fa-wine-glass', seating: true,
      desc: 'Relaxed bistro dining, indoors or out on the terrace.' }
  ];

  // 2-hour slots starting at 1 PM (24h start/end)
  var SLOTS = [
    { start: 13, end: 15 }, { start: 15, end: 17 }, { start: 17, end: 19 },
    { start: 19, end: 21 }, { start: 21, end: 23 }
  ];

  var MIN_GUESTS = 1, MAX_GUESTS = 12, MAX_DAYS_AHEAD = 90;
  var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                'August', 'September', 'October', 'November', 'December'];
  var STEP_COUNT = 4;

  /* ---------- State ---------- */
  var state = { step: 1, restaurant: null, date: null, slot: null, seating: null, guests: 2, notes: '' };
  var today = startOfDay(new Date());
  var view = { year: today.getFullYear(), month: today.getMonth() };
  var customer = null;

  /* ---------- Helpers ---------- */
  function $(id) { return document.getElementById(id); }
  function startOfDay(d) { return new Date(d.getFullYear(), d.getMonth(), d.getDate()); }
  function sameDay(a, b) { return a && b && a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate(); }
  function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }

  function hourLabel(h) {
    var suffix = h >= 12 ? 'PM' : 'AM';
    var h12 = h % 12 === 0 ? 12 : h % 12;
    return h12 + ':00 ' + suffix;
  }
  function slotLabel(s) { return hourLabel(s.start) + ' – ' + hourLabel(s.end); }
  function dateLabel(d) {
    return d.toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
  }

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

  /* ---------- Current customer (from users.js) ---------- */
  function findCustomer(key) {
    if (!key || typeof users === 'undefined') return null;
    key = String(key).trim();
    if (key.charAt(0) === '{') {            // stored as JSON object
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

  /* ---------- Step 1 : restaurants ---------- */
  function renderRestaurants() {
    var grid = $('res-restaurants');
    grid.innerHTML = RESTAURANTS.map(function (r, i) {
      return '<button type="button" class="res-card" data-i="' + i + '" aria-pressed="false">' +
        '<div class="res-cover"><i class="' + r.icon + '"></i><span class="res-tick"><i class="fa-solid fa-check"></i></span></div>' +
        '<div class="res-info"><div class="res-name">' + esc(r.name) + '</div>' +
        '<p class="res-desc">' + esc(r.desc) + '</p></div></button>';
    }).join('');

    grid.addEventListener('click', function (e) {
      var card = e.target.closest('.res-card');
      if (!card) return;
      var chosen = RESTAURANTS[+card.getAttribute('data-i')];
      if (state.restaurant !== chosen) state.seating = null;   // seating only applies per restaurant
      state.restaurant = chosen;
      Array.prototype.forEach.call(grid.children, function (c) {
        var on = c === card;
        c.classList.toggle('selected', on);
        c.setAttribute('aria-pressed', on);
      });
    });
  }

  /* ---------- Step 2 : calendar + slots ---------- */
  function renderCalendar() {
    var first = new Date(view.year, view.month, 1);
    var daysInMonth = new Date(view.year, view.month + 1, 0).getDate();
    var lastAllowed = new Date(today.getFullYear(), today.getMonth(), today.getDate() + MAX_DAYS_AHEAD);
    var html = '';

    $('cal-month').textContent = MONTHS[view.month] + ' ' + view.year;
    for (var b = 0; b < first.getDay(); b++) html += '<span class="cal-blank"></span>';

    for (var d = 1; d <= daysInMonth; d++) {
      var date = new Date(view.year, view.month, d);
      var disabled = date < today || date > lastAllowed;
      var cls = 'cal-day' + (sameDay(date, today) ? ' today' : '') + (sameDay(date, state.date) ? ' selected' : '');
      html += '<button type="button" class="' + cls + '" data-d="' + d + '"' + (disabled ? ' disabled' : '') +
              ' aria-label="' + esc(dateLabel(date)) + '">' + d + '</button>';
    }
    $('cal-grid').innerHTML = html;

    $('cal-prev').disabled = view.year === today.getFullYear() && view.month === today.getMonth();
    $('cal-next').disabled = new Date(view.year, view.month + 1, 1) > lastAllowed;
  }

  function renderSlots() {
    var list = $('slot-list');
    var hint = $('slot-hint');
    if (!state.date) { list.innerHTML = ''; hint.hidden = false; return; }
    hint.hidden = true;

    var now = new Date();
    var isToday = sameDay(state.date, today);
    if (state.slot && isToday && state.slot.start <= now.getHours()) state.slot = null;

    list.innerHTML = SLOTS.map(function (s, i) {
      var past = isToday && s.start <= now.getHours();
      var sel = state.slot === s;
      return '<button type="button" class="slot' + (sel ? ' selected' : '') + '" data-i="' + i + '"' + (past ? ' disabled' : '') + '>' +
             '<span>' + slotLabel(s) + '</span><small>' + (past ? 'Passed' : '2 hours') + '</small></button>';
    }).join('');
  }

  function bindCalendar() {
    $('cal-prev').addEventListener('click', function () {
      view.month--; if (view.month < 0) { view.month = 11; view.year--; }
      renderCalendar();
    });
    $('cal-next').addEventListener('click', function () {
      view.month++; if (view.month > 11) { view.month = 0; view.year++; }
      renderCalendar();
    });
    $('cal-grid').addEventListener('click', function (e) {
      var btn = e.target.closest('.cal-day');
      if (!btn || btn.disabled) return;
      state.date = new Date(view.year, view.month, +btn.getAttribute('data-d'));
      renderCalendar();
      renderSlots();
    });
    $('slot-list').addEventListener('click', function (e) {
      var btn = e.target.closest('.slot');
      if (!btn || btn.disabled) return;
      state.slot = SLOTS[+btn.getAttribute('data-i')];
      renderSlots();
    });
  }

  /* ---------- Step 3 : details ---------- */
  function renderGuests() {
    $('guest-count').textContent = state.guests;
    $('guest-label').textContent = state.guests === 1 ? 'guest' : 'guests';
    $('guest-minus').disabled = state.guests <= MIN_GUESTS;
    $('guest-plus').disabled = state.guests >= MAX_GUESTS;
  }

  function renderSeating() {
    var needs = !!(state.restaurant && state.restaurant.seating);
    $('seating-box').hidden = !needs;
    Array.prototype.forEach.call($('seat-options').children, function (c) {
      c.classList.toggle('selected', c.getAttribute('data-seat') === state.seating);
    });
  }

  function bindDetails() {
    $('guest-minus').addEventListener('click', function () { if (state.guests > MIN_GUESTS) { state.guests--; renderGuests(); } });
    $('guest-plus').addEventListener('click', function () { if (state.guests < MAX_GUESTS) { state.guests++; renderGuests(); } });

    $('seat-options').addEventListener('click', function (e) {
      var card = e.target.closest('.seat-card');
      if (!card) return;
      state.seating = card.getAttribute('data-seat');
      renderSeating();
    });

    $('res-notes').addEventListener('input', function (e) {
      state.notes = e.target.value;
      $('notes-count').textContent = e.target.value.length;
    });
  }

  /* ---------- Step 4 : review ---------- */
  function row(label, value, muted) {
    return '<div class="sum-row"><dt>' + label + '</dt><dd' + (muted ? ' class="muted"' : '') + '>' + esc(value) + '</dd></div>';
  }

  function renderReview() {
    var c = customer || {};
    var name = ((c.firstName || '') + ' ' + (c.lastName || '')).trim();
    var phone = c.phone || c.phoneNumber || c.mobile || '';

    $('sum-person').innerHTML =
      row('Name', name || 'Not available', !name) +
      row('Email', c.email || 'Not available', !c.email) +
      row('Phone', phone || 'Not provided', !phone);

    var html = row('Restaurant', state.restaurant.name) +
               row('Date', dateLabel(state.date)) +
               row('Time', slotLabel(state.slot));
    if (state.restaurant.seating) html += row('Seating', state.seating);
    html += row('Guests', state.guests) +
            row('Special requests', state.notes.trim() || 'None', !state.notes.trim());
    $('sum-reservation').innerHTML = html;
  }

  /* ---------- Thank you ---------- */
  function makeRef() {
    var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789', out = '';
    for (var i = 0; i < 6; i++) out += chars.charAt(Math.floor(Math.random() * chars.length));
    return 'RSV-' + out;
  }

 function saveLocally(ref) {
  try {
    var key = 'savoure_reservations';
    var all = JSON.parse(window.localStorage.getItem(key) || '[]');
    all.push({
      ref: ref,
      customerId: customer ? customer.id : null,
      restaurant: state.restaurant.name,
      date: dateLabel(state.date),
      time: slotLabel(state.slot),
      seating: state.restaurant.seating ? state.seating : null,
      guests: state.guests,
      notes: state.notes.trim(),
      createdAt: new Date().toISOString(),
      dateISO: state.date.getFullYear() + '-' +
               String(state.date.getMonth() + 1).padStart(2, '0') + '-' +
               String(state.date.getDate()).padStart(2, '0'),
      startHour: state.slot.start,
      endHour: state.slot.end
    });
    window.localStorage.setItem(key, JSON.stringify(all));
  } catch (e) { /* storage unavailable — demo still works */ }
}

  function confirmReservation() {
    var ref = makeRef();
    saveLocally(ref);
    $('thanks-name').textContent = customer ? customer.firstName : 'guest';
    $('thanks-rest').textContent = state.restaurant.name;
    $('thanks-when').textContent = dateLabel(state.date) + ', ' + slotLabel(state.slot);
    $('thanks-ref').textContent = ref;

    showPanel('done');
    $('res-stepper').hidden = true;
    $('res-nav').hidden = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function resetFlow() {
    state = { step: 1, restaurant: null, date: null, slot: null, seating: null, guests: 2, notes: '' };
    view = { year: today.getFullYear(), month: today.getMonth() };
    $('res-notes').value = '';
    $('notes-count').textContent = '0';
    Array.prototype.forEach.call($('res-restaurants').children, function (c) {
      c.classList.remove('selected'); c.setAttribute('aria-pressed', 'false');
    });
    renderCalendar(); renderSlots(); renderGuests(); renderSeating();
    $('res-stepper').hidden = false;
    $('res-nav').hidden = false;
    goTo(1);
  }

  /* ---------- Wizard navigation ---------- */
  function showPanel(id) {
    for (var i = 1; i <= STEP_COUNT; i++) $('panel-' + i).hidden = true;
    $('panel-done').hidden = true;
    $('panel-' + id).hidden = false;
  }

  function goTo(step) {
    state.step = step;
    showPanel(step);

    Array.prototype.forEach.call(document.querySelectorAll('#res-stepper .step'), function (el) {
      var n = +el.getAttribute('data-step');
      el.classList.toggle('done', n < step);
      el.classList.toggle('active', n === step);
    });
    Array.prototype.forEach.call(document.querySelectorAll('#res-stepper .step-line'), function (el, i) {
      el.classList.toggle('done', i + 1 < step);
    });

    if (step === 2) { renderCalendar(); renderSlots(); }
    if (step === 3) { renderSeating(); renderGuests(); }
    if (step === 4) renderReview();

    $('btn-back').hidden = step === 1;
    $('btn-next').textContent = step === STEP_COUNT ? 'Confirm reservation' : 'Continue';
    $('btn-next').className = 'btn ' + (step === STEP_COUNT ? 'btn-gold' : 'btn-primary');
  }

  function validate(step) {
    if (step === 1 && !state.restaurant) return 'Please choose a restaurant.';
    if (step === 2) {
      if (!state.date) return 'Please select a date.';
      if (!state.slot) return 'Please select a time slot.';
    }
    if (step === 3 && state.restaurant.seating && !state.seating) return 'Please choose indoor or outdoor seating.';
    return '';
  }

  function bindNav() {
    $('btn-next').addEventListener('click', function () {
      var err = validate(state.step);
      if (err) { toast(err, 'error'); return; }
      if (state.step === STEP_COUNT) confirmReservation();
      else goTo(state.step + 1);
    });
    $('btn-back').addEventListener('click', function () { if (state.step > 1) goTo(state.step - 1); });
    $('btn-again').addEventListener('click', resetFlow);
  }

  /* ---------- Preselect restaurant from ?restaurant=... ---------- */
function preselectRestaurant() {
  var wanted = new URLSearchParams(window.location.search).get('restaurant');
  if (!wanted) return false;
  wanted = wanted.trim().toLowerCase();

  for (var i = 0; i < RESTAURANTS.length; i++) {
    if (RESTAURANTS[i].name.toLowerCase() === wanted) {
      state.restaurant = RESTAURANTS[i];
      var card = $('res-restaurants').children[i];
      card.classList.add('selected');
      card.setAttribute('aria-pressed', 'true');
      return true;
    }
  }
  return false;
}

  /* ---------- Init ---------- */
  /* ---------- Init ---------- */
document.addEventListener('DOMContentLoaded', function () {
  customer = resolveCustomer();
  renderRestaurants();
  bindCalendar();
  bindDetails();
  bindNav();
  renderGuests();
  goTo(preselectRestaurant() ? 2 : 1);
});
})();
