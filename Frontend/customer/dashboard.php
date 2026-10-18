<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · Souvré Staff</title>
    <link rel="stylesheet" href="../styles/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .dash-grid { display: grid; grid-template-columns: 3fr 2fr; gap: 24px; }
        @media (max-width: 1100px) { .dash-grid { grid-template-columns: 1fr; } }
        .floor-mini { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; }
        .floor-mini .table-card { padding: 12px 8px; border-radius: 10px; }
        .floor-mini .tc-num { font-size: 1.4rem; }
        .floor-mini .tc-seats, .floor-mini .tc-guest { font-size: .62rem; }
        .legend { display: flex; gap: 16px; margin-bottom: 12px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 600; }
        .dot { width: 9px; height: 9px; border-radius: 50%; }
    </style>
</head>
<body>
<div class="shell">
    <?php include 'includes/headerCustomer.php';
    
    
    
    
    
    
    ?>
</div>