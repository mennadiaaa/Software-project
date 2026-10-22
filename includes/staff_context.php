<?php
/**
 * Savouré – shared staff context (include with require_once on EVERY staff page + the sidebar).
 *
 * Decides which staff member is active and which restaurant they belong to, once, in one place.
 * Accepts every ID style used in the project:
 *   short ids      : nour, tarek, michael, pierre
 *   users.js ids   : staff_nour, staff_tarek, staff_02 (Pierre), staff_03 (Michael)
 *   restaurant name: ?restaurant=Mo Bistro
 *
 * Exposes: $staff_accounts, $staff_id, $current_staff, $assigned_restaurant, $staff_param,
 *          $SAVOURE_DATA, $pending_orders_count, $open_requests_count, $pending_reservations_count
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$staff_accounts = [
    'nour'    => ['id' => 'nour',    'name' => 'Nour Khalil',   'role' => 'Floor Manager', 'avatar' => 'N', 'restaurant' => 'The Revolving Restaurant'],
    'tarek'   => ['id' => 'tarek',   'name' => 'Tarek Mansour', 'role' => 'Floor Manager', 'avatar' => 'T', 'restaurant' => 'Le Petite Chef'],
    'pierre'  => ['id' => 'pierre',  'name' => 'Pierre Dubois', 'role' => 'Floor Manager', 'avatar' => 'P', 'restaurant' => 'Le Petite Chef'],
    'michael' => ['id' => 'michael', 'name' => 'Michael Ross',  'role' => 'Floor Manager', 'avatar' => 'M', 'restaurant' => 'Mo Bistro'],
];

// users.js ids  ->  short ids
$staff_aliases = [
    'staff_nour'  => 'nour',
    'staff_tarek' => 'tarek',
    'staff_02'    => 'pierre',
    'staff_03'    => 'michael',
];

// ?restaurant=... fallback
$restaurant_to_staff = [
    'The Revolving Restaurant' => 'nour',
    'Le Petite Chef'           => 'tarek',
    'Mo Bistro'                => 'michael',
];

if (!function_exists('savoure_normalize_staff_id')) {
    function savoure_normalize_staff_id($raw, array $accounts, array $aliases) {
        if (!is_string($raw) || $raw === '') return null;
        $raw = strtolower(trim($raw));
        if (isset($aliases[$raw])) $raw = $aliases[$raw];
        return isset($accounts[$raw]) ? $raw : null;
    }
}

// Priority: URL ?staff  >  URL ?restaurant  >  session  >  cookie  >  default
$staff_id = null;
if (isset($_GET['staff'])) {
    $staff_id = savoure_normalize_staff_id($_GET['staff'], $staff_accounts, $staff_aliases);
}
if (!$staff_id && isset($_GET['restaurant']) && is_string($_GET['restaurant'])) {
    $staff_id = $restaurant_to_staff[$_GET['restaurant']] ?? null;
}
if (!$staff_id) {
    foreach ([$_SESSION['staff_account'] ?? null, $_SESSION['staff_id'] ?? null, $_COOKIE['staff_account'] ?? null] as $candidate) {
        $staff_id = savoure_normalize_staff_id($candidate, $staff_accounts, $staff_aliases);
        if ($staff_id) break;
    }
}
if (!$staff_id) $staff_id = 'nour';

$_SESSION['staff_account'] = $staff_id;

$current_staff       = $staff_accounts[$staff_id];
$assigned_restaurant = $current_staff['restaurant'];
$staff_param         = '?staff=' . urlencode($staff_id);

// Shared per-restaurant data (reservations, tables, requests, orders)
$SAVOURE_DATA = require __DIR__ . '/staff_data.php';

$pending_orders_count = count($SAVOURE_DATA['orders'][$assigned_restaurant]['incoming'] ?? []);
$pending_reservations_count = count(array_filter(
    $SAVOURE_DATA['reservations'][$assigned_restaurant] ?? [],
    fn($r) => $r[7] === 'pending'
));
$open_requests_count  = count(array_filter(
    $SAVOURE_DATA['requests'][$assigned_restaurant] ?? [],
    fn($r) => $r['status'] === 'pending'
));