const SIGNUPS_KEY = 'savoure_signups';

// "users" comes from includes/users.js ({ staff, customers, admins })
function getAllUsers() {
  const fromFile = [...users.staff, ...users.customers, ...users.admins];

  let fromSignups = [];
  try {
    fromSignups = (JSON.parse(localStorage.getItem(SIGNUPS_KEY)) || []).filter(u => u.password);
  } catch (e) {}

  return [...fromFile, ...fromSignups];
}

document.getElementById('login-form').addEventListener('submit', (e) => {
  e.preventDefault();
  const email = document.getElementById('email').value.trim().toLowerCase();
  const password = document.getElementById('password').value;

  const user = getAllUsers().find(u => u.email.toLowerCase() === email && u.password === password);
  if (!user) {
    document.getElementById('login-error').textContent = 'Wrong email or password.';
    return;
  }

  localStorage.setItem('savoure_current_user', JSON.stringify({ id: user.id, role: user.role }));

  if (user.role === 'admin') {
    location.href = '../admin/dashboard.php';
  } else if (user.role === 'staff') {
    location.href = '../staff/dashboard.php?staff=' + encodeURIComponent(user.id);
  } else {
    location.href = '../customer/dashboard.php?customer=' + encodeURIComponent(user.id.replace('cust_', ''));
  }
});