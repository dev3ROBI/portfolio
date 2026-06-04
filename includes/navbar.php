<?php
/**
 * Navigation Bar Component
 * 
 * Features: Sticky navbar, mobile responsive, active link highlighting, theme toggle
 */
?>
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container">
        <a href="index.php" class="navbar__brand">
            <span class="navbar__brand-text">RobiCodes</span>
            <span class="navbar__brand-dot"></span>
        </a>

        <button class="navbar__toggle" aria-label="Toggle navigation menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="navbar__overlay"></div>

        <ul class="navbar__links" role="menubar">
            <li role="none"><a href="index.php" class="navbar__link" role="menuitem">Home</a></li>
            <li role="none"><a href="projects.php" class="navbar__link" role="menuitem">Projects</a></li>
            <li role="none"><a href="skills.php" class="navbar__link" role="menuitem">Skills</a></li>
            <li role="none"><a href="contact.php" class="navbar__link" role="menuitem">Contact</a></li>
            
            <?php if (isLoggedIn()): ?>
                <?php if (hasRole('admin')): ?>
                    <li role="none"><a href="dashboard-x92/dashboard.php" class="navbar__link navbar__link--special" role="menuitem">Admin</a></li>
                <?php endif; ?>
                <li role="none" class="navbar__user">
                    <span class="navbar__link"><i class="fas fa-user-circle"></i> <?php echo sanitizeOutput($_SESSION['user_display_name']); ?></span>
                    <ul class="navbar__dropdown">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li role="none"><a href="login.php" class="navbar__link" role="menuitem">Login</a></li>
                <li role="none"><a href="signup.php" class="btn btn--primary btn--sm" role="menuitem" style="margin-left:var(--spacing-md)">Sign Up</a></li>
            <?php endif; ?>

            <li role="none">
                <button class="theme-toggle" aria-label="Toggle theme">
                    <span class="theme-toggle__thumb" aria-hidden="true"><i class="fas fa-moon" id="theme-icon"></i></span>
                </button>
            </li>
        </ul>
    </div>
</nav>
