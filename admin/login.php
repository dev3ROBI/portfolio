<?php
/**
 * Admin Login
 * Secure authentication for portfolio management
 */
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $user = dbGetRow(
            "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1",
            'ss',
            [$username, $username]
        );
        
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['display_name'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please enter username and password.';
    }
}

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | RobiCodes</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="login-page">
        <div class="login-card">
            <h1>RobiCodes</h1>
            <p>Admin Panel Login</p>

            <?php if ($error): ?>
                <div class="login-error"><?php echo sanitizeOutput($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Enter username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Enter password" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn--primary">Sign In</button>
            </form>

            <p style="margin-top:1.5rem;font-size:0.75rem">
                <a href="../index.php" style="color:#6c6c8a">&#8592; Back to Portfolio</a>
            </p>
        </div>
    </div>
</body>
</html>
