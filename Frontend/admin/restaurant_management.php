<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management · Savouré Admin</title>
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="shell">
    <?php include '../../includes/admin_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerAdmin.php'; ?>

        <main class="content">

            <!-- Banner -->
            <div class="admin-banner">
                <div class="admin-banner-info">
                    <div class="admin-banner-icon">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h2 class="admin-banner-title">Restaurant Management</h2>
                        <div class="admin-banner-sub">Register new dining destinations, configure cuisines, and monitor branch locations</div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-utensils"></i></div>
                    </div>
                    <div class="stat-value" id="stat-total-restaurants">0</div>
                    <div class="stat-label">Total Restaurants</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-layer-group"></i></div>
                    </div>
                    <div class="stat-value" id="stat-total-cuisines">0</div>
                    <div class="stat-label">Active Cuisines</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon navy"><i class="fa-solid fa-user-tie"></i></div>
                    </div>
                    <div class="stat-value" id="stat-assigned-staff">0</div>
                    <div class="stat-label">Assigned Staff</div>
                </div>
            </div>

            <!-- Action Toolbar -->
            <div class="toolbar">
                <div class="toolbar-left">
                    <div class="filters">
                        <button type="button" class="filter-btn active" onclick="filterRestaurantsByCuisine('all', this)">All Cuisines</button>
                        <button type="button" class="filter-btn" onclick="filterRestaurantsByCuisine('International', this)">International</button>
                        <button type="button" class="filter-btn" onclick="filterRestaurantsByCuisine('French', this)">French</button>
                        <button type="button" class="filter-btn" onclick="filterRestaurantsByCuisine('Egyptian', this)">Egyptian</button>
                    </div>
                </div>

                <div class="toolbar-right">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" class="search-input" placeholder="Search by name, address, cuisine..." oninput="handleRestaurantSearch(this.value)">
                    </div>
                    <button type="button" class="btn btn-gold" onclick="openAddRestaurantModal()">
                        <i class="fa-solid fa-plus"></i> Add Restaurant
                    </button>
                </div>
            </div>

            <!-- Restaurant Cards Grid -->
            <div class="admin-rest-grid" id="restaurants-grid">
                <!-- Rendered dynamically by adminRestaurants.js -->
            </div>

            <!-- Empty State -->
            <div class="empty-state" id="restaurants-empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-utensils"></i></div>
                <h3 class="empty-state-title">No Restaurants Found</h3>
                <p class="empty-state-text">No restaurants match your search or filter. Try a different query or add a new restaurant.</p>
                <button type="button" class="btn btn-primary" onclick="openAddRestaurantModal()">
                    <i class="fa-solid fa-plus"></i> Add New Restaurant
                </button>
            </div>

        </main>
    </div>
</div>

<!-- Add Restaurant Modal -->
<div class="modal-backdrop" id="add-restaurant-modal">
    <div class="modal">
        <div class="modal-head">
            <h3 class="modal-title">Register New Restaurant</h3>
            <button type="button" class="modal-close" onclick="closeAddRestaurantModal()">&times;</button>
        </div>
        <form id="add-restaurant-form">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="rest-name">Restaurant Name</label>
                    <input type="text" id="rest-name" class="form-control" placeholder="e.g. Bella Venezia" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="rest-cuisine">Cuisine Type</label>
                        <input type="text" id="rest-cuisine" class="form-control" placeholder="e.g. Italian Fine Dining" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="rest-category">Category</label>
                        <select id="rest-category" class="form-control">
                            <option value="International">International</option>
                            <option value="French">French</option>
                            <option value="Egyptian">Egyptian</option>
                            <option value="Italian">Italian</option>
                            <option value="Mediterranean">Mediterranean</option>
                            <option value="Asian">Asian</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="rest-address">Address & Location</label>
                    <input type="text" id="rest-address" class="form-control" placeholder="e.g. 23 Nile Corniche, Zamalek, Cairo" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="rest-hours">Opening Hours</label>
                        <input type="text" id="rest-hours" class="form-control" placeholder="e.g. 12:00 PM - 12:00 AM">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="rest-image">Cover Image URL (Optional)</label>
                        <input type="url" id="rest-image" class="form-control" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="rest-desc">Restaurant Description</label>
                    <textarea id="rest-desc" class="form-control" rows="3" placeholder="Brief summary of dining atmosphere, culinary identity, and guest experience..."></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-ghost" onclick="closeAddRestaurantModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Add Restaurant
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notifications Zone -->
<div class="toast-zone"></div>

<script src="../js/adminRestaurants.js"></script>
</body>
</html>
