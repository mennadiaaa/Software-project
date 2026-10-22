(function () {
  let current = null, signups = [];
  try { current = JSON.parse(localStorage.getItem('savoure_current_user')); } catch (e) {}
  try { signups = JSON.parse(localStorage.getItem('savoure_signups')) || []; } catch (e) {}

  if (!current || current.role !== 'customer') return;
  const user = signups.find(u => u.id === current.id);
  if (!user) return; // built-in accounts (Sarah, Youssef) keep the PHP version

  const chip = document.querySelector('.customer-chip');
  if (!chip) return;

  const avatar = chip.querySelector('.avatar');
  const name = chip.querySelector('.staff-name');
  const role = chip.querySelector('.staff-role');
  const pts = chip.querySelector('.staff-branch');

  if (avatar) avatar.textContent = user.firstName.charAt(0).toUpperCase();
  if (name) name.textContent = user.firstName + ' ' + user.lastName;
  if (role) role.textContent = 'New Member';
  if (pts) pts.innerHTML = '<i class="fa-solid fa-gem"></i> 0 Pts';
})();