<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management · Savouré Admin</title>
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="shell">
    <?php include '../../includes/admin_sidebar.php'; ?>

    <div class="main">
        <?php include '../../includes/headerAdmin.php'; ?>

        <main class="content">

            <div class="admin-banner">
                <div class="admin-banner-info">
                    <div class="admin-banner-icon">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <div>
                        <h2 class="admin-banner-title">User Management</h2>
                        <div class="admin-banner-sub">Control user accounts, permissions, and staff restaurant assignments</div>
                    </div>
                </div>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <div class="stat-value" id="stat-total-users">0</div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon gold"><i class="fa-solid fa-user-tie"></i></div>
                    </div>
                    <div class="stat-value" id="stat-staff-count">0</div>
                    <div class="stat-label">Staff Members</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon cream"><i class="fa-solid fa-user"></i></div>
                    </div>
                    <div class="stat-value" id="stat-customer-count">0</div>
                    <div class="stat-label">Customers</div>
                </div>

                <div class="stat-card dark">
                    <div class="stat-top">
                        <div class="stat-icon navy"><i class="fa-solid fa-shield-halved"></i></div>
                    </div>
                    <div class="stat-value" id="stat-admin-count">0</div>
                    <div class="stat-label">Administrators</div>
                </div>
            </div>

            <div class="toolbar">
                <div class="toolbar-left">
                    <div class="filters">
                        <button type="button" class="filter-btn active" onclick="filterUsersByRole('all', this)">All Users</button>
                        <button type="button" class="filter-btn" onclick="filterUsersByRole('staff', this)">Staff</button>
                        <button type="button" class="filter-btn" onclick="filterUsersByRole('customer', this)">Customers</button>
                        <button type="button" class="filter-btn" onclick="filterUsersByRole('admin', this)">Admins</button>
                    </div>
                </div>

                <div class="toolbar-right">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" class="search-input" placeholder="Search by name, email, restaurant..." oninput="handleUserSearch(this.value)">
                    </div>
                    <button type="button" class="btn btn-gold" onclick="openAddUserModal()">
                        <i class="fa-solid fa-user-plus"></i> Add User
                    </button>
                </div>
            </div>

            <div class="table-wrap" id="users-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User Profile</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th>Assigned Restaurant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                    </tbody>
                </table>
            </div>

            <div class="empty-state" id="users-empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-user-slash"></i></div>
                <h3 class="empty-state-title">No Users Found</h3>
                <p class="empty-state-text">No users match your active search or role criteria. Try adjusting the filter or add a new user.</p>
                <button type="button" class="btn btn-primary" onclick="openAddUserModal()">
                    <i class="fa-solid fa-user-plus"></i> Add New User
                </button>
            </div>

        </main>
    </div>
</div>

<div class="modal-backdrop" id="add-user-modal">
    <div class="modal">
        <div class="modal-head">
            <h3 class="modal-title">Create New User</h3>
            <button type="button" class="modal-close" onclick="closeAddUserModal()">&times;</button>
        </div>
        <form id="add-user-form">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="user-first-name">First Name</label>
                        <input type="text" id="user-first-name" class="form-control" placeholder="e.g. Sarah" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="user-last-name">Last Name</label>
                        <input type="text" id="user-last-name" class="form-control" placeholder="e.g. Jenkins" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="user-email">Email Address</label>
                    <input type="email" id="user-email" class="form-control" placeholder="e.g. sarah.jenkins@savoure.com" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="user-role">Account Role</label>
                        <select id="user-role" class="form-control" onchange="handleRoleChange(this.value)">
                            <option value="staff">Staff Member</option>
                            <option value="customer">Customer</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div class="form-group" id="form-group-restaurant">
                        <label class="form-label" for="user-restaurant-select">Assigned Restaurant</label>
                        <select id="user-restaurant-select" class="form-control">
                        </select>
                        <span class="form-hint">Staff members must be assigned to their operating branch</span>
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-ghost" onclick="closeAddUserModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

<div class="toast-zone"></div>

<script src="../js/adminUsers.js"></script>
</body>
</html>
