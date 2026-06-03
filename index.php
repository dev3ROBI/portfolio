<?php
/**
 * Home Page
 * Hero section with typing effect, CTAs, social links, and feature highlights
 */
require_once 'config/database.php';
$pageTitle = 'Home';
$pageDescription = 'Robiul Islam (RobiCodes) - Professional Web & API Developer. Building modern, scalable applications with PHP, MySQL, and JavaScript.';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" aria-label="Hero introduction">
    <div class="container">
        <div class="hero__content">
            <div class="hero__badge">
                <span class="hero__badge-dot"></span>
                Available for projects
            </div>

            <p class="hero__greeting">Hello, I'm</p>
            <h1 class="hero__name">Robiul Islam</h1>

            <div class="hero__title">
                <span class="hero__typing" data-texts='["Web Developer","PHP Developer","API Developer","Software Engineer","Problem Solver"]'></span>
            </div>

            <p class="hero__description">
                I build modern, scalable web applications and APIs. 
                Passionate about clean code, creative solutions, and turning complex problems into elegant digital experiences.
            </p>

            <div class="hero__actions">
                <a href="projects.php" class="btn btn--primary">
                    &#128188; View My Work
                </a>
                <a href="contact.php" class="btn btn--secondary">
                    &#128231; Get In Touch
                </a>
                <a href="about.php" class="btn btn--ghost">
                    &#128100; About Me
                </a>
            </div>

            <div class="hero__socials">
                <a href="https://github.com/dev3ROBI" target="_blank" rel="noopener noreferrer" class="hero__social-link" aria-label="GitHub">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                </a>
                <a href="https://www.facebook.com/iam.robi69/" target="_blank" rel="noopener noreferrer" class="hero__social-link" aria-label="Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="mailto:iam.robi693@gmail.com" class="hero__social-link" aria-label="Email">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                </a>
                <a href="https://me.robicodes.xyz" target="_blank" rel="noopener noreferrer" class="hero__social-link" aria-label="Portfolio">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Preview -->
<section class="section" aria-label="Featured projects">
    <div class="container">
        <div class="section__header reveal">
            <h2 class="section__title">Featured Projects</h2>
            <p class="section__subtitle">A selection of my best work showcasing diverse skills and technologies</p>
        </div>

        <div class="projects__grid">
            <?php
            $featured = dbGetAll(
                "SELECT p.*, c.name as category_name 
                 FROM projects p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.featured = 1 AND p.status = 'completed' 
                 ORDER BY p.sort_order ASC 
                 LIMIT 3"
            );

            foreach ($featured as $project):
                $techs = json_decode($project['technologies'] ?? '[]', true);
                $desc = htmlspecialchars(substr($project['description'], 0, 120));
            ?>
            <article class="project-card glass-card reveal" 
                     data-category="<?php echo sanitizeOutput($project['category_name'] ?? 'general'); ?>"
                     data-title="<?php echo strtolower(sanitizeOutput($project['title'])); ?>"
                     data-description="<?php echo strtolower(sanitizeOutput($project['description'])); ?>"
                     data-techs="<?php echo strtolower(implode(' ', $techs)); ?>">
                <div class="project-card__image">
                    <?php if ($project['thumbnail'] && file_exists($project['thumbnail'])): ?>
                        <img src="<?php echo sanitizeOutput($project['thumbnail']); ?>" 
                             alt="<?php echo sanitizeOutput($project['title']); ?>" 
                             loading="lazy">
                    <?php else: ?>
                        &#128187;
                    <?php endif; ?>
                    <span class="project-card__featured">Featured</span>
                </div>
                <div class="project-card__body">
                    <h3 class="project-card__title"><?php echo sanitizeOutput($project['title']); ?></h3>
                    <p class="project-card__description"><?php echo sanitizeOutput($desc); ?>...</p>
                    <div class="project-card__techs">
                        <?php foreach ($techs as $tech): ?>
                            <span class="project-card__tech"><?php echo sanitizeOutput($tech); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="project-card__actions">
                        <?php if ($project['github_url']): ?>
                            <a href="<?php echo sanitizeOutput($project['github_url']); ?>" 
                               target="_blank" rel="noopener noreferrer" 
                               class="btn btn--ghost btn--sm">&#128279; Code</a>
                        <?php endif; ?>
                        <?php if ($project['live_url']): ?>
                            <a href="<?php echo sanitizeOutput($project['live_url']); ?>" 
                               target="_blank" rel="noopener noreferrer" 
                               class="btn btn--primary btn--sm">&#128640; Live</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:var(--spacing-2xl)" class="reveal">
            <a href="projects.php" class="btn btn--primary">View All Projects &#8594;</a>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="section" aria-label="Statistics overview">
    <div class="container">
        <div class="stats__grid">
            <?php
            $stats = dbGetAll("SELECT * FROM statistics ORDER BY sort_order ASC");
            foreach ($stats as $stat):
            ?>
            <div class="stat-card glass-card reveal">
                <div class="stat-card__icon">&#128202;</div>
                <div class="stat-card__value" 
                     data-target="<?php echo (int)$stat['value']; ?>" 
                     data-suffix="<?php echo sanitizeOutput($stat['suffix']); ?>">
                    0<?php echo sanitizeOutput($stat['suffix']); ?>
                </div>
                <p class="stat-card__label"><?php echo sanitizeOutput($stat['label']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
