<?php
/**
 * Header Component
 * 
 * Includes: meta tags, Open Graph, stylesheets, preloads, and opening body tags.
 * 
 * @param string $pageTitle    Current page title
 * @param string $pageDescription  Meta description
 * @param string $pageImage    Open Graph image URL
 */

$siteName = 'RobiCodes | Robiul Islam';
$pageTitle = $pageTitle ?? 'Developer Portfolio';
$pageDescription = $pageDescription ?? 'Robiul Islam (RobiCodes) - Professional Web & API Developer. PHP, MySQL, JavaScript, and Android development specialist.';
$pageImage = $pageImage ?? 'assets/images/og-image.jpg';
$pageUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '');
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0a0a1a">
    <meta name="color-scheme" content="dark light">

    <!-- SEO Meta -->
    <title><?php echo sanitizeOutput($pageTitle); ?> | <?php echo sanitizeOutput($siteName); ?></title>
    <meta name="description" content="<?php echo sanitizeOutput($pageDescription); ?>">
    <meta name="keywords" content="Robiul Islam, RobiCodes, Web Developer, PHP Developer, API Developer, Software Engineer, Bangladesh">
    <meta name="author" content="Robiul Islam">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo sanitizeOutput($pageTitle); ?> | <?php echo sanitizeOutput($siteName); ?>">
    <meta property="og:description" content="<?php echo sanitizeOutput($pageDescription); ?>">
    <meta property="og:image" content="<?php echo sanitizeOutput($pageImage); ?>">
    <meta property="og:url" content="<?php echo sanitizeOutput($pageUrl); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo sanitizeOutput($siteName); ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo sanitizeOutput($pageTitle); ?> | <?php echo sanitizeOutput($siteName); ?>">
    <meta name="twitter:description" content="<?php echo sanitizeOutput($pageDescription); ?>">
    <meta name="twitter:image" content="<?php echo sanitizeOutput($pageImage); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Preload Fonts & Critical CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/icons/favicon.svg">
    <link rel="apple-touch-icon" href="assets/icons/apple-touch-icon.png">
    <link rel="manifest" href="manifest.json">

    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Robiul Islam",
        "alternateName": "RobiCodes",
        "url": "https://me.robicodes.xyz",
        "sameAs": [
            "https://github.com/dev3ROBI",
            "https://www.facebook.com/iam.robi69/"
        ],
        "jobTitle": "Web & API Developer",
        "email": "iam.robi693@gmail.com"
    }
    </script>
</head>
<body>

    <!-- Loading Screen -->
    <div class="loader" role="status" aria-label="Loading">
        <div class="loader__spinner">
            <div></div>
            <div></div>
        </div>
        <p class="loader__text">Loading...</p>
    </div>

    <!-- Custom Cursor -->
    <div class="custom-cursor" aria-hidden="true"></div>
    <div class="custom-cursor--dot" aria-hidden="true"></div>

    <!-- Particles Background -->
    <canvas id="particles-canvas" aria-hidden="true"></canvas>

    <!-- Toast Container -->
    <div class="toast-container" aria-live="polite"></div>

    <!-- Back to Top -->
    <button class="back-to-top" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>

    <?php include 'includes/navbar.php'; ?>

    <main>
