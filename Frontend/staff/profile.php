<?php
require_once __DIR__ . '/../../includes/staff_context.php';

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
        $saved = true;
        // In a real app: update DB here
    }
}

// Mock per-staff data for the employment section
$staff_details = [
    'nour'    => ['email'=>'nour.khalil@savoure.com',    'phone'=>'+20 100 111 2233', 'hired'=>'March 2022',   'salary'=>'12,500 EGP/mo', 'shift'=>'Fri–Tue · 17:00–01:00', 'hours_week'=>40],
    'tarek'   => ['email'=>'tarek.mansour@savoure.com',  'phone'=>'+20 101 444 5566', 'hired'=>'June 2021',    'salary'=>'12,500 EGP/mo', 'shift'=>'Sat–Wed · 16:00–00:00', 'hours_week'=>40],
    'pierre'  => ['email'=>'pierre.dubois@savoure.com',  'phone'=>'+33 6 12 34 56 78','hired'=>'January 2023', 'salary'=>'14,000 EGP/mo', 'shift'=>'Thu–Mon · 18:00–02:00', 'hours_week'=>40],
    'michael' => ['email'=>'michael.ross@savoure.com',   'phone'=>'+20 102 777 8899', 'hired'=>'August 2020',  'salary'=>'13,000 EGP/mo', 'shift'=>'Sun–Thu · 15:00–23:00', 'hours_week'=>40],
];
$det = $staff_details[$staff_id];
$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile · Savouré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        window.SAVOURE_STAFF_ID         = <?= json_encode($staff_id) ?>;
        window.SAVOURE_STAFF_RESTAURANT = <?= json_encode($assigned_restaurant) ?>;
    </script>
    <style>
        .profile-wrap {
            max-width: 600px;
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
        .role-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(31,42,68,.08); color: var(--navy);
            padding: 5px 12px; border-radius: 20px;
            font-size: .8rem; font-weight: 600;
            margin-top: 8px;
        }

        .profile-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,.07);
            border-radius: 16px;
            padding: 32px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
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

        /* Employment info grid */
        .emp-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .emp-item {
            background: var(--ivory);
            border: 1px solid rgba(0,0,0,.07);
            border-radius: 12px;
            padding: 16px;
        }
        .emp-item-label {
            font-size: .68rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: #aaa; margin-bottom: 6px;
        }
        .emp-item-value {
            font-size: .95rem; font-weight: 600; color: var(--navy-dk);
        }
        .emp-item-value .icon { color: var(--gold); margin-right: 6px; font-size: .85rem; }

        .hours-bar-wrap { margin-top: 8px; }
        .hours-bar-bg {
            background: rgba(0,0,0,.07); border-radius: 99px; height: 6px;
            overflow: hidden; margin-top: 6px;
        }
        .hours-bar-fill {
            height: 100%; border-radius: 99px;
            background: linear-gradient(90deg, var(--gold), var(--gold-dk));
        }
    </style>
</head>
<body>
<div class="shell">
    <?php include __DIR__ . '/../../includes/staff_sidebar.php'; ?>

    <div class="main">
        <?php include __DIR__ . '/../../includes/headerStaff.php'; ?>

        <div class="profile-wrap">

            <div class="profile-header">
                <div class="profile-avatar-big"><?= htmlspecialchars($current_staff['avatar']) ?></div>
                <h1><?= htmlspecialchars($current_staff['name']) ?></h1>
                <p><?= htmlspecialchars($assigned_restaurant) ?></p>
                <span class="role-badge">
                    <i class="fa-solid fa-id-badge"></i>
                    <?= htmlspecialchars($current_staff['role']) ?>
                </span>
            </div>

            <?php if ($saved): ?>
                <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Profile updated successfully.</div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
            <?php endif; ?>

            <div class="profile-card">
                <div class="section-label">Personal Details</div>
                <form method="POST" action="profile.php<?= $staff_param ?>">

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name"
                               value="<?= htmlspecialchars($current_staff['name']) ?>"
                               placeholder="Your full name">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($det['email']) ?>"
                               placeholder="you@savoure.com">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone"
                               value="<?= htmlspecialchars($det['phone']) ?>"
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

            <div class="profile-card">
                <div class="section-label">Employment &amp; Shift Info</div>
                <div class="emp-grid">

                    <div class="emp-item">
                        <div class="emp-item-label">Restaurant</div>
                        <div class="emp-item-value">
                            <span class="icon"><i class="fa-solid fa-utensils"></i></span>
                            <?= htmlspecialchars($assigned_restaurant) ?>
                        </div>
                    </div>

                    <div class="emp-item">
                        <div class="emp-item-label">Role</div>
                        <div class="emp-item-value">
                            <span class="icon"><i class="fa-solid fa-id-badge"></i></span>
                            <?= htmlspecialchars($current_staff['role']) ?>
                        </div>
                    </div>

                    <div class="emp-item">
                        <div class="emp-item-label">Shift Schedule</div>
                        <div class="emp-item-value">
                            <span class="icon"><i class="fa-solid fa-clock"></i></span>
                            <?= htmlspecialchars($det['shift']) ?>
                        </div>
                    </div>

                    <div class="emp-item">
                        <div class="emp-item-label">Hire Date</div>
                        <div class="emp-item-value">
                            <span class="icon"><i class="fa-solid fa-calendar-days"></i></span>
                            <?= htmlspecialchars($det['hired']) ?>
                        </div>
                    </div>

                    <div class="emp-item">
                        <div class="emp-item-label">Monthly Salary</div>
                        <div class="emp-item-value">
                            <span class="icon"><i class="fa-solid fa-money-bill-wave"></i></span>
                            <?= htmlspecialchars($det['salary']) ?>
                        </div>
                    </div>

                    <div class="emp-item">
                        <div class="emp-item-label">Weekly Hours</div>
                        <div class="emp-item-value">
                            <span class="icon"><i class="fa-solid fa-business-time"></i></span>
                            <?= $det['hours_week'] ?> hrs
                            <div class="hours-bar-wrap">
                                <div class="hours-bar-bg">
                                    <div class="hours-bar-fill" style="width:<?= min(100, round($det['hours_week']/48*100)) ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
