<?php
/**
 * Unified Login
 * Handles authentication for all user roles (Admin, User, Guest)
 */
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid form submission.';
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
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_display_name'] = $user['display_name'];
                $_SESSION['user_role'] = $user['role'];
                
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
                $error = 'Invalid credentials or account suspended.';
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
