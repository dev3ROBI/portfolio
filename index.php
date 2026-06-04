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
                    <i class="fas fa-briefcase"></i> View My Work
                </a>
                <a href="contact.php" class="btn btn--secondary">
                    <i class="fas fa-envelope"></i> Get In Touch
                </a>
                <a href="about.php" class="btn btn--ghost">
                    <i class="fas fa-user"></i> About Me
                </a>
            </div>

            <div class="hero__socials">
                <a href="https://github.com/dev3ROBI" target="_blank" rel="noopener noreferrer" class="hero__social-link" aria-label="GitHub">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://www.facebook.com/iam.robi69/" target="_blank" rel="noopener noreferrer" class="hero__social-link" aria-label="Facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="mailto:iam.robi693@gmail.com" class="hero__social-link" aria-label="Email">
                    <i class="fas fa-envelope"></i>
                </a>
                <a href="https://me.robicodes.xyz" target="_blank" rel="noopener noreferrer" class="hero__social-link" aria-label="Portfolio">
                    <i class="fas fa-globe"></i>
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
                        <i class="fas fa-code" style="font-size:2rem;color:var(--text-muted)"></i>
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
                               class="btn btn--ghost btn--sm"><i class="fas fa-link"></i> Code</a>
                        <?php endif; ?>
                        <?php if ($project['live_url']): ?>
                            <a href="<?php echo sanitizeOutput($project['live_url']); ?>" 
                               target="_blank" rel="noopener noreferrer" 
                               class="btn btn--primary btn--sm"><i class="fas fa-rocket"></i> Live</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:var(--spacing-2xl)" class="reveal">
            <a href="projects.php" class="btn btn--primary">View All Projects <i class="fas fa-arrow-right"></i></a>
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
                <div class="stat-card__icon"><i class="fas fa-chart-bar"></i></div>
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
