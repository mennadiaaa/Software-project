<?php 
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

$customer_accounts = [
    'sarah' => [
        'id'         => 'sarah',
        'name'       => 'Sarah Hassan',
        'membership' => 'VIP Member',
        'avatar'     => 'S',
        'loyalty'    => '1,250 Pts',
    ],
    'youssef' => [
        'id'         => 'youssef',
        'name'       => 'Youssef Ali',
        'membership' => 'Regular Member',
        'avatar'     => 'Y',
        'loyalty'    => '450 Pts',
    ],
];

if (isset($_GET['customer']) && isset($customer_accounts[$_GET['customer']])) {
    $_SESSION['customer_account'] = $_GET['customer'];
}

$customer_id = $_GET['customer'] ?? $_SESSION['customer_account'] ?? $_COOKIE['customer_account'] ?? 'sarah';
if (!isset($customer_accounts[$customer_id])) {
    $customer_id = 'sarah';
}
$_SESSION['customer_account'] = $customer_id;

$current_customer = $customer_accounts[$customer_id];
$page = basename($_SERVER['PHP_SELF']); 
$category = $_GET['category'] ?? 'all';

$is_restaurant_active = in_array($page, ['allRestaurants.php', 'internationalRestaurants.php', 'frenchRestaurants.php', 'egyptianRestaurants.php']);
$customer_param = '?customer=' . urlencode($_GET['customer'] ?? $customer_id);
?>
<aside class="sidebar">
    <div class="brand">
        <div class="brand-mark">⚜</div>
         <div class="brand-name">Savouré</div>
        <div class="brand-sub">Guest Portal</div>
        
    </div>

    <nav>
        <div class="nav-label">Main Menu</div>

        <a href="dashboard.php<?= $customer_param ?>" class="nav-link <?= $page === 'dashboard.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-house"></i></span>
            Dashboard
        </a>


        <div class="nav-group <?= $is_restaurant_active ? 'open' : '' ?>">
            <button type="button" class="nav-link dropdown-toggle <?= $is_restaurant_active ? 'active' : '' ?>" onclick="toggleDropdown(this)">
                <span class="icon"><i class="fa-solid fa-utensils"></i></span>
                Restaurants
                <span class="chevron"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="nav-submenu">
                <a href="allRestaurants.php<?= $customer_param ?>" class="sub-link <?= ($page === 'allRestaurants.php') ? 'active' : '' ?>">
                    All Restaurants
                </a>
                <a href="internationalRestaurants.php<?= $customer_param ?>" class="sub-link <?= ($page === 'internationalRestaurants.php') ? 'active' : '' ?>">
                    International
                </a>
                <a href="frenchRestaurants.php<?= $customer_param ?>" class="sub-link <?= ($page === 'frenchRestaurants.php') ? 'active' : '' ?>">
                     🇫🇷 French
                </a>
                
                <a href="egyptianRestaurants.php<?= $customer_param ?>" class="sub-link <?= ($page === 'egyptianRestaurants.php') ? 'active' : '' ?>">
                    🇪🇬 Egyptian
                </a>
            </div>
        </div>

        <a href="Mybookings.php<?= $customer_param ?>" class="nav-link <?= $page === 'Mybookings.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-calendar-check"></i></span>
            My Bookings
        </a>

        <a href="Reservation.php<?= $customer_param ?>" class="nav-link <?= $page === 'Reservation.php' ? 'active' : '' ?>">
            <span class="icon"><i class="fa-solid fa-calendar-plus"></i></span>
            Make a Reservation
        </a>
    </nav>

    <div class="sidebar-foot">
        <a href="profile.php<?= $customer_param ?>" class="profile-chip-link <?= $page === 'profile.php' ? 'active' : '' ?>">
        <div class="staff-chip customer-chip">
            <div class="avatar"><?= htmlspecialchars($current_customer['avatar']) ?></div>
            <div>
                <span class="staff-name"><?= htmlspecialchars($current_customer['name']) ?></span>
                <span class="staff-role"><?= htmlspecialchars($current_customer['membership']) ?></span>
                <span class="staff-branch"><i class="fa-solid fa-gem"></i> <?= htmlspecialchars($current_customer['loyalty']) ?></span>
            </div>
        </div>
        </a>

       <a href="../auth/login.php" class="logout-btn"
   onclick="if (confirm('Log out from Savouré?')) { localStorage.removeItem('savoure_current_user'); return true; } return false;">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
</a>
    </div>
</aside>

<style>
/* ---- Dropdown Styles ---- */
.nav-group {
    display: flex;
    flex-direction: column;
}

.dropdown-toggle {
    width: 100%;
    cursor: pointer;
    justify-content: flex-start;
    background: none;
    border: none;
    font-family: inherit;
    text-align: left;
}

.chevron {
    margin-left: auto;
    font-size: .75rem;
    transition: transform 0.2s ease;
}

/* Rotate chevron icon when open */
.nav-group.open .chevron {
    transform: rotate(180deg);
}

/* Submenu collapse/expand behavior */
.nav-submenu {
    display: none;
    flex-direction: column;
    padding-left: 36px;
    margin-top: 2px;
    gap: 2px;
}

/* Display submenu when active or open */
.nav-group.open .nav-submenu {
    display: flex;
}

/* Sub-link item styling */
.sub-link {
    display: block;
    padding: 8px 12px;
    font-size: .8rem;
    font-weight: 500;
    color: rgba(214,201,182,.65);
    border-radius: 8px;
    transition: all 0.2s ease;
}

.sub-link:hover {
    color: var(--ivory, #F8F6EE);
    background: rgba(201,169,110,.1);
}

.sub-link.active {
    color: var(--gold, #C9A96E);
    font-weight: 600;
    background: rgba(201,169,110,.15);
}
.profile-chip-link { display: block; text-decoration: none; border-radius: 12px; transition: all .2s ease; margin-bottom: 8px; }
.profile-chip-link:hover .staff-chip, .profile-chip-link.active .staff-chip { background: rgba(201,169,110,.12); border: 1px solid rgba(201,169,110,.3); }
.staff-chip { border: 1px solid transparent; border-radius: 12px; }
</style>

<script>
function toggleDropdown(button) {
    const navGroup = button.closest('.nav-group');
    navGroup.classList.toggle('open');
    
    // Save state locally
    const isOpen = navGroup.classList.contains('open');
    localStorage.setItem('savoure_restaurants_dropdown_open', isOpen ? 'true' : 'false');
}

document.addEventListener('DOMContentLoaded', () => {
    const navGroup = document.querySelector('.nav-group');
    if (!navGroup) return;

    const currentPath = window.location.pathname.toLowerCase();
    const isRestaurantPage = ['allrestaurants', 'restaurants', 'frenchrestaurants', 'egyptianrestaurants', 'internationalrestaurants']
        .some(page => currentPath.includes(page));

    const savedState = localStorage.getItem('savoure_restaurants_dropdown_open');

    if (savedState === 'true' || isRestaurantPage) {
        navGroup.classList.add('open');
    }
});

function switchCustomerAccount(id, ev) {
    if (ev) ev.preventDefault();
    document.cookie = "customer_account=" + encodeURIComponent(id) + ";path=/;max-age=31536000";
    try { 
        localStorage.setItem('customer_account', id);
    } catch(e) {}
    window.location.reload();
}
</script>
<script src="../js/customer_profile.js"></script>