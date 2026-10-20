<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · Savouré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .dash-grid { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 24px; }
        @media (max-width: 1100px) { .dash-grid { grid-template-columns: 1fr; } }
        .floor-mini { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
        .floor-mini .table-card { padding: 12px 8px; border-radius: 10px; text-align: center; width: 100%; min-width: 0; cursor: pointer; text-decoration: none; }
        .floor-mini .tc-num { font-size: 1.4rem; line-height: 1.1; }
        .floor-mini .tc-seats, .floor-mini .tc-guest { font-size: .6rem; white-space: nowrap; }
        .legend { display: flex; gap: 14px; margin-bottom: 12px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 600; }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .badge-clickable { cursor: pointer; user-select: none; }
    </style>
</head>
<body>

<div class="shell">
    <?php include '../../includes/admin_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerAdmin.php'; ?>


    </div>
</div>
</body>
</html>