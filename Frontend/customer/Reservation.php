<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Reservation · Savouré</title>
    <link rel="stylesheet" href="../styles/customer.css">
    <link rel="stylesheet" href="../styles/reservation.css">
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
                    <h2 class="section-title">Make a Reservation</h2>
                </div>
            </div>

            <!-- Progress -->
            <div class="stepper res-stepper" id="res-stepper">
                <div class="step active" data-step="1"><div class="step-dot">1</div><span class="step-name">Restaurant</span></div>
                <div class="step-line"></div>
                <div class="step" data-step="2"><div class="step-dot">2</div><span class="step-name">Date &amp; Time</span></div>
                <div class="step-line"></div>
                <div class="step" data-step="3"><div class="step-dot">3</div><span class="step-name">Details</span></div>
                <div class="step-line"></div>
                <div class="step" data-step="4"><div class="step-dot">4</div><span class="step-name">Review</span></div>
            </div>

            <!-- STEP 1 : restaurant -->
            <section class="res-panel" id="panel-1">
                <h3 class="res-q">Where would you like to dine?</h3>
                <div class="res-grid" id="res-restaurants"></div>
            </section>

            <!-- STEP 2 : date + time -->
            <section class="res-panel" id="panel-2" hidden>
                <div class="res-split">
                    <div class="res-box">
                        <h3 class="res-q">Choose a date</h3>
                        <div class="cal">
                            <div class="cal-head">
                                <button type="button" class="cal-nav" id="cal-prev" aria-label="Previous month"><i class="fa-solid fa-chevron-left"></i></button>
                                <span class="cal-month" id="cal-month"></span>
                                <button type="button" class="cal-nav" id="cal-next" aria-label="Next month"><i class="fa-solid fa-chevron-right"></i></button>
                            </div>
                            <div class="cal-week">
                                <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                            </div>
                            <div class="cal-grid" id="cal-grid"></div>
                        </div>
                    </div>

                    <div class="res-box">
                        <h3 class="res-q">Choose a time</h3>
                        <p class="res-hint" id="slot-hint">Pick a date first to see available times.</p>
                        <div class="slot-list" id="slot-list"></div>
                    </div>
                </div>
            </section>

            <!-- STEP 3 : details -->
            <section class="res-panel" id="panel-3" hidden>
                <div class="res-box" id="seating-box" hidden>
                    <h3 class="res-q">Would you like to sit indoor or outdoor?</h3>
                    <div class="seat-options" id="seat-options">
                        <button type="button" class="seat-card" data-seat="Indoor">
                            <i class="fa-solid fa-couch"></i><strong>Indoor</strong><span>Air-conditioned dining room</span>
                        </button>
                        <button type="button" class="seat-card" data-seat="Outdoor">
                            <i class="fa-solid fa-tree"></i><strong>Outdoor</strong><span>Open-air terrace</span>
                        </button>
                    </div>
                </div>

                <div class="res-box">
                    <h3 class="res-q">Number of guests</h3>
                    <div class="counter">
                        <button type="button" class="counter-btn" id="guest-minus" aria-label="Fewer guests"><i class="fa-solid fa-minus"></i></button>
                        <div class="counter-val"><span id="guest-count">2</span><small id="guest-label">guests</small></div>
                        <button type="button" class="counter-btn" id="guest-plus" aria-label="More guests"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>

                <div class="res-box">
                    <h3 class="res-q">Special requests <span class="res-opt">optional</span></h3>
                    <textarea class="form-control res-notes" id="res-notes" maxlength="300" rows="4"
                        placeholder="Allergies, a birthday surprise, a high chair, a quiet corner..."></textarea>
                    <div class="res-count"><span id="notes-count">0</span>/300</div>
                </div>
            </section>

            <!-- STEP 4 : review -->
            <section class="res-panel" id="panel-4" hidden>
                <h3 class="res-q">Please review your reservation</h3>
                <div class="res-split">
                    <div class="card">
                        <div class="card-head"><span class="card-head-title">Your information</span></div>
                        <dl class="sum-list" id="sum-person"></dl>
                    </div>
                    <div class="card">
                        <div class="card-head"><span class="card-head-title">Reservation details</span></div>
                        <dl class="sum-list" id="sum-reservation"></dl>
                    </div>
                </div>
            </section>

            <!-- THANK YOU -->
            <section class="res-panel res-thanks" id="panel-done" hidden>
                <div class="thanks-icon"><i class="fa-solid fa-check"></i></div>
                <h3 class="thanks-title">Thank you, <span id="thanks-name"></span>!</h3>
                <p class="thanks-text">Your reservation at <strong id="thanks-rest"></strong> is confirmed.<br>
                    We look forward to welcoming you on <strong id="thanks-when"></strong>.</p>
                <p class="thanks-ref">Reference <strong id="thanks-ref"></strong></p>
                <div class="thanks-actions">
                    <a href="allRestaurants.php" class="btn btn-ghost">View restaurants</a>
                    <button type="button" class="btn btn-gold" id="btn-again">Make another reservation</button>
                </div>
            </section>

            <!-- Navigation -->
            <div class="res-nav" id="res-nav">
                <button type="button" class="btn btn-ghost" id="btn-back" hidden><i class="fa-solid fa-arrow-left"></i> Back</button>
                <button type="button" class="btn btn-primary" id="btn-next">Continue</button>
            </div>
        </main>
    </div>
</div>

<div class="toast-zone" id="toast-zone"></div>
<script src="../../includes/users.js"></script>
<script src="../js/reservation.js"></script>
</body>
</html>