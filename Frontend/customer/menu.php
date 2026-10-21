<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
$page = 'menu.php';
$customer_id = $_GET['customer'] ?? $_SESSION['customer_account'] ?? 'sarah';
$customer_param = '?customer=' . urlencode($customer_id);

$restaurant_name = $_GET['restaurant'] ?? 'The Revolving Restaurant';
$restaurant_slug = strtolower(str_replace(' ', '-', $restaurant_name));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu · <?= htmlspecialchars($restaurant_name) ?> · Savouré</title>
    <link rel="stylesheet" href="../styles/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .menu-hero {
            background: linear-gradient(135deg, var(--navy-dk), var(--navy));
            border-radius: 16px; padding: 32px 36px; color: var(--ivory);
            margin-bottom: 28px; position: relative; overflow: hidden;
        }
        .menu-hero::after { content: '⚜'; position: absolute; right: 30px; top: 50%; transform: translateY(-50%); font-size: 6rem; opacity: .06; }
        .menu-hero-title { font-family: var(--serif); font-size: 2rem; font-weight: 700; line-height: 1.1; }
        .menu-hero-sub { font-size: .72rem; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: rgba(214,201,182,.7); margin-top: 6px; }
        .menu-hero-back { display: inline-flex; align-items: center; gap: 8px; margin-top: 18px; color: var(--gold); font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .menu-hero-back:hover { color: var(--ivory); }

        .menu-section-title { font-family: var(--serif); font-size: 1.35rem; font-weight: 700; color: var(--navy); margin: 32px 0 16px; padding-bottom: 10px; border-bottom: 1px solid var(--cream); display: flex; align-items: center; gap: 10px; }
        .menu-section-title i { color: var(--gold-dk); font-size: 1rem; }

        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }
        .menu-item {
            background: #fff; border: 1px solid var(--cream); border-radius: 14px;
            padding: 18px 20px; display: flex; justify-content: space-between; align-items: flex-start;
            gap: 16px; box-shadow: var(--shadow); transition: transform var(--t), box-shadow var(--t);
        }
        .menu-item:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(31,42,68,.1); }
        .menu-item-info { flex: 1; }
        .menu-item-name { font-family: var(--serif); font-size: 1.1rem; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
        .menu-item-desc { font-size: .8rem; color: var(--charcoal); opacity: .72; line-height: 1.45; margin-bottom: 6px; }
        .menu-item-price { font-family: var(--serif); font-size: 1.15rem; font-weight: 700; color: var(--gold-dk); white-space: nowrap; }
        .menu-item-price small { font-family: var(--sans); font-size: .62rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--beige); display: block; }

        .menu-item-add {
            flex-shrink: 0; width: 36px; height: 36px; border-radius: 50%;
            background: var(--navy); color: var(--ivory); display: flex; align-items: center; justify-content: center;
            font-size: .85rem; transition: all var(--t); cursor: pointer; border: none;
        }
        .menu-item-add:hover { background: var(--gold-dk); transform: scale(1.08); }

        .menu-cta {
            margin-top: 36px; padding: 24px 28px; background: var(--navy); border-radius: 16px;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
        }
        .menu-cta-text { color: var(--ivory); }
        .menu-cta-text strong { font-family: var(--serif); font-size: 1.2rem; display: block; }
        .menu-cta-text span { font-size: .8rem; opacity: .7; }
        .menu-cta .btn-gold { padding: 12px 28px; font-size: .82rem; }
    </style>
</head>
<body>

<div class="shell">
    <?php include '../../includes/custRest_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerCustomer.php'; ?>

        <main class="content" style="padding: 24px;">
            <div class="menu-hero">
                <div class="menu-hero-title" id="menu-hero-title"><?= htmlspecialchars($restaurant_name) ?></div>
                <div class="menu-hero-sub" id="menu-hero-cuisine">Fine Dining · Cairo</div>
                <a href="allRestaurants.php<?= $customer_param ?>" class="menu-hero-back">
                    <i class="fa-solid fa-arrow-left"></i> Back to Restaurants
                </a>
            </div>

            <div id="menu-container">
                <!-- Rendered by customerMenu.js -->
            </div>
        </main>
    </div>
</div>

<script>
    window.SAVOURE_RESTAURANT = <?= json_encode($restaurant_name) ?>;
    window.SAVOURE_CUSTOMER = <?= json_encode($customer_id) ?>;
</script>
<script src="../js/customerMenu.js"></script>
</body>
</html>