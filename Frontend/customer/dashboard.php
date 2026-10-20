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
        .floor-mini { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .floor-mini .table-card { padding: 12px 8px; border-radius: 10px; }
        .floor-mini .tc-num { font-size: 1.4rem; }
        .floor-mini .tc-seats, .floor-mini .tc-guest { font-size: .62rem; }
        .legend { display: flex; gap: 16px; margin-bottom: 12px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 600; }
        .dot { width: 9px; height: 9px; border-radius: 50%; }

        /* Recommendations Cards Styling */
        .recs-section { margin-top: 24px; }
        .recs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-top: 14px; }
        .rec-card { 
            background: #ffffff; 
            border: 1px solid rgba(0, 0, 0, 0.08); 
            border-radius: 14px; 
            padding: 18px; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .rec-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.07); }
        .rec-tag { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #a8843e; letter-spacing: 0.5px; }
        .rec-title { font-size: 1.1rem; font-weight: 700; margin: 6px 0 4px; color: #1a1a1a; }
        .rec-desc { font-size: 0.82rem; color: #666; margin-bottom: 14px; line-height: 1.4; }
        .rec-footer { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f0f0f0; padding-top: 12px; }
        .rec-price { font-size: 1.1rem; font-weight: 800; color: #1b2a4a; }
        .rec-btn { background: #1b2a4a; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; cursor: pointer; transition: background 0.2s; }
        .rec-btn:hover { background: #0f192d; }
    </style>
</head>
<body>

<div class="shell">
    <?php include '../../includes/customer_sidebar.php'; ?> 
    <?php include '../../includes/custRest_sidebar.php'; ?>
    
    <div class="main">
        <?php include '../../includes/headerCustomer.php'; ?>

        <main class="content" style="padding: 24px;">
            <!-- Recommended Dishes Section -->
            <section class="recs-section">
                <div class="section-head">
                    <div>
                        <span class="section-eye" style="color: #a8843e; font-weight: 600; font-size: 0.8rem;">Chef Special Selection</span>
                        <h2 class="section-title" style="margin-top: 2px;">Recommended Dishes</h2>
                    </div>
                </div>
                
                <div class="recs-grid" id="recs-grid">
                    <!-- Dynamic Recommendation Cards loaded via customer_dashboard.js -->
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Linked External JavaScript File -->
<script src="../js/customerDashboard.js"></script>
</body>
</html>