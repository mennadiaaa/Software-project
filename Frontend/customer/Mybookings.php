<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings · Savouré</title>
    <link rel="stylesheet" href="../styles/customer.css">
    <link rel="stylesheet" href="../styles/myBookings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="shell">
    <?php include '../../includes/custRest_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerCustomer.php'; ?>

        <main class="content">
            <div class="section-head">
                <div>
                    <span class="section-eye">Reservations</span>
                    <h2 class="section-title">My Bookings</h2>
                </div>
                <a href="Reservation.php" class="btn btn-gold"><i class="fa-solid fa-plus"></i> New reservation</a>
            </div>

            <!-- Summary -->
            <div class="stats bk-stats" id="bk-stats"></div>

            <!-- Filters -->
            <div class="filters" id="bk-filters">
                <button type="button" class="filter-btn active" data-filter="all">All</button>
                <button type="button" class="filter-btn" data-filter="upcoming">Upcoming</button>
                <button type="button" class="filter-btn" data-filter="past">Past</button>
                <button type="button" class="filter-btn" data-filter="cancelled">Cancelled</button>
            </div>

            <!-- List -->
            <div class="bk-list" id="bk-list"></div>
        </main>
    </div>
</div>

<!-- Cancel confirmation -->
<div class="modal-backdrop" id="cancel-modal" role="dialog" aria-modal="true" aria-labelledby="cancel-title">
    <div class="modal">
        <div class="modal-head">
            <span class="modal-title" id="cancel-title">Cancel this reservation?</span>
            <button type="button" class="modal-close" id="cancel-close" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p class="bk-modal-text" id="cancel-text"></p>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancel-keep">Keep reservation</button>
            <button type="button" class="btn btn-danger" id="cancel-confirm">Cancel reservation</button>
        </div>
    </div>
</div>

<div class="toast-zone" id="toast-zone"></div>
<script src="../../includes/users.js"></script>
<script src="../js/myBookings.js"></script>
</body>
</html>