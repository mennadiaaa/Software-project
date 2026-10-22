// "users" comes from includes/users.js ({ staff, customers, admins })
function getAllUsers() {
  const fromFile = [...users.staff, ...users.customers, ...users.admins];

  // also accept accounts saved in localStorage (e.g. future signups) that have a password
  let fromStorage = [];
  try {
    fromStorage = (JSON.parse(localStorage.getItem('savoure_users')) || []).filter(u => u.password);
  } catch (e) {}

  return [...fromFile, ...fromStorage];
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
const staffMap = { staff_nour: 'nour', staff_tarek: 'tarek', staff_02: 'pierre', staff_03: 'michael' };

if (user.role === 'admin') {
  location.href = '../admin/dashboard.php';
} else if (user.role === 'staff') {
  location.href = '../staff/dashboard.php?staff=' + encodeURIComponent(staffMap[user.id] || 'nour');
} else {
  location.href = '../customer/dashboard.php?customer=' + encodeURIComponent(user.id.replace('cust_', ''));
}
  
});