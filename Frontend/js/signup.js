const SIGNUPS_KEY = 'savoure_signups';

function loadSignups() {
  try { return JSON.parse(localStorage.getItem(SIGNUPS_KEY)) || []; } catch (e) { return []; }
}

document.getElementById('signup-form').addEventListener('submit', (e) => {
  e.preventDefault();
  const err = document.getElementById('signup-error');
  const firstName = document.getElementById('firstName').value.trim();
  const lastName = document.getElementById('lastName').value.trim();
  const email = document.getElementById('email').value.trim().toLowerCase();
  const password = document.getElementById('password').value;
  const confirm = document.getElementById('confirm').value;

  if (password !== confirm) { err.textContent = 'Passwords do not match.'; return; }

  const signups = loadSignups();
  const taken = [...users.staff, ...users.customers, ...users.admins, ...signups]
    .some(u => u.email.toLowerCase() === email);
  if (taken) { err.textContent = 'That email is already registered.'; return; }

  const id = 'cust_' + Date.now();
  signups.push({ id, firstName, lastName, email, password, role: 'customer' });
  localStorage.setItem(SIGNUPS_KEY, JSON.stringify(signups));
  localStorage.setItem('savoure_current_user', JSON.stringify({ id, role: 'customer' }));

  location.href = '../customer/dashboard.php?customer=' + encodeURIComponent(id.replace('cust_', ''));
});