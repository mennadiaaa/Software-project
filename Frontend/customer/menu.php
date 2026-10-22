<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
$page = 'menu.php';
$customer_id = $_GET['customer'] ?? $_SESSION['customer_account'] ?? 'sarah';
$customer_param = '?customer=' . urlencode($customer_id);

$restaurant_name = $_GET['restaurant'] ?? 'The Revolving Restaurant';
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
        /* ---------- Menu Page ---------- */
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

        .menu-layout { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }
        @media (max-width: 1050px) { .menu-layout { grid-template-columns: 1fr; } }

        .menu-section-title { font-family: var(--serif); font-size: 1.35rem; font-weight: 700; color: var(--navy); margin: 28px 0 16px; padding-bottom: 10px; border-bottom: 1px solid var(--cream); display: flex; align-items: center; gap: 10px; }
        .menu-section-title i { color: var(--gold-dk); font-size: 1rem; }
        .menu-section-title:first-child { margin-top: 0; }

        .menu-grid { display: grid; grid-template-columns: 1fr; gap: 14px; }
        .menu-item {
            background: #fff; border: 1px solid var(--cream); border-radius: 14px;
            padding: 18px 20px; display: flex; justify-content: space-between; align-items: center;
            gap: 16px; box-shadow: var(--shadow); transition: transform var(--t), box-shadow var(--t);
        }
        .menu-item:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(31,42,68,.1); }
        .menu-item-info { flex: 1; }
        .menu-item-name { font-family: var(--serif); font-size: 1.1rem; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
        .menu-item-desc { font-size: .8rem; color: var(--charcoal); opacity: .72; line-height: 1.45; margin-bottom: 6px; }
        .menu-item-price { font-family: var(--serif); font-size: 1.15rem; font-weight: 700; color: var(--gold-dk); white-space: nowrap; }
        .menu-item-price small { font-family: var(--sans); font-size: .62rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--beige); display: block; }

        .menu-item-add {
            flex-shrink: 0; min-width: 110px; height: 40px; padding: 0 16px;
            border-radius: 10px; background: var(--navy); color: var(--ivory);
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            font-size: .74rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            transition: all var(--t); cursor: pointer; border: none;
        }
        .menu-item-add:hover { background: var(--gold-dk); transform: translateY(-1px); }

        /* ---------- Cart Sidebar (sticky) ---------- */
        .cart-panel {
            position: sticky; top: 90px;
            background: #fff; border: 1px solid var(--cream); border-radius: 16px;
            box-shadow: var(--shadow); overflow: hidden;
            display: flex; flex-direction: column;
        }
        .cart-head { background: var(--navy); color: var(--ivory); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; }
        .cart-head-title { font-family: var(--serif); font-size: 1.15rem; font-weight: 700; }
        .cart-head-count { background: var(--gold); color: var(--navy-dk); font-size: .68rem; font-weight: 700; padding: 2px 9px; border-radius: 20px; }

        .cart-body { padding: 16px 20px; max-height: 400px; overflow-y: auto; }
        .cart-empty { text-align: center; padding: 24px 8px; color: var(--beige); font-style: italic; font-size: .86rem; }
        .cart-empty i { font-size: 1.6rem; display: block; margin-bottom: 8px; opacity: .5; }

        .cart-line { display: flex; align-items: flex-start; gap: 10px; padding: 12px 0; border-bottom: 1px solid var(--cream); }
        .cart-line:last-child { border-bottom: none; }
        .cart-line-info { flex: 1; min-width: 0; }
        .cart-line-name { font-size: .85rem; font-weight: 600; color: var(--navy); line-height: 1.3; }
        .cart-line-price { font-size: .74rem; color: var(--gold-dk); font-weight: 700; margin-top: 2px; }
        .cart-line-qty { display: flex; align-items: center; gap: 6px; margin-top: 6px; }
        .cart-qbtn { width: 24px; height: 24px; border-radius: 6px; background: var(--cream); color: var(--navy); font-weight: 700; font-size: .8rem; cursor: pointer; border: none; display: flex; align-items: center; justify-content: center; }
        .cart-qbtn:hover { background: var(--beige); }
        .cart-qnum { min-width: 22px; text-align: center; font-size: .82rem; font-weight: 700; color: var(--navy); }
        .cart-remove { color: #8B2635; background: none; border: none; font-size: .78rem; cursor: pointer; padding: 4px; }
        .cart-remove:hover { background: #FDEAEC; border-radius: 6px; }

        .cart-foot { padding: 16px 20px; border-top: 1px solid var(--cream); background: var(--ivory); }
        .cart-total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .cart-total-lbl { font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--beige); }
        .cart-total-val { font-family: var(--serif); font-size: 1.4rem; font-weight: 700; color: var(--navy); }

        .fulfillment-tabs { display: flex; gap: 6px; margin-bottom: 12px; }
        .fulfill-tab { flex: 1; padding: 10px 8px; border: 1.5px solid var(--beige); border-radius: 10px; background: #fff; cursor: pointer; text-align: center; transition: all var(--t); font-size: .68rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--charcoal); }
        .fulfill-tab i { display: block; font-size: 1rem; margin-bottom: 4px; }
        .fulfill-tab.active { border-color: var(--navy); background: var(--navy); color: var(--ivory); }

        .cart-field { margin-bottom: 10px; }
        .cart-field label { display: block; font-size: .66rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--navy); margin-bottom: 5px; }
        .cart-field input, .cart-field select, .cart-field textarea {
            width: 100%; padding: 8px 12px; border: 1.5px solid var(--beige); border-radius: 8px;
            font-family: var(--sans); font-size: .82rem; color: var(--charcoal); outline: none; background: #fff;
            transition: border-color var(--t);
        }
        .cart-field input:focus, .cart-field select:focus, .cart-field textarea:focus { border-color: var(--navy); }

        .cart-place-btn {
            width: 100%; justify-content: center; padding: 13px; margin-top: 6px;
            font-size: .76rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
            background: linear-gradient(135deg, var(--gold), var(--gold-dk)); color: var(--navy-dk);
            border: none; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
            transition: all var(--t); box-shadow: 0 3px 14px rgba(201,169,110,.3);
        }
        .cart-place-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(201,169,110,.45); }
        .cart-place-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

        .cart-note { font-size: .66rem; color: var(--beige); text-align: center; margin-top: 8px; line-height: 1.4; }

        /* Floating cart button for mobile when scrolled past the panel */
        .cart-fab {
            display: none; position: fixed; bottom: 24px; right: 24px; z-index: 400;
            width: 56px; height: 56px; border-radius: 50%;
            background: var(--navy); color: var(--gold); font-size: 1.2rem;
            align-items: center; justify-content: center; cursor: pointer; border: none;
            box-shadow: 0 8px 24px rgba(31,42,68,.3);
        }
        .cart-fab-count { position: absolute; top: -4px; right: -4px; background: var(--gold); color: var(--navy-dk); min-width: 20px; height: 20px; padding: 0 5px; border-radius: 10px; font-size: .64rem; font-weight: 700; display: flex; align-items: center; justify-content: center; }
        @media (max-width: 1050px) {
            .cart-fab { display: flex; }
            .cart-panel { position: fixed; inset: 0; z-index: 500; border-radius: 0; max-width: 100%; display: none; overflow-y: auto; }
            .cart-panel.mobile-open { display: flex; }
            .cart-close-mobile { display: inline-flex !important; }
        }
        .cart-close-mobile { display: none; background: rgba(255,255,255,.15); color: var(--ivory); width: 30px; height: 30px; border-radius: 50%; align-items: center; justify-content: center; cursor: pointer; border: none; }
    </style>
      <script>const REQUIRED_ROLE = 'customer';</script>
    <script src="../js/guard.js"></script>
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

            <div class="menu-layout">
                <div id="menu-container">
                    <!-- Sections rendered by customerMenu.js -->
                </div>

                <!-- Sticky Cart Panel -->
                <aside class="cart-panel" id="cart-panel">
                    <div class="cart-head">
                        <div class="cart-head-title"><i class="fa-solid fa-bag-shopping" style="color:var(--gold);margin-right:6px;"></i> Your Pre-Order</div>
                        <span class="cart-head-count" id="cart-count">0</span>
                        <button class="cart-close-mobile" onclick="closeMobileCart()"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <div class="cart-body" id="cart-body">
                        <!-- Rendered by customerMenu.js -->
                    </div>

                    <div class="cart-foot" id="cart-foot">
                        <div class="cart-total-row">
                            <span class="cart-total-lbl">Total</span>
                            <span class="cart-total-val" id="cart-total">0 EGP</span>
                        </div>

                        <div class="fulfillment-tabs">
                            <button type="button" class="fulfill-tab active" data-type="pickup" onclick="selectFulfillment('pickup', this)">
                                <i class="fa-solid fa-bag-shopping"></i> Pickup
                            </button>
                            <button type="button" class="fulfill-tab" data-type="dinein" onclick="selectFulfillment('dinein', this)">
                                <i class="fa-solid fa-utensils"></i> Dine-In
                            </button>
                        </div>

                        <!-- Pickup fields -->
                        <div id="pickup-fields">
                            <div class="cart-field">
                                <label for="pickup-time">Pickup Time</label>
                                <input type="time" id="pickup-time" value="19:00">
                            </div>
                        </div>

                        <!-- Dine-in fields -->
                        <div id="dinein-fields" style="display:none;">
                            <div class="cart-field">
                                <label for="dinein-time">Arrival Time</label>
                                <input type="time" id="dinein-time" value="19:00">
                            </div>
                            <div class="cart-field">
                                <label for="dinein-party">Party Size</label>
                                <select id="dinein-party">
                                    <option value="1">1 guest</option>
                                    <option value="2" selected>2 guests</option>
                                    <option value="3">3 guests</option>
                                    <option value="4">4 guests</option>
                                    <option value="5">5 guests</option>
                                    <option value="6">6+ guests</option>
                                </select>
                            </div>
                            <div class="cart-field">
                                <label for="dinein-notes">Special Requests</label>
                                <textarea id="dinein-notes" rows="2" placeholder="Allergies, celebrations..."></textarea>
                            </div>
                        </div>

                        <button type="button" class="cart-place-btn" id="place-order-btn" onclick="placePreOrder()" disabled>
                            <i class="fa-solid fa-check"></i> Save Pre-Order
                        </button>
                        <div class="cart-note">Your order will be prepared and waiting for you.</div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<!-- Mobile floating cart button -->
<button class="cart-fab" id="cart-fab" onclick="openMobileCart()">
    <i class="fa-solid fa-bag-shopping"></i>
    <span class="cart-fab-count" id="cart-fab-count">0</span>
</button>

<script>
    window.SAVOURE_RESTAURANT = <?= json_encode($restaurant_name) ?>;
    window.SAVOURE_CUSTOMER = <?= json_encode($customer_id) ?>;
</script>
<script src="../js/customerMenu.js"></script>
</body>
</html>