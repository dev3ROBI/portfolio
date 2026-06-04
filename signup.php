<?php
/**
 * User Signup
 * Allows new users to create an account with a default 'user' or 'guest' role.
 */
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid form submission.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $displayName = trim($_POST['display_name'] ?? '');
        $masterKey = $_POST['master_key'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($displayName) || empty($masterKey)) {
            $error = 'All fields are required.';
        } elseif ($masterKey !== getenv('SIGNUP_MASTER_KEY')) {
            $error = 'Invalid master key. Registration restricted.';
            securityLog("Unauthorized signup attempt with invalid master key", "warning");
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        } else {
            // Check if user exists
            $existing = dbGetRow("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1", 'ss', [$username, $email]);
            
            if ($existing) {
                $error = 'Username or email already exists.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $role = 'user'; // Default role
                
                $result = dbExecute(
                    "INSERT INTO users (username, email, password_hash, display_name, role, status) VALUES (?, ?, ?, ?, ?, 'active')",
                    'sssss',
                    [$username, $email, $passwordHash, $displayName, $role]
                );
                
                if ($result) {
                    $success = 'Account created successfully! You can now <a href="login.php">login</a>.';
                } else {
                    $error = 'Something went wrong. Please try again.';
                }
            }
        }
    }
}

$pageTitle = 'Sign Up';
include 'includes/header.php';
?>

<div class="login-page">
    <div class="login-card glass-card">
        <h1>Create Account</h1>
        <p>Join RobiCodes Community</p>

        <?php if ($error): ?>
            <div class="login-error"><?php echo sanitizeOutput($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="login-success" style="color:var(--success);background:rgba(0,255,0,0.1);padding:1rem;border-radius:var(--radius-md);margin-bottom:1.5rem">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="display_name">Full Name</label>
                <input type="text" id="display_name" name="display_name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
            </div>

            <div class="form-group">
                <label for="master_key">Registration Master Key</label>
                <input type="password" id="master_key" name="master_key" placeholder="Enter registration key" required>
            </div>

            <button type="submit" class="btn btn--primary">Sign Up</button>
        </form>

        <p style="margin-top:1.5rem;font-size:0.875rem">
            Already have an account? <a href="login.php" style="color:var(--primary)">Login</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
