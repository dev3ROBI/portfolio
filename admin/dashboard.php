<?php
/**
 * Admin Dashboard
 * Overview of portfolio statistics and quick management
 */
require_once __DIR__ . '/../config/database.php';

// Auth check with session timeout
$sessionTimeout = 3600; // 1 hour

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity'] > $sessionTimeout)) {
    session_destroy();
    header('Location: login.php');
    exit;
}
$_SESSION['admin_last_activity'] = time();

$totalProjects = dbGetRow("SELECT COUNT(*) as count FROM projects")['count'] ?? 0;
$totalMessages = dbGetRow("SELECT COUNT(*) as count FROM contact_messages")['count'] ?? 0;
$unreadMessages = dbGetRow("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0")['count'] ?? 0;
$totalSkills = dbGetRow("SELECT COUNT(*) as count FROM skills")['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | RobiCodes Admin</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>RobiCodes</h2>
            <nav>
                <a href="dashboard.php" class="active"><i class="fas fa-chart-bar"></i> Dashboard</a>
                <a href="projects.php"><i class="fas fa-code"></i> Projects</a>
                <a href="login.php?logout=1" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span><i class="fas fa-user"></i> <?php echo sanitizeOutput($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                </div>
            </div>

            <div class="admin-cards">
                <div class="admin-card">
                    <h3>Total Projects</h3>
                    <div class="value"><?php echo $totalProjects; ?></div>
                </div>
                <div class="admin-card">
                    <h3>Total Messages</h3>
                    <div class="value"><?php echo $totalMessages; ?></div>
                </div>
                <div class="admin-card">
                    <h3>Unread Messages</h3>
                    <div class="value"><?php echo $unreadMessages; ?></div>
                </div>
                <div class="admin-card">
                    <h3>Skills Listed</h3>
                    <div class="value"><?php echo $totalSkills; ?></div>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="glass-card" style="padding:1.5rem">
                <h3 style="margin-bottom:1rem">Recent Messages</h3>
                <?php
                $messages = dbGetAll(
                    "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5"
                );
                if (empty($messages)): ?>
                    <p style="color:#6c6c8a;text-align:center;padding:1rem">No messages yet.</p>
                <?php else: ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo sanitizeOutput($msg['name']); ?></td>
                                <td><?php echo sanitizeOutput($msg['email']); ?></td>
                                <td><?php echo sanitizeOutput(substr($msg['subject'] ?? 'No subject', 0, 30)); ?></td>
                                <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                                <td><?php echo $msg['is_read'] ? '<i class="fas fa-check-circle" style="color:#00cec9"></i> Read' : '<i class="fas fa-circle" style="color:#fdcb6e;font-size:0.6rem;vertical-align:middle"></i> Unread'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php
    // Handle logout
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
    ?>
</body>
</html>
