<?php
/**
 * Admin Login
 * Secure authentication for portfolio management
 */
require_once __DIR__ . '/../config/database.php';

$error = '';

// Login rate limiting
$maxAttempts = 5;
$lockoutTime = 900; // 15 minutes

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}

// Clean expired attempts
$_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], function($time) use ($lockoutTime) {
    return $time > (time() - $lockoutTime);
});

$isLocked = count($_SESSION['login_attempts']) >= $maxAttempts;

if ($isLocked) {
    $remaining = $lockoutTime - (time() - min($_SESSION['login_attempts']));
    $error = 'Too many login attempts. Please try again in ' . ceil($remaining / 60) . ' minutes.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($isLocked) {
        $error = 'Too many login attempts. Please try again later.';
    } elseif (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!empty($username) && !empty($password)) {
            $user = dbGetRow(
                "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1",
                'ss',
                [$username, $username]
            );
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Clear attempts on success
                $_SESSION['login_attempts'] = [];
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['display_name'];
                
                header('Location: dashboard.php');
                exit;
            } else {
                $_SESSION['login_attempts'][] = time();
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Please enter username and password.';
        }
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
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
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
                <a href="../index.php" style="color:#6c6c8a"><i class="fas fa-arrow-left"></i> Back to Portfolio</a>
            </p>
        </div>
    </div>
</body>
</html>
