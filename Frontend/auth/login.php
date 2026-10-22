<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · Savouré</title>
    <link rel="stylesheet" href="../styles/staff.css">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <form id="login-form" class="modal">
        <div class="modal-head">
            <h2 class="modal-title">Log in to Savouré</h2>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" class="form-control" required>
            </div>
            <p id="login-error" style="color:#8B2635;font-size:.8rem;"></p>
        </div>
        <div class="modal-foot">
            <a href="signup.php" style="margin-right:auto;font-size:.85rem;">Create account</a>
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
    <script src="../../includes/users.js"></script>
    <script src="../js/login.js"></script>
</body>
</html>