// Usage: <script>const REQUIRED_ROLE = 'admin';</script> then <script src="../js/guard.js"></script>
(function () {
  let user = null;
  try { user = JSON.parse(localStorage.getItem('savoure_current_user')); } catch (e) {}

  if (!user || user.role !== REQUIRED_ROLE) {
    location.replace('../auth/login.php');
  }
})();