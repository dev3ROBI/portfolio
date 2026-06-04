<?php
/**
 * Unified Login
 * Handles authentication for all user roles (Admin, User, Guest)
 */
require_once 'config/database.php';

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
        securityLog("Rate limit hit for user login attempt", "warning");
    } elseif (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid form submission.';
        securityLog("CSRF mismatch on login attempt", "danger");
    } else {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!empty($login) && !empty($password)) {
            $user = dbGetRow(
                "SELECT * FROM users WHERE (username = ? OR email = ?) AND status = 'active' LIMIT 1",
                'ss',
                [$login, $login]
            );
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Clear attempts on success
                $_SESSION['login_attempts'] = [];
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_display_name'] = $user['display_name'];
                $_SESSION['user_role'] = $user['role'];
                
                securityLog("Successful login: " . $user['username'], "info");
                
                // Update last login
                dbExecute("UPDATE users SET last_login = NOW() WHERE id = ?", 'i', [$user['id']]);
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: dashboard-x92/dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $_SESSION['login_attempts'][] = time();
                $error = 'Invalid credentials or account suspended.';
                securityLog("Failed login attempt for: " . $login, "warning");
            }
        } else {
            $error = 'Please enter all fields.';
        }
    }
}

// Redirect if already logged in
if (isLoggedIn()) {
    if (hasRole('admin')) {
        header('Location: dashboard-x92/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$pageTitle = 'Login';
include 'includes/header.php';
?>

<div class="login-page">
    <div class="login-card glass-card">
        <h1>Welcome Back</h1>
        <p>Login to your account</p>

        <?php if ($error): ?>
            <div class="login-error"><?php echo sanitizeOutput($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <div class="form-group">
                <label for="login">Username or Email</label>
                <input type="text" id="login" name="login" placeholder="Enter username or email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn--primary">Sign In</button>
        </form>

        <p style="margin-top:1.5rem;font-size:0.875rem">
            Don't have an account? <a href="signup.php" style="color:var(--primary)">Sign Up</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
