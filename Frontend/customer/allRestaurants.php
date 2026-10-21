<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Restaurants · Savouré</title>
    <link rel="stylesheet" href="../styles/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Restaurant Cards Grid Styling */
        .restaurants-section { margin-top: 12px; }
        .rest-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-top: 18px; }
        
        .rest-card { 
            background: #ffffff; 
            border: 1px solid rgba(0, 0, 0, 0.08); 
            border-radius: 16px; 
            overflow: hidden;
            display: flex; 
            flex-direction: column; 
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .rest-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
        
        .rest-img-wrapper { position: relative; width: 100%; height: 200px; overflow: hidden; background: #f4f4f4; }
        .rest-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
        .rest-card:hover .rest-img { transform: scale(1.04); }
        .rest-cuisine-badge { 
            position: absolute; 
            top: 12px; 
            right: 12px; 
            background: rgba(27, 42, 74, 0.85); 
            backdrop-filter: blur(4px);
            color: #fff; 
            font-size: 0.7rem; 
            font-weight: 600; 
            padding: 4px 10px; 
            border-radius: 20px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rest-body { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between; }
        .rest-title { font-size: 1.25rem; font-weight: 700; color: #1b2a4a; margin: 0 0 6px; }
        .rest-desc { font-size: 0.85rem; color: #666; line-height: 1.5; margin-bottom: 16px; }
        
        .rest-info-row { display: flex; align-items: center; gap: 16px; font-size: 0.78rem; color: #777; margin-bottom: 18px; }
        .rest-info-item { display: flex; align-items: center; gap: 6px; }
        .rest-info-item i { color: #a8843e; }

        .rest-footer { display: flex; gap: 10px; border-top: 1px solid #f0f0f0; padding-top: 16px; }
        .btn-rest { flex: 1; text-align: center; padding: 9px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.2s ease; }
        .btn-rest-primary { background: #1b2a4a; color: #fff; border: 1px solid #1b2a4a; }
        .btn-rest-primary:hover { background: #0f192d; }
        .btn-rest-outline { background: transparent; color: #1b2a4a; border: 1px solid #1b2a4a; }
        .btn-rest-outline:hover { background: rgba(27, 42, 74, 0.05); }
    </style>
</head>
<body>

<div class="shell">
    <?php include '../../includes/custRest_sidebar.php'; ?>
    
    <div class="main">
        <?php include '../../includes/headerCustomer.php'; ?>

        <main class="content" style="padding: 24px;">
            <section class="restaurants-section">
                <div class="section-head">
                    <div>
                        <span class="section-eye" style="color: #a8843e; font-weight: 600; font-size: 0.8rem;">Our Destinations</span>
                        <h2 class="section-title" style="margin-top: 2px;">Explore Our Restaurants</h2>
                    </div>
                </div>
                
                <div class="rest-grid" id="restaurants-grid">
                    <!-- Cards are dynamically rendered by customerDashboard.js -->
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Linked External JavaScript File -->
<script>
    window.SAVOURE_CUSTOMER = <?= json_encode($customer_id) ?>;
</script>
<script src="../js/customerDashboard.js"></script>
</body>
</html>