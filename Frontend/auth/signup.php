<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up · Savouré</title>
    <link rel="stylesheet" href="../styles/staff.css">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <form id="signup-form" class="modal">
        <div class="modal-head">
            <h2 class="modal-title">Create your Savouré account</h2>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label" for="firstName">First name</label>
                <input type="text" id="firstName" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="lastName">Last name</label>
                <input type="text" id="lastName" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password (min 8 characters)</label>
                <input type="password" id="password" class="form-control" minlength="8" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="confirm">Confirm password</label>
                <input type="password" id="confirm" class="form-control" required>
            </div>
            <p id="signup-error" style="color:#8B2635;font-size:.8rem;"></p>
        </div>
        <div class="modal-foot">
            <a href="login.php" style="margin-right:auto;font-size:.85rem;">Already have an account?</a>
            <button type="submit" class="btn btn-primary">Sign up</button>
        </div>
    </form>
    <script src="../../includes/users.js"></script>
    <script src="../js/signup.js"></script>
</body>
</html>