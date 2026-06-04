<?php
$pageTitle = 'Page Not Found';
$pageDescription = 'The requested page could not be found.';
include 'includes/header.php';
?>

<section class="section hero" style="min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center">
    <div class="container">
        <h1 style="font-size:8rem;line-height:1;margin-bottom:1rem;background:var(--gradient-primary);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">404</h1>
        <h2 style="margin-bottom:1rem">Page Not Found</h2>
        <p style="color:var(--text-muted);margin-bottom:2rem">The page you are looking for doesn't exist or has been moved.</p>
        <a href="index.php" class="btn btn--primary"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
