<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

$customer_accounts = [
    'sarah'   => ['id' => 'sarah',   'name' => 'Sarah Hassan', 'membership' => 'VIP Member',     'avatar' => 'S', 'loyalty' => '1,250 Pts'],
    'youssef' => ['id' => 'youssef', 'name' => 'Youssef Ali',  'membership' => 'Regular Member', 'avatar' => 'Y', 'loyalty' => '450 Pts'],
];
if (isset($_GET['customer']) && isset($customer_accounts[$_GET['customer']])) {
    $_SESSION['customer_account'] = $_GET['customer'];
}
$customer_id = $_GET['customer'] ?? $_SESSION['customer_account'] ?? $_COOKIE['customer_account'] ?? 'sarah';
if (!isset($customer_accounts[$customer_id])) $customer_id = 'sarah';
$_SESSION['customer_account'] = $customer_id;
$current_customer = $customer_accounts[$customer_id];
$customer_param   = '?customer=' . urlencode($customer_id);

$form = [
    'name'  => $current_customer['name'],
    'email' => $customer_id === 'sarah' ? 'sarah.hassan@example.com' : 'youssef.ali@example.com',
    'phone' => $customer_id === 'sarah' ? '+20 100 234 5678' : '+20 101 999 1234',
];

// Handle form save
$saved  = false;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pass  = $_POST['password']   ?? '';
    $pass2 = $_POST['password2']  ?? '';

    if ($name === '')  $errors[] = 'Name is required.';
    if ($email === '') $errors[] = 'Email is required.';
    if ($pass !== '' && $pass !== $pass2) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $form['name']  = $name;
        $form['email'] = $email;
        $form['phone'] = $phone;
        $saved = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile · Savouré</title>
    <link rel="stylesheet" href="../styles/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>const REQUIRED_ROLE = 'customer';</script>
    <script src="../js/guard.js"></script>
    <style>
        .profile-wrap {
            max-width: 560px;
            margin: 40px auto;
            padding: 0 24px 60px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 36px;
        }
        .profile-avatar-big {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dk));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--serif); font-size: 2.2rem; font-weight: 700;
            color: var(--ivory);
            margin: 0 auto 16px;
        }
        .profile-header h1 {
            font-family: var(--serif); font-size: 1.9rem; font-weight: 700;
            color: var(--navy-dk); margin-bottom: 4px;
        }
        .profile-header p { font-size: .85rem; color: #888; }

        .profile-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,.07);
            border-radius: 16px;
            padding: 32px;
            box-shadow: var(--shadow);
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: .75rem; font-weight: 600;
            letter-spacing: .08em; text-transform: uppercase;
            color: #999; margin-bottom: 7px;
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid rgba(0,0,0,.12);
            border-radius: 10px;
            font-family: var(--sans); font-size: .9rem;
            color: var(--charcoal);
            background: var(--ivory);
            transition: border-color .2s;
            outline: none;
        }
        .form-group input:focus { border-color: var(--gold); background: #fff; }

        .section-label {
            font-size: .7rem; font-weight: 700; letter-spacing: .18em;
            text-transform: uppercase; color: var(--gold-dk);
            margin: 0 0 16px;
        }
        .divider { border: none; border-top: 1px solid rgba(0,0,0,.07); margin: 24px 0; }

        .btn-save {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dk));
            color: var(--ivory);
            border: none; border-radius: 10px;
            font-family: var(--sans); font-size: .9rem; font-weight: 600;
            cursor: pointer;
            transition: opacity .2s;
            margin-top: 8px;
        }
        .btn-save:hover { opacity: .88; }

        .alert {
            padding: 12px 16px; border-radius: 10px;
            font-size: .85rem; margin-bottom: 22px;
        }
        .alert-success { background: #edfaf4; color: #2a7a5a; border: 1px solid #b8e8d4; }
        .alert-error   { background: #fff2f2; color: #c0415b; border: 1px solid #f5c2cc; }

        .loyalty-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(201,169,110,.12); color: var(--gold-dk);
            padding: 5px 12px; border-radius: 20px;
            font-size: .8rem; font-weight: 600;
        }
    </style>
</head>
<body>
<div class="shell">
    <?php include '../../includes/custRest_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerCustomer.php'; ?>

        <main class="content" style="padding:24px;">
            <div class="profile-wrap">

                <div class="profile-header">
                    <div class="profile-avatar-big"><?= htmlspecialchars($current_customer['avatar']) ?></div>
                    <h1><?= htmlspecialchars($current_customer['name']) ?></h1>
                    <p><?= htmlspecialchars($current_customer['membership']) ?></p>
                    <br>
                    <span class="loyalty-badge">
                        <i class="fa-solid fa-gem"></i>
                        <?= htmlspecialchars($current_customer['loyalty']) ?>
                    </span>
                </div>

                <?php if ($saved): ?>
                    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Profile updated successfully.</div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
                <?php endif; ?>

                <div class="profile-card">
                    <form method="POST" action="profile.php<?= $customer_param ?>">

                        <div class="section-label">Personal Info</div>

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name"
                                   value="<?= htmlspecialchars($form['name']) ?>"
                                   placeholder="Your full name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email"
                                   value="<?= htmlspecialchars($form['email']) ?>"
                                   placeholder="you@example.com">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone"
                                   value="<?= htmlspecialchars($form['phone']) ?>"
                                   placeholder="+20 1XX XXX XXXX">
                        </div>

                        <hr class="divider">
                        <div class="section-label">Change Password</div>
                        <p style="font-size:.82rem;color:#999;margin-bottom:16px;">Leave blank to keep your current password.</p>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" placeholder="••••••••">
                        </div>

                        <div class="form-group">
                            <label for="password2">Confirm New Password</label>
                            <input type="password" id="password2" name="password2" placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
<script>
    window.SAVOURE_CUSTOMER = <?= json_encode($customer_id) ?>;
</script>
</body>
</html>
