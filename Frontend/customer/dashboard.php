<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · Savouré</title>
    <link rel="stylesheet" href="../styles/customer.css">
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
    <?php include '../../includes/customer_sidebar.php'; ?> 
    <?php include '../../includes/custRest_sidebar.php'; ?>
    <div class="main">
        <?php include '../../includes/headerCustomer.php'; ?>
    </div>
</div>
</div>
    
    
    
    
    
<script>
    function tick() {
    const d = new Date(), p = n => String(n).padStart(2,'0');
    document.getElementById('clk').textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    document.getElementById('clk-date').textContent = d.toLocaleDateString('en-EG', { weekday:'short', month:'short', day:'numeric' });
}
tick(); setInterval(tick, 1000);
</script>